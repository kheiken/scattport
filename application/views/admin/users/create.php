<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('admin/users', _('Users'));?> &raquo; <?=_('Create user');?></h2>
	</div>

	<div class="box">
		<form name="createUser" method="post" action="<?=site_url('admin/users/create')?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Username'), 'username');?> <span class="req">*</span>
					<div>
						<input type="text" name="username" id="username" class="short text" value="<?=set_value('username');?>" />
						<?=form_error('username');?>
					</div>
					<label class="note"><?=_('Must be between 4 and 20 characters long');?></label>
				</li>
				<li>
					<?=form_label(_('Email address'), 'email');?> <span class="req">*</span>
					<div>
						<input type="text" name="email" id="email" class="medium text" value="<?=set_value('email');?>" />
						<?=form_error('email');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Password'), 'password');?> <span class="req">*</span>
					<div>
						<input type="password" name="password" id="password" class="short text" />
						<?=form_error('password');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Confirm password'), 'password_confirm');?> <span class="req">*</span>
					<div>
						<input type="password" name="password_confirm" id="password_confirm" class="short text" />
						<?=form_error('password_confirm');?>
					</div>
				</li>
				<li>
					<?=form_label(_('First name'), 'firstname');?> <span class="req">*</span>
					<div>
						<input type="text" name="firstname" id="firstname" class="short text" value="<?=set_value('firstname');?>" />
						<?=form_error('firstname');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Last name'), 'lastname');?> <span class="req">*</span>
					<div>
						<input type="text" name="lastname" id="lastname" class="short text" value="<?=set_value('lastname');?>" />
						<?=form_error('lastname');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Group'), 'group_id');?> <span class="req">*</span>
					<div>
						<?=form_dropdown('group_id', $groups, $default['id'], 'id="group_id" class="drop"');?>
					</div>
				</li>
			</ul>
			<h3><?=_('Optional information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Institution'), 'institution');?>
					<div>
						<input type="text" name="institution" id="institution" class="medium text" value="<?=set_value('institution');?>" />
						<?=form_error('institution');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Phone number'), 'phone');?>
					<div>
						<input type="text" name="phone" id="phone" class="short text" value="<?=set_value('phone');?>" />
						<?=form_error('phone');?>
					</div>
					<label class="note"><?=('Example');?>: +49 123 456789</label>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=createUser]').submit();" class="button save"><?=_('Save');?></a>
				<a href="<?=site_url('admin/users');?>" class="button cancel"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>
