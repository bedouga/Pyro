<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is a tips module for PyroCMS
 *
 * @author 	Sean-Paul MckEe
 * @package 	Bedouga
 * @subpackage 	Tips Module
 */
class Tips_m extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    //get one item
    function get($term_id) {
        $this->db->where(array('term_id' => $term_id));
        return $this->db->get('beer_tips')->row();
    }

    //get all items
    public function get_all() {
        return $this->db
                ->order_by('tip_name', 'asc')
                ->where('tip_status', '1')
                ->get('beer_tips')
                ->result();
    }
    
    function get_many_by($params = array())
	{

		// Limit the results based on 1 number or 2 (2nd is offset)
		if (isset($params['limit']) && is_array($params['limit']))
			$this->db->limit($params['limit'][0], $params['limit'][1]);
		elseif (isset($params['limit']))
			$this->db->limit($params['limit']);
		return $this->get_all();
	}

    public function update($term_id = 0, $input = array()) {

        $return = $this->db->update('beer_tips', array(
                    'term_name' => $input['term_name'],
                    'term_desc' => $input['term_desc'],
                    'term_status' => $input['term_status']
                        ), array('term_id' => $term_id));

        return $return;
    }

    public function delete($term_id = 0) {
        $this->db->trans_start();

        $this->db->where_in('term_id', $term_id);
        $this->db->delete('beer_tips');

        $this->db->trans_complete();

        return $this->db->trans_status() !== FALSE ? $ids : FALSE;
    }

    //create a new item
    public function create_item($input) {
        $to_insert = array(
            'term_name' => $input['term_name'],
            'term_desc' => $input['term_desc']
        );

        return $this->db->insert('beer_tips', $to_insert);
    }

    //make sure the slug is valid
    public function _check_slug($slug) {
        $slug = strtolower($slug);
        $slug = preg_replace('/\s+/', '-', $slug);

        return $slug;
    }

}