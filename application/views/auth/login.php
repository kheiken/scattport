<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>ScattPort | Login</title>

<?=link_tag('assets/css/style.css');?>
<?=link_tag('assets/css/table.css');?>
<?=link_tag('assets/css/form.css');?>

<?=script_tag('assets/js/minmax.js');?>
<?=script_tag('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');?>
<?=script_tag('assets/js/scattport.js');?>

</head>
<body>

<div id="header">

	<h1><a href="<?=base_url()?>"><img src="<?=site_url('assets/images/logo.gif')?>" /></a></h1>

</div>

<div id="wrapper">

<?
	if(isset($error))
		foreach($error as $e) echo "<div class=\"error\">".$e."</div>";
	if(isset($notice))
		foreach($notice as $n) echo "<div class=\"notice\">".$n."</div>";
	if(isset($success))
	foreach($success as $s) echo "<div class=\"success\">".$s."</div>";
?>
	<div id="content">

	<div class="title">
		<h2>Login</h2>
	</div>

	<div class="box">
		<form action="<?=site_url('auth/login')?>" method="post" name="loginform">
			<ul>
				<li>
					<h4>Benutzername</h4>
					<input type="text" name="username" />
				</li>
				<li>
					<h4>Passwort</h4>
					<input type="password" name="password" />
				</li>
				<li>
					<a href="#" onclick="document.forms.loginform.submit()" class="button big">Login</a>
				</li>
			</ul>
		</form>
	</div>
	<div id="footer">
		<span class="left"><a href="#">Dashboard</a> | <a href="#">Link</a> | <a href="#">Link</a> | <a href="#">Link</a></span>
		<span class="right">© 2011 Karsten Heiken.</span>
	</div>

</div>


</body>
</html>
