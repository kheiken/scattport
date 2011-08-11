<?php
	if (is_array($messages)):
		foreach ($messages as $type => $msgs):
			if (count($msgs > 0)):
				foreach ($msgs as $message):
?>
<div class="<?=$type;?>">
	<?=$message;?>
	<a href="javascript:void(0);" onclick="$(this).parent().hide()" class="cross"><span>X</span></a>
</div>
<?php
				endforeach;
			endif;
		endforeach;
	endif;
?>
