<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Poll voters model
 *
 * @author Victor Michnowicz
 * @category Modules
 *
 */
class Poll_voters_m extends MY_Model {
	
	/**
	 * Constructor method
	 * 
	 * @author Victor Michnowicz
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Call the parent's constructor
		parent::__construct();
	}
	
	/**
	 * Record user details in database
	 * 
	 * This allows us to make sure the same user does not vote multiple times in the same poll
	 *
	 * @author Victor Michnowicz
	 * @access public
	 * @param int poll ID
	 * @return void
	 */		
	public function record_voter($poll_id)
	{
		$data = array(
			'poll_id' 		=> $poll_id,
			'user_id' 		=> $this->ion_auth->logged_in() ? $this->session->userdata('user_id') : NULL,
			'session_id' 	=> $this->session->userdata('session_id'),
			'ip_address' 	=> $this->session->userdata('ip_address'),
			'timestamp' 	=> time()
		);
		
		$this->db->insert('poll_voters', $data);
	}
	
	/**
	 * Has current user already voted in this poll?
	 *
	 * @author Victor Michnowicz
	 * @access public
	 * @param int poll ID
	 * @return null
	 */	
	public function already_voted($poll_id)
	{
		// IP address are considered unique for one week
		$expire = 604800;
		$now = time();
		
		// First, let's see if we can find this poll in the userdata
		if ( $this->session->userdata('poll_' . $poll_id) )
		{
			return TRUE;
		}
		
		$user_id = $this->ion_auth->logged_in() ? $this->session->userdata('user_id') : NULL;
		$session_id = $this->session->userdata('session_id');;
		$ip_address = $this->session->userdata('ip_address');
		$current_time = time();
		
		/*
		 * Get all poll voters that have voted in a particular poll
		 * 
		 * Where the user ID is the same as the current user -OR-
		 * The session ID is the same as the current user -OR-
		 * The IP address is the same as the current user (but assume that an IP is unique to a user for only one week)
		 */
		$query = $this->db->query("
			SELECT *
			FROM poll_voters
			WHERE poll_id = $poll_id AND
				(
					user_id = '$user_id' OR
					session_id = '$session_id' OR
					(ip_address = '$ip_address' AND timestamp + $expire < $now)
				)
		");
		
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}