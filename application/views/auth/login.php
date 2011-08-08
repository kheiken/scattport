<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<title>ScattPort | Login</title>

		<?= link_tag('assets/css/login.css'); ?>
		<?= link_tag('assets/css/form.css'); ?>

		<?= script_tag('assets/js/minmax.js'); ?>
		<?= script_tag('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js'); ?>
		<?= script_tag('assets/js/scattport.js'); ?>

</head>
<body>

	<div id="wrapper">

		<div id="box">
			<h2>Scattport <span class="light">Login</span></h2>

			<p>
				<?php
				if (isset($error))
					foreach ($error as $e)
						echo "<div class=\"error\">" . $e . "</div>";
				if (isset($notice))
					foreach ($notice as $n)
						echo "<div class=\"notice\">" . $n . "</div>";
				if (isset($success))
					foreach ($success as $s)
						echo "<div class=\"success\">" . $s . "</div>";
				?>
			</p>

			<form action="<?= site_url('auth/login') ?>" method="post" name="loginform">
				<ul>
					<li>
						<label>Benutzername</label>
						<div><input type="text" name="username" class="text medium" /></div>
					</li>
					<li>
						<label>Passwort</label>
						<div><input type="password" name="password" class="text medium" /></div>
					</li>
					<li>
						<div>
							<input type="submit" class="button" name="login" value="Einloggen" />
						</div>
					</li>
				</ul>
			</form>

			<p><a href="#">Passwort vergessen?</a></p>
		</div>


</body>
</html>
