<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Configuration <?=$name?></title>
		<link rel="stylesheet" href="<?=base_url()?>/assets/css/inuit.css" />
    </head>
    <body>
		<div class="grids">
			<div class="grid grid-10">
				<table>
					<tr>
						<th colspan="2">
							Konfigurationsdetails
						</th>
					</tr>
					<tr>
						<td>ID</td><td><?=$id?></td>
					</tr>
					<tr>
						<td>Name</td><td><?=$name?></td>
					</tr>
				</table>
			</div>
			<div class="grid grid-6">
				<table>
					<tr>
						<th>Beschreibung</th>
					</tr>
					<tr>
						<td><?=nl2br($description)?></td>
					</tr>
				</table>
			</div>
		</div>
    </body>
</html>
