<?php $this->load->view('header');?>

<div id="content">
	<form name="settings" action="<?=site_url('auth/settings');?>" method="post">
		<div class="title">
			<h2><?=_('Settings');?></h2>
		</div>
		<ul class="tabs">
			<li class="active"><a href="#personal"><?=_('Personal information');?></a></li>
			<li><a href="#settings"><?=_('Settings');?></a></li>
			<li><a href="#password"><?=_('Password');?></a></li>
		</ul>

		<div class="tab_container">
			<div id="personal" class="tab_content">
				<ul>
					<li>
						<?=form_label(_('First name'), 'firstname');?>
						<span class="req">*</span>
						<div>
							<input type="text" name="firstname" id="firstname" class="short text" value="<?=set_value('firstname', $firstname);?>" />
							<?=form_error('firstname');?>
						</div>
					</li>
					<li>
						<?=form_label(_('Last name'), 'lastname');?>
						<span class="req">*</span>
						<div>
							<input type="text" name="lastname" id="lastname" class="short text" value="<?=set_value('lastname', $lastname);?>" />
							<?=form_error('lastname');?>
						</div>
					</li>
					<li>
						<?=form_label(_('Email address'), 'email');?>
						<span class="req">*</span>
						<div>
							<input type="text" name="email" id="email" class="medium text" value="<?=set_value('email', $email);?>" />
							<?=form_error('email');?>
						</div>
					</li>
					<li>
						<?=form_label(_('Institution'), 'institution');?>
						<div>
							<input type="text" name="institution" id="institution" class="medium text" value="<?=set_value('institution', $institution);?>" />
							<?=form_error('institution');?>
						</div>
					</li>
					<li>
						<?=form_label(_('Phone number'), 'phone');?>
						<div>
							<input type="text" name="phone" id="phone" class="short text" value="<?=set_value('phone', $phone);?>" />
							<?=form_error('phone');?>
						</div>
					</li>
				</ul>
			</div>
			<div id="settings" class="tab_content">
				<ul>
					<li>
						<input type="checkbox" id="projects_sortrecently" name="projects_sortrecently" value="1" class="checkbox"/>
						<label for="projects_sortrecently"><?=_('Sort projects by date of the last access');?></label><br />
						<label class="note"><?=_('If the projects are sorted by the data of the last access, the rarely used projects &quot;slip&quot; to the end of the list.');?></label>
					</li>
				</ul>
			</div>
			<div id="password" class="tab_content">
				<ul>
					<li>
						<?=form_label(_('Current password'), 'old_password');?>
						<div>
							<input type="password" name="old_password" id="old_password" class="short text" value="<?=set_value('old_password');?>" />
							<?=form_error('old_password');?>
						</div>
					</li>
					<li>
						<?=form_label(_('New password'), 'new_password');?>
						<div>
							<input type="password" name="new_password" id="new_password" class="short text" />
							<?=form_error('new_password');?>
						</div>
					</li>
					<li>
						<?=form_label(_('Confirm new password'), 'new_password_confirm');?>
						<div>
							<input type="password" name="new_password_confirm" id="new_password_confirm" class="short text" />
							<?=form_error('new_password_confirm');?>
						</div>
					</li>
				</ul>
			</div>
			<div class="tab_buttons">
				<p>
					<a class="button save" href="javascript:void(0);" onclick="$('form[name=settings]').submit();"><?=_('Save settings');?></a>
				</p>
			</div>
		</div>
	</form>
</div>

<?php $this->load->view('footer'); ?>
