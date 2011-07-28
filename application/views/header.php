<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>ScattPort | Dashboard</title>

<?=link_tag('assets/css/style.css');?>
<?=link_tag('assets/css/table.css');?>
<?=link_tag('assets/css/form.css');?>

<?=script_tag('assets/js/minmax.js');?>

</head>
<body>

<div id="header">

	<h1><a href="#"><img src="<?=site_url('assets/images/logo.gif')?>" /></a></h1>
	<div class="menu">Hallo <a href="#">Admin</a>! | <a href="#">Hilfe</a> | <a href="#">Einstellungen</a> | <a href="#">Logout</a></div>

</div>

<div id="wrapper">

<? if(isset($error))
	foreach($error as $e) echo "<div id=\"error\">".$e."</div>"; ?>
<? if(isset($notice))
	foreach($notice as $n) echo "<div id=\"notice\">".$n."</div>"; ?>
<? if(isset($success))
	foreach($success as $s) echo "<div id=\"success\">".$s."</div>"; ?>
	
	<div id="sidebar">

		<div class="title">
			<h2>Projekte</h2>
		</div>

		<div class="navigation">
			<ul>
				<li><a href="#">Eigene Projekte</a>
					<ul>
						<li><a href="#">Blutkörperchen</a></li>
						<li><a href="#">Gerstenkorn</a></li>
						<li><a href="#">Bleistiftspitze</a></li>
					</ul>
				</li>
				<li><a href="#">Freigegebene Projekte</a>
					<ul>
						<li><a href="#">Prisma</a></li>
					</ul>
				</li>
				<li><a href="#">Öffentliche Projekte</a>
					<ul>
						<li><a href="#">Beispielprojekt</a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="title">
			<h2>Suche</h2>
		</div>

		<div class="box">
			<form id="search-form" method="get" action="#">
				<input type="text" name="search" id="search-input" class="search-input">
				<input type="image" src="<?=site_url('assets/images/button-search.gif')?>" id="search-submit" class="search-submit">
			</form>
		</div>

		<div class="title">
			<h2>Ereignisse</h2>
		</div>

		<div class="box">
			<ul id="blog">
				<li><h4><a href="#" title="Berechnung fertig">Berechnung fertig</a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p>Berechnung für &quot;Gerstenkorn&quot; erfolgreich beendet.</i></p></li>
				<li><h4><a href="#" title="Berechnung fertig">Berechnung fertig</a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p>Berechnung für &quot;Gerstenkorn&quot; erfolgreich beendet.</i></p></li>
				<li><h4><a href="#" title="Berechnung fertig">Berechnung fertig</a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p>Berechnung für &quot;Gerstenkorn&quot; erfolgreich beendet.</i></p></li>
			</ul>
		</div>

	</div>
