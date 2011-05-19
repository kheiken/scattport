<?php

/**
 * @author Karsten Heiken, karsten@disposed.de
 */
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
	public function getAll() {

		$projects = $this->db->get('projects')->result_array();
		$this->load->view('web/projects/list',
				array('projects' => $projects));
	}

	public function detail($project_id) {
		$project = $this->project->get($project_id);
		$project['configs'] = $this->project->getConfigurations($project_id);

		$this->load->view('web/projects/detail', $project);
	}

	public function create() {
		$this->load->library('form_validation');
		$this->load->helper('date');
		$this->form_validation->set_rules('f_name', "Projektname",
				'required|max_length[100]|xss_clean|callback_unique');
		$this->form_validation->set_rules('f_description', "Beschreibung", 'required|xss_clean');
		
		if ($this->form_validation->run() == true) {
			
			// populate fields for the new project
			$project['name'] = $this->form_validation->set_value('f_name');
			$project['description'] = $this->form_validation->set_value('f_description');
			$foo = $this->project->create($project);

			redirect('web/projects/getAll', 'refresh');

		} else {
			$this->data['message'] = validation_errors() ? validation_errors() : null;
			$this->data['name'] = $this->form_validation->set_value('f_name');
			$this->data['description'] = $this->form_validation->set_value('f_description');

			$this->load->view('web/projects/create', $this->data);
		}
	}

	/**
	 * Checks if the user already has a project with the given name.
	 * 
	 * It's okay if different users have the same project - even if that
	 * will complicate sharing.
	 *
	 * @param string $project_name
	 * @return boolean
	 */
	function unique($project_name) {
		$query = $this->db->get_where('projects',
					array(
						'name' => $project_name,
						'owner' => $this->session->userdata('user_id'))
					);
			if($query->num_rows() > 0)
				return false;
			else
				return true;
	}

}
