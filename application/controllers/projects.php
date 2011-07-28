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
        $this->load->model('trial');
        $this->load->helper('tree');

        // load language file
        $this->lang->load(strtolower($this->router->class));
    }

	/**
	 * Create a new project.
	 */
	public function create() {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$config = array(
			array(
				'field' => 'name',
				'label' => 'Projektname',
				'rules' => 'trim|required|min_length[3]|max_length[100]|xss_clean',
			),
			array(
				'field' => 'description',
				'label' => 'Beschreibung',
				'rules' => 'trim|required|xss_clean',
			),
			array(
				'field' => 'defaultmodel',
				'label' => '3D-Modell',
			),
			array(
				'field' => 'defaultconfig',
				'label' => 'Standard-Konfiguration',
			),
		);
		
		$this->form_validation->set_rules($config);


		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('project/new');
		}
		else
		{
			// TODO: handle file upload

			$data = array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'defaultmodel' => "todo",
				'defaultconfig' => "todo",
			);

			$result = $this->project->create($data);
			if($result)
				redirect('/projects/detail/' . $result, 'refresh');
			else {
				$tpl['error'][] = "Das Projekt konnte nicht gespeichert werden.";
				$this->load->view('project/new', $tpl);
			}
		}

	}

	public function index() {
		$projects = $this->project->getAll();
		
		$tpl['projects'] = $projects;
		$this->load->view('project/list', $tpl);
	}

	public function detail($prj_id) {
		$project = $this->project->getById($prj_id);
		$trials = $this->trial->getByProjectId($prj_id);
		
		$tpl['project'] = $project;
		$tpl['trials'] = $trials;
		$tpl['jobsDone'] = null;
		$this->load->view('project/detail', $tpl);
	}
}
