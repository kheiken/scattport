<?php $this->load->view('header'); ?>

<script type="text/javascript">
var message = function(title, message) {
    Ext.Msg.show({
        title: title,
        msg: message,
        minWidth: 200,
        modal: true,
        icon: Ext.Msg.INFO,
        buttons: Ext.Msg.OK
    });
};

var loginForm = new Ext.form.FormPanel({
    frame: true,
    width: 340,
    labelWidth: 120,
    defaults: {
        width: 180
    },
    items: [
        new Ext.form.TextField({
            id: "username",
            fieldLabel: "Benutzername",
            allowBlank: false,
            blankText: "Benutzernamen eingeben"
        }),
        new Ext.form.TextField({
            id:"password",
            fieldLabel: "Passwort",
            inputType: 'password',
            allowBlank: false,
            blankText: "Passwort eingeben"
        })
    ],
    buttons: [{
        text: "Login",
        handler: function() {
            if (loginForm.getForm().isValid()) {
                loginForm.getForm().submit({
                    url: BASE_URL + 'auth/do_login',
                    waitMsg: "Lade...",
                    success: function(loginForm) {
                        message("Erfolg", "Willkommen zurück");
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
    height: 140,
    width: 340,
    closable: false,
    resizable: false,
    draggable: false,
    items: [loginForm]
});

loginWindow.show();
</script>

<?php $this->load->view('footer'); ?>
