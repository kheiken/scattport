<?php $this->load->view('header'); ?>

<div id="content">
	<form action="#" method="post">
		<div class="title">
			<h2><?=lang('settings');?></h2>
		</div>
		<ul class="tabs">
			<li class="active"><a href="#personal"><?=lang('tab_personal');?></a></li>
			<li><a href="#settings"><?=lang('tab_settings');?></a></li>
		</ul>

		<div class="tab_container">
			<div id="personal" class="tab_content">
				<ul>
<?
	foreach($profile_fields as $field):
?>
					<li>
						<h4><?=$field[1]?></h4>
						<div><input type="<?=$field[2]?>" name="<?=$field[0]?>" class="short text" /></div>
					</li>
<?
	endforeach;
?>
				</ul>
			</div>
			<div id="settings" class="tab_content">
				<ul>
					<li>
						<input type="checkbox" id="projects_sortrecently" name="projects_sortrecently" value="1" class="checkbox"/>
						<label for="projects_sortrecently"><?=lang('projects_sortrecently');?></label><br />
						<label class="note"><?=lang('projects_sortrecently_note');?></label>
					</li>
				</ul>
			</div>
		</div>
	</form>
</div>

<?php $this->load->view('footer'); ?>
