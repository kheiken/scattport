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
     * Logs the user in.
     */
    public function login() {
        if ($this->access->loggedIn()) {
            redirect('welcome');
        }

        // validate form input
        $this->form_validation->set_rules('username', "Benutzername", 'required');
        $this->form_validation->set_rules('password', "Passwort", 'required');

        if ($this->form_validation->run() == true) { //check to see if the user is logging in
            // check for "remember me"
            $remember = (boolean) $this->input->post('remember');

            if ($this->access->login($this->input->post('username'), $this->input->post('password'), $remember)) { //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->access->messages());
                redirect('', 'refresh');
            } else { //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('message', $this->access->errors());
                //redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
                echo "{success: false}";
            }
        } else {  //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->data['message'] = validation_errors() ? validation_errors() : $this->session->flashdata('message');
            $this->data['username'] = $this->form_validation->set_value('username');

            $this->load->view('auth/login', $this->data);
        }
    }

	/**
	 * Logs the user in - or not ;-)
	 */
    public function do_login() {
        $this->form_validation->set_rules('username', "Benutzername", 'required');
        $this->form_validation->set_rules('password', "Passwort", 'required');

        if ($this->form_validation->run() == true) {
            $remember = (boolean) $this->input->post('remember');

            if ($this->access->login($this->input->post('username'), $this->input->post('password'), $remember)) {
                $this->session->set_flashdata('message', $this->access->messages());
                $return['success'] = true;
            } else { // if the login was un-successful
                $this->session->set_flashdata('message', $this->access->errors());
                $return['success'] = false;
                $return['message'] = "Benutzername oder PW falsch";
            }
        }

        $this->output->set_content_type('application/json')
                ->set_output(json_encode($return));
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

    //change password
    public function change_password() {
        $this->form_validation->set_rules('old', 'Old password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'access') . ']|max_length[' . $this->config->item('max_password_length', 'access') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

        if (!$this->access->loggedIn()) {
            redirect('auth/login', 'refresh');
        }
        $user = $this->access->get_user($this->session->userdata('user_id'));

        if ($this->form_validation->run() == false) { //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['old_password'] = array('name' => 'old',
				'id' => 'old',
				'type' => 'password'
            );
            $this->data['new_password'] = array('name' => 'new',
				'id' => 'new',
				'type' => 'password'
            );
            $this->data['new_password_confirm'] = array('name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password'
            );
            $this->data['user_id'] = array('name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id
            );

            //render
            $this->load->view('auth/change_password', $this->data);
        } else {
            $username = $this->session->userdata('username');

            $change = $this->access->change_password($username, $this->input->post('old'), $this->input->post('new'));

            if ($change) { //if the password was successfully changed
                $this->session->set_flashdata('message', $this->access->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->access->errors());
                redirect('auth/change_password', 'refresh');
            }
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

    private function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    private function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false &&
        $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return true;
        } else {
            return false;
        }
    }

	/**
	 * Logs the user out.
	 */
	public function do_logout() {
	    echo "{success: true}";
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
