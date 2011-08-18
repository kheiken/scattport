	<div id="footer">
<?
	if ($this->access->isAdmin()):
?>
		<span class="left"><strong><?=_('Administration')?> - </strong>
			<a href="<?=site_url('admin/settings')?>"><?=_('Global settings')?></a> |
			<a href="<?=site_url('admin/servers')?>"><?=_('Manage calculation servers')?></a> |
			<a href="<?=site_url('admin/users')?>"><?=_('Manage users')?></a> |
			<a href="<?=site_url('admin/programs')?>"><?=_('Manage programs')?></a>
		</span>
<?
	endif;
?>
		<span class="right">© 2011 Karsten Heiken.</span>
	</div>

</div>


</body>
</html>
