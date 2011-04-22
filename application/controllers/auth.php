<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Authentication controller.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Auth extends CI_Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('access');
        $this->load->library('form_validation');
        $this->load->model('user');
    }

    //redirect if needed, otherwise display the user list
    public function index() {
        if (!$this->access->loggedIn()) {
            redirect('auth/login');
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = validation_errors() ? validation_errors() : $this->session->flashdata('message');

            //$this->data['users'] = $this->access->getUsers();
            $this->load->view('index', $this->data);
        }
    }

    /**
     * Logs the user in - or not ;-)
     */
    public function login() {
        if ($this->access->loggedIn()) {
            redirect('welcome');
        }

        // validate form input
        $this->form_validation->set_rules('username', "Benutzername", 'required');
        $this->form_validation->set_rules('password', "Passwort", 'required');

        if ($this->form_validation->run() == true) {
            // check for "remember me"
            $remember = (boolean) $this->input->post('remember');

            if ($this->access->login($this->input->post('username'), $this->input->post('password'), $remember)) {
                $this->data['success'] = true;
            } else { // if the login was un-successful
                $this->data['success'] = false;
                $this->data['message'] = $this->access->errors();
            }

            // output json data
            $this->output->set_content_type('application/json')
                    ->set_output(json_encode($this->data));
        } else {
            $this->data['message'] = validation_errors() ? validation_errors() : null;
            $this->data['username'] = $this->form_validation->set_value('username');

            $this->load->view('auth/login', $this->data);
        }
    }

    /**
     * Logs the user out.
     */
    public function logout() {
        $logout = $this->access->logout();
        redirect('auth');
    }

    /**
     * Allows users to register.
     */
    public function register() {
        if ($this->access->loggedIn()) {
            redirect('welcome');
        }

        // validate form input
        $this->form_validation->set_rules('username', "Username", 'required');
        $this->form_validation->set_rules('realname', "Realname", 'required');
        $this->form_validation->set_rules('email', "Email address", 'required|valid_email');
        $this->form_validation->set_rules('password', "Password", 'required|min_length[' . $this->config->item('min_password_length', 'access') . ']|max_length[' . $this->config->item('max_password_length', 'access') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', "Password confirmation", 'required');

        if ($this->form_validation->run() == true) {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'realname' => $this->input->post('realname'),
            );
        }

        if ($this->form_validation->run() == true && $this->access->register($username, $password, $email, $additional_data))  {
            // redirect them to the login page
            $this->session->set_flashdata('message', "Registration successful");
            redirect('auth/register_success');
        } else {
            // set the flash data error message if there is one
            $this->data['message'] = validation_errors() ? validation_errors() : ($this->access->errors() ? $this->access->errors() : $this->session->flashdata('message'));
            $this->data['username'] = $this->form_validation->set_value('username');
            $this->data['email'] = $this->form_validation->set_value('email');
            $this->data['realname'] = $this->form_validation->set_value('realname');
            $this->data['password'] = $this->form_validation->set_value('password');
            $this->data['password_confirm'] = $this->form_validation->set_value('password_confirm');
            $this->load->view('auth/register', $this->data);
        }
    }

    public function register_success() {
        $this->load->view('auth/register_success', $this->data);
    }

    public function settings() {
        if (!$this->access->loggedIn()) {
            redirect('auth/login', 'refresh');
        }

        // validate form
        $this->form_validation->set_rules('new_password', 'New Password', 'min_length[' . $this->config->item('min_password_length', 'auth') . ']|max_length[' . $this->config->item('max_password_length', 'access') . ']|matches[new_password_confirm]');

        if ($this->form_validation->run() == true) {
            // change password if needed
            if ($this->input->post('new_password') != '') {
                $username = $this->session->userdata('username');
                $change = $this->access->changePassword($username, $this->input->post('old_password'), $this->input->post('new_password'));

                if ($change) {
                    $this->logout();
                }
            }

            // update user
            $updateData = array(
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'institution' => $this->input->post('institution'),
            	'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
            );
            $this->access->updateUser($this->session->userdata('user_id'), $updateData);

            echo "{success: true}";
        } else {
            $this->data['success'] = true;
            $this->data['data'] = $this->access->getCurrentUser();

            // output json data
            $this->output->set_content_type('application/json')
                    ->set_output(json_encode($this->data));
        }
    }

    //forgot password
    public function forgot_password() {
        $this->form_validation->set_rules('email', 'Email Address', 'required');
        if ($this->form_validation->run() == false) {
            //setup the input
            $this->data['email'] = array('name' => 'email',
				'id' => 'email',
            );
            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->load->view('auth/forgot_password', $this->data);
        } else {
            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->access->forgotten_password($this->input->post('email'));

            if ($forgotten) { //if there were no errors
                $this->session->set_flashdata('message', $this->access->messages());
                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->access->errors());
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code) {
        $reset = $this->access->forgotten_password_complete($code);

        if ($reset) {  //if the reset worked then send them to the login page
            $this->session->set_flashdata('message', $this->access->messages());
            redirect('auth/login');
        } else { //if the reset didnt work then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->access->errors());
            redirect('auth/forgot_password');
        }
    }

    public function test() {
        echo "{xtype: 'form', title: 'Bla'}";
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
