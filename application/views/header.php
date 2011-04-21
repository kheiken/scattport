<!DOCTYPE html>
<html lang="<?=$this->config->item('lang_selected');?>">
<head>
  <title>Scattport</title>
  <meta charset="utf-8" />
  <?=link_tag('assets/css/main.css');?>
  <?=link_tag('assets/js/ext/resources/css/ext-all.css');?>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
  <!-- ExtJS library: base/adapter -->
  <?=script_tag('assets/js/ext/adapter/jquery/ext-jquery-adapter.js');?>
  <!-- ExtJS library: all widgets -->
  <?=script_tag('assets/js/ext/ext-all.js');?>
  <script type="text/javascript">
  var BASE_URL = '<?=site_url('/');?>';
  var BASE_PATH = '<?=base_url();?>';
  </script>
  <?=script_tag('assets/js/common.js');?>
</head>

<body>

<div id="header">
  <?=img(array('src' => 'assets/images/logo.png', 'style' => 'margin-left: 5px'));?>
  <div style="float: right; margin-top: 15px; margin-right: 10px; color: #ccc;">
    <?=anchor('auth/settings', "Einstellungen", array('style' => 'padding: 5px;'));?> |
    <?=anchor('auth/logout', "Logout", array('style' => 'padding: 5px;'));?>
  </div>
  <?=img(array('src' => 'assets/images/lang_de.png', 'style' => 'float: right; margin-top: 18px; margin-right: 5px;'));?>
</div>
