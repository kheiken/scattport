<!DOCTYPE html5>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>List of projects</title>
		<link rel="stylesheet" href="<?=base_url();?>/assets/css/inuit.css" />
    </head>
    <body>
		<table>
			<tr>
				<td><b>id</b></td>
				<td><b>name</b></td>
				<td><b>owner</b></td>
			</tr>
			<? foreach($projects as $project): ?>
			<tr>
				<td><?=anchor('web/projects/detail/'.$project['id'], $project['id'])?></td>
				<td><?=$project['name']?></td>
				<td><?=$project['owner']?></td>
			</tr>
			<? endforeach; ?>
		</table>
    </body>
</html>
