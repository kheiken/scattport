<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Rules for login page.
 *
 * @var array
 */
$config['auth/login'] = array(
		array(
				'field' => 'username',
				'label' => _('Username'),
				'rules' => 'required|trim',
		),
		array(
				'field' => 'password',
				'label' => _('Password'),
				'rules' => 'required|trim',
		),
		array(
				'field' => 'remember',
				'label' => _('Remember me on this computer'),
				'rules' => 'integer',
		),
);

/**
 * Rules for forgotten password page.
 *
 * @var array
 */
$config['auth/forgot_password'] = array(
		array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'required|valid_email|trim',
		),
);

/**
 * Rules for the settings page.
 *
 * @var array
 */
$config['auth/settings'] = array(
		array(
				'field' => 'firstname',
				'label' => _('First name'),
				'rules' => 'required|max_length[50]|trim',
		),
		array(
				'field' => 'lastname',
				'label' => _('Last name'),
				'rules' => 'required|max_length[50]|trim',
		),
		array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'required|valid_email|trim',
		),
		array(
				'field' => 'institution',
				'label' => _('Institution'),
				'rules' => 'max_length[100]|trim',
		),
		array(
				'field' => 'phone',
				'label' => _('Phone number'),
				'rules' => 'regex_match[/^\+\d{2,4}\s\d{2,4}\s\d{3,10}+$/i]|trim',
		),
		array(
				'field' => 'new_password',
				'label' => _('New password'),
				'rules' => 'min_length[6]|matches[new_password_confirm]',
		),
		array(
				'field' => 'job_check_interval',
				'label' => _('Job checking interval'),
				'rules' => 'greater_than[9]',
		),
		array(
				'field' => 'projects_sort_recently',
				'label' => _('Sort projects by last access'),
				'rules' => 'integer',
		),
);

/**
 * Rules for global settingsa.
 *
 * @var array
 */
$config['settings/index'] = array(
		array(
				'field' => 'offline',
				'label' => _('Offline mode'),
				'rules' => 'required|integer',
		),
		array(
				'field' => 'offline_message',
				'label' => _('Offline message'),
				'rules' => 'trim',
		),
);

/**
 * Rules for creating users.
 *
 * @var array
 */
$config['users/create'] = array(
		array(
				'field' => 'username',
				'label' => _('Username'),
				'rules' => 'required|min_length[4]|max_length[20]|unique[users.username]|trim',
		),
		array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'required|valid_email|trim',
		),
		array(
				'field' => 'password',
				'label' => _('Password'),
				'rules' => 'required|min_length[6]|matches[password_confirm]',
		),
		array(
				'field' => 'password_confirm',
				'label' => _('Confirm password'),
				'rules' => 'required',
		),
		array(
				'field' => 'firstname',
				'label' => _('First name'),
				'rules' => 'required|max_length[50]|trim',
		),
		array(
				'field' => 'lastname',
				'label' => _('Last name'),
				'rules' => 'required|max_length[50]|trim',
		),
		array(
				'field' => 'institution',
				'label' => _('Institution'),
				'rules' => 'max_length[100]|trim',
		),
		array(
				'field' => 'phone',
				'label' => _('Phone number'),
				'rules' => 'regex_match[/^\+\d{2,4}\s\d{2,4}\s\d{3,10}+$/i]|trim',
		),
);

/**
 * Rules for editing users.
 *
 * @var array
 */
$config['users/edit'] = array(
		array(
				'field' => 'firstname',
				'label' => _('First name'),
				'rules' => 'required|max_length[50]|trim',
		),
		array(
				'field' => 'lastname',
				'label' => _('Last name'),
				'rules' => 'required|max_length[50]|trim',
		),
		array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'required|valid_email|trim',
		),
		array(
				'field' => 'institution',
				'label' => _('Institution'),
				'rules' => 'max_length[100]|trim',
		),
		array(
				'field' => 'phone',
				'label' => _('Phone number'),
				'rules' => 'regex_match[/^\+\d{2,4}\s\d{2,4}\s\d{3,10}+$/i]|trim',
		),
);

/**
 * Rules for editing programs.
 *
 * @var array
 */
$config['programs/edit'] = array(
		array(
				'field' => 'name',
				'label' => _('Name of the program'),
				'rules' => 'required|max_length[100]|trim',
		),
		array(
				'field' => 'config_template',
				'label' => _('Config template'),
				'rules' => 'required',
		),
);

/**
 * Rules for creating parameters.
 *
 * @var array
 */
$config['parameters/create'] = array(
		array(
				'field' => 'name',
				'label' => _('Name'),
				'rules' => 'required|max_length[255]|trim',
		),
		array(
				'field' => 'readable',
				'label' => _('Human-readable name'),
				'rules' => 'required|max_length[100]|trim',
		),
		array(
				'field' => 'unit',
				'label' => _('Name'),
				'rules' => 'max_length[20]|trim',
		),
		array(
				'field' => 'default_value',
				'label' => _('Default value'),
				'rules' => 'max_length[255]|trim',
		),
		array(
				'field' => 'type',
				'label' => _('Type'),
				'rules' => 'required|max_length[20]|trim',
		),
);

/**
 * Rules for creating projects.
 *
 * @var array
 */
$config['projects/create'] = array(
		array(
				'field' => 'name',
				'label' => _('Project name'),
				'rules' => 'required|min_length[3]|max_length[100]|trim',
		),
		array(
				'field' => 'description',
				'label' => _('Description'),
				'rules' => 'required|trim',
		),
		array(
				'field' => 'defaultmodel',
				'label' => _('3D model'),
				'rules' => 'file_allowed_type[obj]',
		),
		array(
				'field' => 'defaultconfig',
				'label' => _('Default configuration'),
				'rules' => 'file_allowed_type[calc]',
		),
		array(
				'field' => 'public',
				'label' => _('Make the project public'),
				'rules' => 'integer',
		),
);

/**
 * Rules for creating trials.
 *
 * @var array
 */
$config['experiments/create'] = array(
		array(
				'field' => 'name',
				'label' => _('Name'),
				'rules' => 'required|min_length[3]|max_length[60]|trim',
		),
		array(
				'field' => 'description',
				'label' => _('Description'),
				'rules' => 'required|trim',
		),
		array(
				'field' => 'program_id',
				'label' => _('Program'),
				'rules' => 'required|alpha_numeric|trim',
		),
		array(
				'field' => '3dmodel',
				'label' => _('3D model'),
				'rules' => 'file_allowed_type[obj]',
		),
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */
