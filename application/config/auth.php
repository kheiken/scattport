<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Default group, use name
 */
$config['default_group'] = 'users';

/**
 * Default administrators group, use name
 */
$config['admin_group'] = 'admins';

/**
 * Minimum required length of passwords.
 */
$config['min_password_length'] = 8;

/**
 * Maximum allowed length of passwords.
 **/
$config['max_password_length'] = 40;

/**
 * Allow users to be remembered and enable auto-login.
 */
$config['remember_users'] = true;

/**
 * How long to remember the user.
 */
$config['user_expire'] = 86500;

/**
 * Extend the users cookies everytime they auto-login.
 */
$config['user_extend_on_login'] = false;

/**
 * Type of emails to send (HTML or text).
 */
$config['email_type'] = 'html';

/**
 * Folder where e-mail templates are stored.
 */
$config['email_templates'] = 'auth/email/';

/**
 * Forgot password e-mail template.
 */
$config['email_forgot_password'] = 'forgot_password.php';

/**
 * Forgot password complete e-mail template.
 */
$config['email_forgot_password_complete'] = 'new_password.php';

/**
 * Salt length.
 */
$config['salt_length'] = 40;

/**
 * Should the salt be stored in the database?
 *
 * Warning: This will change your password encryption algorithm.
 */
$config['store_salt'] = true;

/* End of file auth.php */
/* Location: ./application/config/auth.php */
