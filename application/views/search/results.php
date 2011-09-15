<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Search results');?></h2>
	</div>

	<div class="box">
		<h3>Projects</h3>
<?php
	foreach($projects as $project):
?>
			<p>
				<h4 style="margin-bottom: 5px;"><?=anchor('projects/detail/' . $project['id'], $project['name']);?></h4>
				<?=character_limiter($project['description'], 255);?><br />
			</p>
<?php
	endforeach;
?>
	</div>

	<div class="box">
		<h3>Experiments</h3>
<?php
	foreach($experiments as $experiment):
?>
			<p>
				<h4 style="margin-bottom: 5px;"><?=anchor('experiments/detail/' . $experiment['id'], $experiment['name']);?></h4>
				<?=character_limiter($experiment['description'], 255);?><br />
			</p>
<?php
	endforeach;
?>
	</div>

</div>

<?php $this->load->view('footer');?>
