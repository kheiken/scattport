<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Global settings')?></h2>
	</div>

	<div class="box">
		<form name="settings" method="post" action="<?=site_url('admin/settings')?>">
			<ul>
				<li>
					<?=form_label(_('Offline mode?'), 'offline');?>
					<div>
						<?=form_yesno('offline', set_value('offline', $this->setting->get('offline')), 'id="offline" class="drop"');?>
						<?=form_error('offline');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Offline message'), 'offline_message');?><br />
					<label class="note"><?=_('This message is shown if the page is in offline mode.');?></label>
					<div>
						<textarea name="offline_message" id="offline_message" rows="6" cols="60" class="textarea"><?=set_value('offline_message', $this->setting->get('offline_message'));?></textarea>
						<?=form_error('offline_message');?>
					</div>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=settings]').submit();" class="button save"><?=_('Save settings');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>
