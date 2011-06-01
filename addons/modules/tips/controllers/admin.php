<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a terms module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Terms Module
 */
class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        // Load all the required classes
        $this->load->model('tips_m');
        $this->load->library('form_validation');
        $this->lang->load('tips');

        // Set the validation rules
        $this->item_validation_rules = array(
            array(
                'field' => 'tip_name',
                'label' => 'Tip name',
                'rules' => 'trim|max_length[255]|required'
            ),
            array(
                'field' => 'tip_desc',
                'label' => 'Description',
                'rules' => 'trim|max_length[255]|required'
            )
        );

        $this->template
                ->set_partial('shortcuts', 'admin/partials/shortcuts')
                ->append_metadata(js('admin.js', 'tips'))
                ->append_metadata(css('admin.css', 'tips'));
    }

    /**
     * List all items
     */
    public function index() {
        $items = $this->tips_m->get_all();

        // Load the view
        $this->data->items = & $items;
        $this->template
                ->title($this->module_details['name'])
                ->build('admin/items', $this->data);
    }

    public function create_item() {
        // Set the validation rules
        $this->form_validation->set_rules($this->item_validation_rules);

        if ($this->form_validation->run()) {
            // Create the item
            if ($this->tips_m->create_item($_POST)) {
                // All good...
                $this->session->set_flashdata('success', lang('tips.success'));
                redirect('admin/tips');
            }
            // Something went wrong..
            else {
                $this->session->set_flashdata('error', lang('tips.error'));
                redirect('admin/tips/form');
            }
        } else {
            // Go through all the known fields and get the post values
            foreach ($this->item_validation_rules as $key => $field) {
                $post->$field['field'] = set_value($field['field']);
            }
        }

        // Load the view
        $this->template
                ->title($this->module_details['name'], lang('tips.new_item'))
                ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                ->set('post', $post)
                ->build('admin/form', $this->data);
    }
    
    

/**
     * Edit term
     * @access public
     * @param int $id the ID of the blog post to edit
     * @return void
     */
    public function edit_item($term_id = 0) {
        $term_id OR redirect('admin/tips');

        $this->load->library('form_validation');

        $this->form_validation->set_rules($this->item_validation_rules);

        $post = $this->tips_m->get($term_id);

        $this->term_id = $post->term_id;

        if ($this->form_validation->run()) {
            $result = $this->tips_m->update($term_id, array(
                        'term_name' => $this->input->post('term_name'),
                        'term_desc' => $this->input->post('term_desc'),
                        'term_status' => $this->input->post('term_status')
                    ));

            if ($result) {
                $this->session->set_flashdata(array('success' => sprintf($this->lang->line('term_edit_success'), $this->input->post('term_name'))));
            } else {
                $this->session->set_flashdata(array('error' => $this->lang->line('term_edit_error')));
            }

            // Redirect back to the form or main page
            $this->input->post('btnAction') == 'save_exit' ? redirect('admin/tips') : redirect('admin/tips/edit_item/' . $id);
        }

        // Go through all the known fields and get the post values
        foreach (array_keys($this->item_validation_rules) as $field) {
            if (isset($_POST[$field])) {
                $post->$field = $this->form_validation->$field;
            }
        }

        // Load WYSIWYG editor
        $this->template
                ->title($this->module_details['name'], sprintf(lang('term_edit_title'), $post->term_name))
                ->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
                ->set('post', $post)
                ->build('admin/form');
    }
    
    public function delete_item($term_id = 0) {
        // Delete one
        $ids = ($term_id) ? array($term_id) : $this->input->post('action_to');

        // Go through the array of slugs to delete
        if (!empty($ids)) {
            $post_tips = array();
            foreach ($ids as $term_id) {
                // Get the current page so we can grab the id too
                if ($post = $this->tips_m->get($term_id)) {
                    $this->tips_m->delete($term_id);

                    // Wipe cache for this model, the content has changed
                    $this->cache->delete('tips_m');
                    $post_tips[] = $post->tips;
                }
            }
        }

        // Some pages have been deleted
        if (!empty($post_tips)) {
            // Only deleting one page
            if (count($post_tips) == 1) {
                $this->session->set_flashdata('success', sprintf($this->lang->line('tips_delete_success'), $post_terms[0]));
            }
        }
        // For some reason, none of them were deleted
        else {
            $this->session->set_flashdata('notice', lang('tips_delete_error'));
        }

        redirect('admin/tips');
    }

}
