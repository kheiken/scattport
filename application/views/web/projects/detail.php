<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Project <?=$name?></title>
		<link rel="stylesheet" href="/ScattPort/assets/css/inuit.css" />
    </head>
    <body>
		<div class="grids">
			<div class="grid grid-8">
				<table>
					<tr><th colspan="2">Projektdetails</th></tr>
					<tr>
						<td>ID</td>
						<td><?=$id?></td>
					</tr>
					<tr>
						<td>Name</td>
						<td><?=$name?></td>
					</tr>
					<tr>
						<td>Beschreibung</td>
						<td><?=$description?></td>
					</tr>
				</table>
			</div>
			<div class="grid grid-8">
				<table>
					<tr><th>Konfigurationen</th></tr>
					<? foreach($configs as $config): ?>
					<tr><td><?=anchor('web/configurations/detail/'.$config['id'], $config['name'])?></td></tr>
				<? endforeach; ?>
				</table>
			</div>
		</div>
    </body>
</html>
