<?php

class Projects extends CI_Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('project');
        $this->load->helper('tree');

        // load language file
        $this->lang->load(strtolower($this->router->class));
    }

	/**
	 * Lists all projects the user has access to.
	 */
	public function getAvailable() {
		$path = $this->input->get_post('node');

		switch($path) {
			case '/projects/own':
				$projects = $this->project->getOwn();
				array_walk($projects, 'set_tree_icons', base_url() . 'assets/images/icons/document.png');
				break;
			case '/projects/shared':
				$projects = $this->project->getShared();
				array_walk($projects, 'set_tree_icons', base_url() . 'assets/images/icons/document.png');
				break;
			case '/projects/public':
				$projects = $this->project->getPublic();
				array_walk($projects, 'set_tree_icons', base_url() . 'assets/images/icons/document.png');
				break;
			default:
				$projects = array(
					array(
						'id' => '/projects/own',
						'cls' => 'folder',
						'text' => lang('projects_own'),
						'icon' => base_url() . 'assets/images/icons/folder.png',),
					array(
						'id' => '/projects/shared',
						'cls' => 'leaf',
						'text' => lang('projects_shared'),
						'icon' => base_url() . 'assets/images/icons/folder-share.png',
					),
					array(
						'id' => '/projects/public',
						'cls' => 'folder',
						'text' => lang('projects_public'),
						'icon' => base_url() . 'assets/images/icons/folder-network.png',
					),
				);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($projects));
		//	->set_output(json_encode(array('count' => $count, 'projects' => $projects)));
	}

	public function detail($projects, $area, $id) {
		$result = $this->db->get_where('projects', array('id' => $id))->row_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('result' => $result)));
	}
}
