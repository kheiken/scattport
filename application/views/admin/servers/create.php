<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('admin/servers', _('Servers'));?> &raquo; <?=_('Add new server');?></h2>
	</div>

	<div class="box">
		<form name="createServer" method="post" action="<?=site_url('admin/servers/create/')?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Name'), 'id');?> <span class="req">*</span>
					<div>
						<input type="text" name="id" id="id" class="medium text" value="<?=set_value('id');?>" />
						<?=form_error('id');?>
					</div>
					<label class="note">The preferred format is SP-OWNER-NUMBER, for example SP-JDOE-01</label>
				</li>
				<li>
					<?=form_label(_('Location'), 'location');?> <span class="req">*</span>
					<div>
						<input type="text" name="location" id="location" class="medium text" value="<?=set_value('location');?>" />
						<?=form_error('location');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Owner'), 'owner');?> <span class="req">*</span>
					<div>
						<?=form_dropdown('owner', $users, 'id="owner" class="drop"');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Description'), 'description');?> <span class="req">*</span>
					<div>
						<textarea name="description" id="description" rows="6" cols="60" class="textarea"><?=set_value('description');?></textarea>
						<?=form_error('description');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Secret'), 'secret');?> <span class="req">*</span>
					<div>
						<input type="text" readonly="readonly" name="secret" id="secret" class="medium text" value="<?=set_value('secret', $secret);?>" />
						<?=form_error('secret');?>
					</div>
					<label class="note"><?=_('This secret later needs to be entered in the client. Copy this secret to your clipboard!')?></label>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=createServer]').submit();" class="button save"><?=_('Save');?></a>
				<a href="<?=site_url('admin/servers');?>" class="button cancel"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>
