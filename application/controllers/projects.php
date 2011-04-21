<?php

class Projects extends CI_Controller {

	/**
	 * List all projects the user has access to.
	 */
	public function getAvailable() {
		$this->load->model('project');
		$this->load->helper('tree');

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
						'text' => "Eigene Projekte",
						'icon' => base_url() . 'assets/images/icons/folder.png',),
					array(
						'id' => '/projects/shared',
						'cls' => 'leaf',
						'text' => "Für mich freigegeben",
						'icon' => base_url() . 'assets/images/icons/folder-share.png',
					),
					array(
						'id' => '/projects/public',
						'cls' => 'folder',
						'text' => "Öffentliche Projekte",
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
