<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Tips extends Module {

    public $version = '1.0';

    public function info() {
        return array(
            'name' => array(
                'en' => 'Tips'
            ),
            'description' => array(
                'en' => 'This is a Bedouga module tip_comments.'
            ),
            'frontend' => TRUE,
            'backend' => TRUE,
            'menu' => 'content'
        );
    }

    public function install() {
        // It worked!
        return TRUE;
    }

    public function uninstall() {
        
    }

    public function upgrade($old_version) {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help() {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}

/* End of file details.php */