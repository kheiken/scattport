<!DOCTYPE html>
<html lang="<?=substr($this->config->item('language'), 0, 2);?>">
<head>

<meta charset="utf-8" />

<title>ScattPort | <?=_('Login');?></title>

<?=link_tag('assets/css/login.css');?>
<?=link_tag('assets/css/form.css');?>

<?=script_tag('assets/js/minmax.js');?>
<?=script_tag('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');?>
<?=script_tag('assets/js/scattport.js');?>
<script type="text/javascript">
	var SITE_URL = '<?=site_url()?>';
</script>
</head>
<body>

	<div id="wrapper">

		<div id="box">
			<h2>Scattport <span class="light"><?=_('Login');?></span></h2>

<?php
	if (isset($errors) && is_array($errors)) {
		foreach ($errors as $e) {
			echo "<p class=\"error\"><strong>" . _('Error') . ":</strong> " . $e . "</p>";
		}
	} else if (isset($messages) && is_array($messages)) {
		foreach ($messages as $m) {
			echo "<p class=\"success\"><strong>" . _('Success') . ":</strong> " . $m . "</p>";
		}
	}
?>
			<form name="password" action="<?= site_url('auth/forgot_password') ?>"
				method="post">
				<ul>
					<li>
						<label><?=form_label(_('Email address'), 'email');?></label>
						<div>
							<input type="text" name="email" id="email" class="text max" value="<?=set_value('email');?>" />
							<?=form_error('email');?>
						</div>
					</li>
					<li>
						<div>
							<input type="submit" class="button" name="forgot_password" value="<?=_('Submit');?>" />
						</div>
					</li>
				</ul>
			</form>

			<p><?=anchor('auth/login', _('Back to login page'));?></p>
		</div>
	</div>