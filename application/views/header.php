<!DOCTYPE html>
<html lang="de">
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
  <?=script_tag('assets/js/ProjectInfoWindow.js');?>
  <script type="text/javascript">
  var BASE_URL = '<?=base_url();?>' + 'index.php/';
  var BASE_PATH = '<?=base_url();?>';

  $(document).ready(function() {
      Ext.QuickTips.init();
  });
  </script>
</head>

<body>

<div id="header">
  <?=img(array('src' => 'assets/images/logo.png', 'style' => 'margin-left: 5px'));?>
  <div style="float: right; margin-top: 15px; margin-right: 10px; color: #ccc;">
    <?=anchor('auth/settings', "Einstellungen", array('style' => 'padding: 5px;'));?> |
    <?=anchor('auth/logout', "Logout", array('style' => 'padding: 5px;'));?>
  </div>
</div>
