<?php defined('BASEPATH') or exit('No direct script access allowed');

class Widget_Polls extends Widgets {
	
	public $title = 'Poll Widget';
	public $description = 'Display a poll.';
	public $author = 'Victor Michnowicz';
	public $website = 'http://www.vmichnowicz.com/';
	public $version = '0.2';
	
	public $fields = array(
		array(
			'field'   => 'poll_id',
			'label'   => 'Poll',
			'rules'   => 'required'
		)
	);
	
	public function __construct()
	{
		// Load models
		$this->load->model('modules/module_m');
		$this->load->model('polls/polls_m');
		$this->load->model('polls/poll_options_m');
		$this->load->model('polls/poll_voters_m');
	}
	
	public function run($options)
	{
		// Get poll ID
		$poll_id = $options['poll_id'];
		
		// Get poll data
		$data = $this->polls_m->get_poll_by_id($poll_id);
		
		// Has user alread voted in this poll?
		$data['already_voted'] = $this->poll_voters_m->already_voted($poll_id);
		
		// Get options
		$data['poll_options'] = $this->poll_options_m->get_all_where_poll_id($poll_id);
		
		// Get total votes
		$data['total_votes'] = $this->poll_options_m->get_total_votes($poll_id);
		
		// Send data
		return $data;
	}
	
	public function form()
	{
		// Get all polls
		$polls = $this->polls_m->get_all();
		return array('polls' => $polls);
	}
	
}