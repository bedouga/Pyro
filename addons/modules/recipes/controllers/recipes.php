<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a reipces module for PyroCMS
 *
 * @author 	Sean-Paul McKee
 * @package 	PyroCMS
 * @subpackage 	Recipes Module
 */
class recipes extends Public_Controller {

    public function __construct() {
        parent::__construct();

        // Load the required classes
        $this->load->model('recipes_m');
        $this->lang->load('recipes');
        
        $this->load->library('form_validation');
        
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

        $this->template->append_metadata(css('recipes.css', 'recipes'))
                ->append_metadata(js('recipes.js', 'recipes'));
    }

    /**
     * All items
     */
    public function index() {
        $this->data->items = $this->recipes_m->get_all();

        $this->template
                ->title($this->module_details['name'], 'the rest of the page title')
                ->build('index', $this->data);
    }
    
    public function add() {
                
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
                redirect('user_dash/add_recipe');
            }
            // Something went wrong..
            else {
                $this->session->set_flashdata('error', lang('recipes.error'));
                redirect('user_dash/add_recipe');
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
                ->build('user_dash/add_recipe', $this->data);
    }

}