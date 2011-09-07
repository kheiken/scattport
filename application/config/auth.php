<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Default group (use name).
 *
 * @var string
 */
$config['default_group'] = 'users';

/**
 * Default administrators group (use name).
 *
 * @var string
 */
$config['admin_group'] = 'admins';

/**
 * Minimum required length of passwords.
 *
 * @var integer
 */
$config['min_password_length'] = 8;

/**
 * Maximum allowed length of passwords.
 *
 * @var integer
 */
$config['max_password_length'] = 40;

/**
 * Allow users to be remembered and enable auto-login.
 *
 * @var boolean
 */
$config['remember_users'] = true;

/**
 * How long to remember the user.
 *
 * @var integer
 */
$config['user_expire'] = 86400;

/**
 * Extend the users cookies everytime they auto-login.
 *
 * @var boolean
 */
$config['user_extend_on_login'] = false;

/**
 * Type of emails to send (HTML or text).
 *
 * @var string
 */
$config['email_type'] = 'html';

/**
 * Folder where email templates are stored.
 *
 * @var string
 */
$config['email_templates'] = 'auth/email/';

/**
 * Salt length.
 *
 * @var integer
 */
$config['salt_length'] = 40;

/**
 * Should the salt be stored in the database?
 *
 * Warning: This will change your password encryption algorithm.
 *
 * @var boolean
 */
$config['store_salt'] = true;

/* End of file auth.php */
/* Location: ./application/config/auth.php */
