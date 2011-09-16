<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('admin/users', _('Users'));?> &raquo; <?=sprintf(_('Edit user &quot;%s&quot;'), $user['username']);?></h2>
	</div>

	<div class="box">
		<form name="editUser" method="post" action="<?=site_url('admin/users/edit/' . $user['id'])?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Email address'), 'email');?> <span class="req">*</span>
					<div>
						<input type="text" name="email" id="email" class="medium text" value="<?=set_value('email', $user['email']);?>" />
						<?=form_error('email')?>
					</div>
				</li>
				<li>
					<?=form_label(_('First name'), 'firstname');?> <span class="req">*</span>
					<div>
						<input type="text" name="firstname" id="firstname" class="short text" value="<?=set_value('firstname', $user['firstname']);?>" />
						<?=form_error('firstname')?>
					</div>
				</li>
				<li>
					<?=form_label(_('Last name'), 'lastname');?> <span class="req">*</span>
					<div>
						<input type="text" name="lastname" id="lastname" class="short text" value="<?=set_value('lastname', $user['lastname']);?>" />
						<?=form_error('lastname')?>
					</div>
				</li>
				<li>
					<?=form_label(_('Group'), 'group_id');?> <span class="req">*</span>
					<div>
						<?=form_dropdown('group_id', $groups, $user['group_id'], 'id="group_id" class="drop"');?>
					</div>
				</li>
			</ul>
			<h3><?=_('Optional information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Institution'), 'institution');?>
					<div>
						<input type="text" name="institution" id="institution" class="medium text" value="<?=set_value('institution', $user['institution']);?>" />
						<?=form_error('institution')?>
					</div>
				</li>
				<li>
					<?=form_label(_('Phone number'), 'phone');?>
					<div>
						<input type="text" name="phone" id="phone" class="short text" value="<?=set_value('phone', $user['phone']);?>" />
						<?=form_error('phone')?>
					</div>
					<label class="note"><?=_('Example');?>: +49 123 456789</label>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=editUser]').submit();" class="button save"><?=_('Save');?></a>
				<a href="<?=site_url('admin/users');?>" class="button cancel"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>
