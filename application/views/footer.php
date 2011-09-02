	<div id="footer">
<?
	if ($this->access->isAdmin()):
?>
		<span class="left"><strong><?=_('Administration')?></strong> -
			<a href="<?=site_url('admin/settings')?>"><?=_('Global settings')?></a> |
			<a href="<?=site_url('admin/servers')?>"><?=_('Manage calculation servers')?></a> |
			<a href="<?=site_url('admin/users')?>"><?=_('Manage users')?></a> |
			<a href="<?=site_url('admin/programs')?>"><?=_('Manage programs')?></a>
		</span>
<?
	endif;
?>

		<span class="right">
			<?=anchor('about', _('About'));?> |
			<?=anchor('legal', _('License'));?>
		</span>
		<span class="right">
			<?=asset('image', 'iwt.gif', array('width' => 36));?>
			<?=asset('image', 'dfg.gif', array('width' => 36));?>
			<?=asset('image', 'uni.png', array('width' => 36));?>
		</span>
	</div>

</div>

</body>
</html>
