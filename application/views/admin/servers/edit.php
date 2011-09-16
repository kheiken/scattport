<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('admin/servers', _('Servers'));?> &raquo; <?=sprintf(_('Edit server &quot;%s&quot;'), $server->id);?></h2>
	</div>

	<div class="box">
		<form name="editServer" method="post" action="<?=site_url('admin/servers/edit/' . $server->id)?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Location'), 'location');?> <span class="req">*</span>
					<div>
						<input type="text" name="location" id="location" class="medium text" value="<?=set_value('location', $server->location);?>" />
						<?=form_error('location');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Owner'), 'owner');?> <span class="req">*</span>
					<div>
						<?=form_dropdown('owner', $users, $server->owner, 'id="owner" class="drop"');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Description'), 'description');?> <span class="req">*</span>
					<div>
						<textarea name="description" id="description" rows="6" cols="60" class="textarea"><?=set_value('description', $server->description);?></textarea>
						<?=form_error('description');?>
					</div>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=editServer]').submit();" class="button save"><?=_('Save');?></a>
				<a href="<?=site_url('admin/servers');?>" class="button cancel"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>
