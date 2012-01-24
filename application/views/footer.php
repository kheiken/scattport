	<div id="footer">
		<span class="right">
			<?=sprintf(_('Page rendered in %s seconds.'), $this->benchmark->elapsed_time());?>
		</span>
		<span class="left">
			<?=anchor('about', _('About'));?> |
			<?=anchor('about/license', _('License'));?>
		</span>
	</div>

	<div id="copyright">
		<a href="http://www.dfg.de/en/index.jsp" target="_blank"><?=img(array('src' => 'assets/images/dfg.png', 'title' => 'Deutsche Forschungsgemeinschaft'))?></a>
		<a href="http://www.hs-emden-leer.de/en/startseite.html" target="_blank"><?=img(array('src' => 'assets/images/hsemden.png', 'title' => 'University of Applied Sciences Emden'))?></a>
		<a href="http://www.uni-bremen.de/en.html" target="_blank"><?=img(array('src' => 'assets/images/unibremen.png', 'title' => 'University of Bremen'))?></a>
	</div>

</div>

</body>
</html>
