<!DOCTYPE html>
<html lang="<?=substr($this->config->item('language'), 0, 2);?>">
<head>

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7,9; chrome=1" />

<title>ScattPort | <?=_('Login');?></title>

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