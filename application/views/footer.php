	<div id="footer">
		<span class="right">
			<?=sprintf(_('Page rendered in %s seconds.'), $this->benchmark->elapsed_time());?>
		</span>
		<span class="left">
			<?=anchor('about', _('About'));?> |
			<?=anchor('license', _('License'));?>
		</span>
	</div>

	<div id="copyright">
		<?=image_asset('iwt.png');?>
		<?=image_asset('dfg.png');?>
		<?=image_asset('uni.png');?>
	</div>

</div>

</body>
</html>
