<!DOCTYPE html5>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Create Project</title>
		<link rel="stylesheet" href="/ScattPort/assets/css/inuit.css" />
    </head>
    <body>
		<div class="grids">
			<div class="grid grid-8">
				<?=form_open('web/projects/create')?>
					<table>
						<tr><th colspan="2">Create a new project</th></tr>
						<tr>
							<td>
								Name:
							</td>
							<td>
								<input type="text" name="f_name" value="<?=$name?>"/><?=form_error('f_name')?>
							</td>
						</tr>
						<tr>
							<td>
								Beschreibung:
							</td>
							<td>
								<input type="text" name="f_description" value="<?=$description?>"/><?=form_error('description')?>
							</td>
						</tr>
						<tr>
							<td align="right" colspan="2">
								<input type="submit" />
							</td>
						</tr>
					</table>
				<?=form_close()?>
			</div>
		</div>
    </body>
</html>
