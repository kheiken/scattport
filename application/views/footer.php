	<div id="footer">
<?
	if($this->session->userdata('group') == 'admins'):
?>
		<span class="left"><strong>Administration - </strong>
			<a href="<?=site_url('admin/settings')?>">Globale Einstellungen</a> |
			<a href="<?=site_url('admin/servers')?>">Berechnungsserver verwalten</a> |
			<a href="<?=site_url('admin/users')?>">Benutzer verwalten</a> |
			<a href="<?=site_url('admin/programs')?>">Programme verwalten</a>
		</span>
<?
	endif;
?>
		<span class="right">© 2011 Karsten Heiken.</span>
	</div>

</div>


</body>
</html>
