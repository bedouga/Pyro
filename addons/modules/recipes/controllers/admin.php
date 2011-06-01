<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a recipes module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	recipes Module
 */
class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        // Load all the required classes
        $this->load->model('recipes_m');
        $this->load->library('form_validation');
        $this->lang->load('recipes');

        // Set the validation rules
        $this->item_validation_rules = array(
            array(
                'field' => 'recipe_name',
                'label' => 'recipe name',
                'rules' => 'trim|max_length[255]|required'
            ),
            array(
                'field' => 'recipe_desc',
                'label' => 'Description',
                'rules' => 'trim|max_length[255]|required'
            )
        );
        
        $this->form_validation->set_rules('style_cat_name', 'Style Category', 'required'); 

        $this->template
                ->set_partial('shortcuts', 'admin/partials/shortcuts')
                ->append_metadata(js('recipes_form.js', 'recipes'))
                ->append_metadata(css('admin.css', 'recipes'));
    }

    /**
     * List all items
     */
    public function index() {
        $items = $this->recipes_m->get_all();

        // Load the view
        $this->data->items = & $items;
        $this->template
                ->title($this->module_details['name'])
                ->build('admin/items', $this->data);
    }

    public function create_item() {
        
        // Grab all the Style Cats
        $this->data->style_cats =  $this->recipes_m->get_all_style_cats();
        
        // Grab all the Style Cats
        $this->data->styles =  $this->recipes_m->get_all_styles();
        
        // Set the validation rules
        $this->form_validation->set_rules($this->item_validation_rules);

        if ($this->form_validation->run()) {
            // Create the item
            if ($this->recipes_m->create_item($_POST)) {
                
                // All good...
                $this->session->set_flashdata('success', lang('recipes.success'));
                redirect('admin/recipes');
            }
            // Something went wrong..
            else {
                $this->session->set_flashdata('error', lang('recipes.error'));
                redirect('admin/recipes/form');
            }
        } else {
            // Go through all the known fields and get the post values
            foreach ($this->item_validation_rules as $key => $field) {
                $post->$field['field'] = set_value($field['field']);
            }
        }

        // Load the view
        $this->template
                ->title($this->module_details['name'], lang('recipes.new_item'))
                ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                ->set('post', $post)
                ->build('admin/recipes_form', $this->data);
    }
    
   
/**
     * Edit recipe
     * @access public
     * @param int $id the ID of the blog post to edit
     * @return void
     */
    public function edit_item($recipe_id = 0) {
        $recipe_id OR redirect('admin/recipes');

        $this->load->library('form_validation');

        $this->form_validation->set_rules($this->item_validation_rules);

        $post = $this->recipes_m->get($recipe_id);

        $this->recipe_id = $post->recipe_id;

        if ($this->form_validation->run()) {
            $result = $this->recipes_m->update($recipe_id, array(
                        'recipe_name' => $this->input->post('recipe_name'),
                        'recipe_desc' => $this->input->post('recipe_desc'),
                        'recipe_status' => $this->input->post('recipe_status')
                    ));

            if ($result) {
                $this->session->set_flashdata(array('success' => sprintf($this->lang->line('recipe_edit_success'), $this->input->post('recipe_name'))));
            } else {
                $this->session->set_flashdata(array('error' => $this->lang->line('recipe_edit_error')));
            }

            // Redirect back to the form or main page
            $this->input->post('btnAction') == 'save_exit' ? redirect('admin/recipes') : redirect('admin/recipes/edit_item/' . $id);
        }

        // Go through all the known fields and get the post values
        foreach (array_keys($this->item_validation_rules) as $field) {
            if (isset($_POST[$field])) {
                $post->$field = $this->form_validation->$field;
            }
        }

        // Load WYSIWYG editor
        $this->template
                ->title($this->module_details['name'], sprintf(lang('recipe_edit_title'), $post->recipe_name))
                ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                ->set('post', $post)
                ->build('admin/form');
    }
    
    public function delete_item($recipe_id = 0) {
        // Delete one
        $ids = ($recipe_id) ? array($recipe_id) : $this->input->post('action_to');

        // Go through the array of slugs to delete
        if (!empty($ids)) {
            $post_recipes = array();
            foreach ($ids as $recipe_id) {
                // Get the current page so we can grab the id too
                if ($post = $this->recipes_m->get($recipe_id)) {
                    $this->recipes_m->delete($recipe_id);

                    // Wipe cache for this model, the content has changed
                    $this->cache->delete('recipes_m');
                    $post_recipes[] = $post->recipes;
                }
            }
        }

        // Some pages have been deleted
        if (!empty($post_recipes)) {
            // Only deleting one page
            if (count($post_recipes) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('recipes_delete_success'), $post_recipes[0]));
            }
        }
        // For some reason, none of them were deleted
        else {
            $this->session->set_flashdata('notice', lang('recipes_delete_error'));
        }

        redirect('admin/recipes');
    }

}
