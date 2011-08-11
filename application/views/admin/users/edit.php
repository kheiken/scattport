<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?php printf(_("Edit user '%s'"), $user['username']);?></h2>
	</div>

	<div class="box">
		<form name="createUser" method="post" action="<?=site_url('users/edit/' . $user['id'])?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Email address'), 'email');?>
					<div>
						<input type="text" name="email" id="email" class="medium text" value="<?=set_value('email', $user['email']);?>" />
						<?=form_error('email')?>
					</div>
				</li>
				<li>
					<?=form_label(_('First name'), 'firstname');?>
					<div>
						<input type="text" name="firstname" id="firstname" class="short text" value="<?=set_value('firstname', $user['firstname']);?>" />
						<?=form_error('firstname')?>
					</div>
				</li>
				<li>
					<?=form_label(_('Last name'), 'lastname');?>
					<div>
						<input type="text" name="lastname" id="lastname" class="short text" value="<?=set_value('lastname', $user['lastname']);?>" />
						<?=form_error('lastname')?>
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
				<li>
					<?=form_label(_('Language'), 'language');?>
					<div>
						<?=form_dropdown('language', array('English'), null, 'id="language" class="drop"');?>
						<?=form_error('language')?>
					</div>
				</li>
			</ul>
			<p>
				<a class="button save" href="javascript:void(0);" onclick="$('form[name=createUser]').submit();"><?=_('Save');?></a>
				<a class="button cancel" href="<?=site_url('users');?>"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>