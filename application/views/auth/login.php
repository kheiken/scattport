<!DOCTYPE html>
<html lang="<?=substr($this->config->item('language'), 0, 2);?>">
<head>

<meta charset="utf-8" />

<title>ScattPort | <?=_('Login');?></title>

<?= link_tag('assets/css/login.css'); ?>
<?= link_tag('assets/css/form.css'); ?>

<?= script_tag('assets/js/minmax.js'); ?>
<?= script_tag('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js'); ?>
<?= script_tag('assets/js/scattport.js'); ?>
<script type="text/javascript">
	var SITE_URL = '<?=site_url()?>';
</script>
</head>
<body>

<div id="wrapper">

	<div id="box">
		<h2>Scattport <span class="light"><?=_('Login');?></span></h2>

<?php
	if (isset($error))
		foreach ($error as $e)
			echo "<p class=\"error\">" . $e . "</p>";
	if (isset($notice))
		foreach ($notice as $n)
			echo "<p class=\"notice\">" . $n . "</p>";
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
