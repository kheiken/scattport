<!DOCTYPE html>
<html lang="<?=substr($this->config->item('language'), 0, 2);?>">
<head>

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7,9; chrome=1" />

<title>ScattPort | <?=_('Login');?></title>

<link rel="icon" href="<?=base_url('assets/images/favicon.png');?>" type="image/png">

<?=css_asset('login.css');?>
<?=css_asset('form.css');?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
if (typeof jQuery == 'undefined') document.write(unescape("%3Cscript src='<?=base_url('/assets/js/jquery-1.6.2.min.js');?>' type='text/javascript'%3E%3C/script%3E"));
</script>
<?=js_asset('minmax.js');?>
<?=js_asset('scattport.js');?>

<script type="text/javascript">
	var SITE_URL = '<?=site_url();?>';
	var BASE_URL = '<?=base_url();?>';
</script>

</head>

<body>

<div id="wrapper">

	<div id="box">
		<h2>Scattport <span class="light"><?=_('Login');?></span></h2>

<?php
	if (isset($errors)) {
		foreach ($errors as $e) {
			echo "<p class=\"error\"><strong>" . _('Error') . ":</strong> " . $e . "</p>";
		}
	} else if (isset($messages) && is_array($messages)) {
		foreach ($messages as $m) {
			echo "<p class=\"success\"><strong>" . _('Success') . ":</strong> " . $m . "</p>";
		}
	}
?>
		<form action="<?= site_url('auth/login') ?>" method="post" name="loginform">
			<ul>
				<li>
					<?=form_label(_('Username'), 'username');?>
					<div>
						<input type="text" name="username" id="username" class="text max" value="<?=set_value('username');?>" />
						<?=form_error('username');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Password'), 'password');?>
					<div>
						<input type="password" name="password" id="password" class="text max" />
						<?=form_error('password');?>
					</div>
				</li>
				<li>
					<div>
						<input type="checkbox" name="remember" id="remember" class="radio" value="1"<?=set_checkbox('remember', 1);?> />
						<label for="remember" class="choice"><?=_('Remember me on this computer')?></label>
					</div>
				</li>
				<li>
					<div>
						<input type="submit" class="button" name="login" value="<?=_('Log in');?>" />
					</div>
				</li>
			</ul>
		</form>

		<p><?=anchor('auth/forgot_password', _('Forgotten password?'));?></p>
	</div>
</div>

</body>
</html>
