<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=lang('create_user');?></h2>
	</div>

	<div class="box">
		<form name="createUser" method="post" action="<?=site_url('users/create')?>">
			<h3>Required information</h3>
			<ul>
				<li>
					<?=form_label("Username", 'username');?>
					<div>
						<input type="text" name="username" id="username" class="short text" value="<?=set_value('username');?>" />
						<?=form_error('username')?>
					</div>
				</li>
				<li>
					<?=form_label("Password", 'password');?>
					<div>
						<input type="password" name="password" id="password" class="short text" />
						<?=form_error('password')?>
					</div>
				</li>
				<li>
					<?=form_label("Confirm password", 'password2');?>
					<div>
						<input type="password" name="password_confirm" id="password_confirm" class="short text" />
						<?=form_error('password_confirm')?>
					</div>
				</li>
				<li>
					<?=form_label("First name", 'firstname');?>
					<div>
						<input type="text" name="firstname" id="firstname" class="short text" value="<?=set_value('firstname');?>" />
						<?=form_error('firstname')?>
					</div>
				</li>
				<li>
					<?=form_label("Last name", 'lastname');?>
					<div>
						<input type="text" name="lastname" id="lastname" class="short text" value="<?=set_value('lastname');?>" />
						<?=form_error('lastname')?>
					</div>
				</li>
			</ul>
			<h3>Optional information</h3>
			<ul>
				<li>
					<?=form_label("Institution", 'institution');?>
					<div>
						<input type="text" name="institution" id="institution" class="medium text" value="<?=set_value('institution');?>" />
						<?=form_error('institution')?>
					</div>
				</li>
				<li>
					<?=form_label("Language", 'language');?>
					<div>
						<?=form_dropdown('language', array('English'), null, 'id="language" class="drop"');?>
						<?=form_error('language')?>
					</div>
				</li>
			</ul>
			<p>
				<a class="button save" href="javascript:void(0);" onclick="$('form[name=createUser]').submit();">Speichern</a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>