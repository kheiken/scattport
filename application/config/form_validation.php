<?php defined('BASEPATH') || exit("No direct script access allowed");

$config = array(
	'users' => array(
		array(
				'field' => 'username',
				'label' => _('Username'),
				'rules' => 'trim|required|min_length[4]|max_length[20]|unique[users.username]',
		),
		array(
				'field' => 'password',
				'label' => _('Password'),
				'rules' => 'required|min_length[6]|matches[password_confirm]',
		),
		array(
				'field' => 'password_confirm',
				'label' => _('Confirm password'),
		),
		array(
				'field' => 'firstname',
				'label' => _('First name'),
				'rules' => 'trim|required|max_length[50]',
		),
		array(
				'field' => 'lastname',
				'label' => _('Last name'),
				'rules' => 'trim|required|max_length[50]',
		),
		array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'trim|required|valid_email',
		),
		array(
				'field' => 'institution',
				'label' => _('Institution'),
				'rules' => 'trim|max_length[100]',
		),
		array(
				'field' => 'phone',
				'label' => _('Phone number'),
				'rules' => 'trim|regex_match[/^\+\d{2,4}\s\d{2,4}\s\d{3,10}+$/i]',
		)
	)
);