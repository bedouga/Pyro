<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a recipes module for PyroCMS
 *
 * @author 		Sean-Paul MckEe
 * @package 	PyroCMS
 * @subpackage 	recipes Module
 */
class Recipes_m extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    //get one item
    function get($recipe_id) {
        $this->db->where(array('recipe_id' => $recipe_id));
        return $this->db->get('beer_recipes')->row();
    }

    //get all items
    public function get_all($recipe_status = 0) {
        return $this->db
                ->order_by('recipe_name', 'asc')
                ->where('recipe_status', $recipe_status)
                //->join('beer_style_cats c', 'beer.category_id = c.id', 'left')
                ->get('beer_recipes')
                ->result();
    }
    
    //get all style cats
    public function get_all_style_cats() {
        return $this->db
                ->order_by('style_cat_name', 'asc')
                ->get('beer_styles_cats')
                ->result();
    }
    
    //get all style cats
    public function get_all_styles() {
        return $this->db
                ->order_by('style_id', 'asc')
                ->get('beer_styles')
                ->result();
    }


    public function update($recipe_id = 0, $input = array()) {

        $return = $this->db->update('beer_recipes', array(
                    'recipe_name' => $input['recipe_name'],
                    'recipe_desc' => $input['recipe_desc'],
                    'recipe_status' => $input['recipe_status']
                        ), array('recipe_id' => $recipe_id));

        return $return;
    }

    public function delete($recipe_id = 0) {
        $this->db->trans_start();

        $this->db->where_in('recipe_id', $recipe_id);
        $this->db->delete('beer_recipes');

        $this->db->trans_complete();

        return $this->db->trans_status() !== FALSE ? $ids : FALSE;
    }

    //create a new item
    public function create_item($input) {
        $to_insert = array(
            'recipe_name' => $input['recipe_name'],
            'recipe_desc' => $input['recipe_desc']
        );

        return $this->db->insert('beer_recipes', $to_insert);
    }

    //make sure the slug is valid
    public function _check_slug($slug) {
        $slug = strtolower($slug);
        $slug = preg_replace('/\s+/', '-', $slug);

        return $slug;
    }

}
