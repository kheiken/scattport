<!DOCTYPE html>
<html lang="<?=$this->config->item('lang_selected');?>">
<head>
  <title>Scattport</title>
  <meta charset="utf-8" />
  <?=link_tag('assets/css/main.css');?>
  <?=link_tag('assets/js/ext/resources/css/ext-all.css');?>
  <!-- ExtJS library: base/adapter -->
  <?=script_tag('assets/js/ext/adapter/ext/ext-base.js');?>
  <!-- ExtJS library: all widgets -->
  <?=script_tag('assets/js/ext/ext-all.js');?>
  <script type="text/javascript">
  var BASE_URL = '<?=site_url('/');?>';
  var BASE_PATH = '<?=base_url();?>';
  </script>
  <?=script_tag('assets/js/language/' . $this->config->item('language') . '.js');?>
  <?=script_tag('assets/js/SettingsWindow.js');?>
  <?=script_tag('assets/js/common.js');?>
</head>

<body>

<div id="header">
  <?=img(array('src' => 'assets/images/logo.png', 'style' => 'margin-left: 5px'));?>
  <div style="float: right; margin-top: 15px; margin-right: 10px; color: #ccc;">
    <a href="javascript:void(0);" onclick="settings.show();" style="padding: 5px">Einstellungen</a> |
    <a href="javascript:void(0);" onclick="logout();" style="padding: 5px">Logout</a>
  </div>
  <?=img(array('src' => 'assets/images/lang_' . $this->config->item('lang_selected') . '.png', 'style' => 'float: right; margin-top: 18px; margin-right: 5px;'));?>
</div>
