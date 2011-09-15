<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Search');?></h2>
	</div>

	<div class="box">
		<form name="search" method="get" action"">
			<h3><?=_('New search')?></h3>
			<ul>
				<li>
					<?=form_label(_('Keyword'), 'query');?>
					<div>
						<input type="text" name="query" id="query" class="medium text" />
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="$('form[name=search]').submit();" class="button search"><?=_('Search');?></a>
				</li>
			</ul>
		</form>
	</div>

</div>
<?php $this->load->view('footer');?>
