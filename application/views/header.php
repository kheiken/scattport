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
  <script type="text/javascript">
  var BASE_URL = '<?=base_url();?>' + 'index.php/';
  var BASE_PATH = '<?=base_url();?>';

  $(document).ready(function() {
      Ext.QuickTips.init();
  });
  </script>
</head>

<body>
