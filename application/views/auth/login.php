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

<script type="text/javascript">
var loginForm = new Ext.form.FormPanel({
    frame: true,
    border: false,
    width: 340,
    labelWidth: 120,
    url: BASE_URL + 'auth/login',
    method: 'POST',
    items: [
        new Ext.form.TextField({
            id: 'username',
            fieldLabel: "Benutzername",
            allowBlank: false,
            blankText: "Benutzernamen eingeben"
        }),
        new Ext.form.TextField({
            id: 'password',
            fieldLabel: "Passwort",
            inputType: 'password',
            allowBlank: false,
            blankText: "Passwort eingeben"
        }),
        <?php if ($this->config->item('remember_users', 'auth')): ?>
        new Ext.form.Checkbox({
            id: 'remember',
            height: 20,
            boxLabel: "Eingeloggt bleiben"
        }),
        <?php endif; ?>
    ],
    buttons: [{
        text: "Login",
        handler: function() {
            if (loginForm.getForm().isValid()) {
                loginForm.getForm().submit({
                    success: function() {
                        window.location = BASE_URL;
                    },
                    failure: function(result, response) {
                    	message("Fehler", response.result.message);
                    }
                });
            }
        }
    }, {
        text: "Zurücksetzen",
        handler: function() {
            loginForm.getForm.reset();
        }
    }]
});

var loginWindow = new Ext.Window({
    title: "Willkommen zu scattport",
    layout: 'fit',
    height: 200,
    width: 340,
    closable: false,
    resizable: false,
    draggable: false,
    items: [loginForm]
});

loginWindow.show();
</script>

</body>
</html>
