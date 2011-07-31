<?php

/**
 * Trials are used to store different variations of the same project.
 *
 * @author Karsten Heiken, karsten@disposed.de
 */
class Trials extends CI_Controller {

	/**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('trial');
		$this->load->model('project');

        // load language file
        // $this->lang->load(strtolower($this->router->class));
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
		);

		$this->form_validation->set_rules($config);


		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('trial/new');
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

			$result = $this->trial->create($data);
			if($result)
				redirect('/trial/detail/' . $result, 'refresh');
			else {
				$tpl['error'][] = "Der Versuch konnte nicht gespeichert werden.";
				$this->load->view('trial/new', $tpl);
			}
		}

	}
}