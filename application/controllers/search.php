<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Search projects and experiments.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Search extends CI_Controller {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('experiment');
		$this->load->helper('typography');
	}

	/**
	 *
	 */
	public function index() {
		if ($this->input->get('query') != '') {
			$query = explode(" ", $this->input->get('query'));

			$data['projects'] = $this->project->search($this->input->get('query'), $this->access->isAdmin());
			$data['experiments'] = $this->experiment->search($this->input->get('query'), false, $this->access->isAdmin());

			$this->load->view('search/results', $data);
		} else {
			$this->load->view('search/new');
		}
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
