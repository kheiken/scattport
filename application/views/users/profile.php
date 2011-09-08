<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=$user->firstname;?> <?=$user->lastname;?></h2>
	</div>

	<div class="box">
		<h3><?=_('Personal information');?></h3>
		<p>
			<strong><?=_('Name');?>:</strong> <?=$user->firstname;?> <?=$user->lastname;?><br />
			<strong><?=_('Institution');?>:</strong> <?=$user->institution;?><br />
			<strong><?=_('Phone number');?>:</strong> <?=$user->phone;?><br />
		</p>
	</div>

</div>

<?php $this->load->view('footer');?>
