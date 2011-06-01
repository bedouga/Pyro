<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a tips module for PyroCMS
 *
 * @author 	Sean-Paul mcKee
 * @website	http://sean-paul.com
 * @package     Bedouga
 * @subpackage 	Tips Module
 */
class Tips extends Public_Controller {

    public function __construct() {
        parent::__construct();

        // Load the required classes
        $this->load->model('tips_m');
        $this->lang->load('tips');
/*
        $this->template
                ->append_metadata(css('tips.css', 'tips'))

 */
    }

    /**
     * All items
     */
    public function index() {
        //$this->data->pagination = create_pagination('tips', $this->tips_m->get_all(array('status' => '1')), NULL, 3);
        $this->data->items = $this->tips_m->get_all();
        //$this->data->items = $this->tips_m->limit($this->data->pagination['limit'])->get_many_by();	

        //$this->data->pagination = create_pagination('tips', count($this->tips_m->get_all()), $limit);
        $this->template->title($this->module_details['name'], 'the rest of the page title')
                ->build('index', $this->data);
    }

}