<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a tersm module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Terms Module
 */
class Terms extends Public_Controller {

    public function __construct() {
        parent::__construct();

        // Load the required classes
        $this->load->model('terms_m');
        $this->lang->load('terms');

        $this->template->append_metadata(css('terms.css', 'terms'))
                ->append_metadata(js('terms.js', 'terms'));
    }

    /**
     * All items
     */
    public function index() {
        //$this->data->pagination = create_pagination('terms', $this->terms_m->get_all(array('status' => '1')), NULL, 3);
        $this->data->items = $this->terms_m->get_all();
        //$this->data->items = $this->terms_m->limit($this->data->pagination['limit'])->get_many_by();	

        //$this->data->pagination = create_pagination('terms', count($this->terms_m->get_all()), $limit);
        $this->template->title($this->module_details['name'], 'the rest of the page title')
                ->build('index', $this->data);
    }

}