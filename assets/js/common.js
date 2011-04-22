/**
 * Shows a message box with given the title and message.
 * 
 * @param {} title
 * @param {} message
 */
var message = function(title, message, icon) {
    Ext.Msg.show({
        title: title,
        msg: message,
        minWidth: 200,
        modal: true,
        icon: Ext.Msg.ERROR,
        buttons: Ext.Msg.OK
    });
}

settings = new Ext.Window({
    layout: 'fit',
    title: lang['settings_window_title'],
    id: 'settings-window',
    width: 400,
    height: 300,
    closeAction: 'hide',
    draggable: false,
    resizable: false,
    modal: true,
    items: new Ext.FormPanel({
        id: 'settings-form',
        url: BASE_URL + 'auth/settings',
        method: 'POST',
        border: false,
        items: [{
            xtype: 'tabpanel',
            border: false,
            activeTab: 0,
            defaults: {
                layout: 'form',
                defaultType: 'textfield',
                labelWidth: 200,
                autoHeight: true,
                bodyStyle: 'padding: 10px'
            },
            items: [{
				xtype: 'panel',
				id: 'password',
				title: "Passwort",
				items: [{
				    fieldLabel: "Altes Passwort",
				    name: 'old_password'
				}, {
				    fieldLabel: "Neues Passwort",
				    name: 'new_password'
				}, {
				    fieldLabel: "Neues Passwort wiederholen",
				    name: 'new_password_confirm'
				}]
		    }, {
                xtype: 'panel',
                title: "Profil",
                items: [{
                    fieldLabel: "Vorname"
                }, {
                    fieldLabel: "Nachname"
                }, {
                    fieldLabel: "Firma"
                }, {
                    fieldLabel: "Telefonnummer"
                }, {
                    fieldLabel: "E-Mail-Adresse"
                }]
            }]
        }],
        buttons: [{
            text: lang['settings_window_save'],
            handler: function() {
                Ext.getCmp('settings-form').getForm().submit();
            }
        }, {
            text: lang['settings_window_close'],
            handler: function() {
                settings.hide();
            }
        }]
    })
});

/*var extender = Ext.getCmp("settings-tabs");

settings.on('beforeshow', function() {
    extender.removeAll();

    Ext.Ajax.request({
        url : BASE_URL + 'auth/change_password',
        success: function(result) {
            extender.add(Ext.util.JSON.decode(result.responseText));
            extender.setActiveTab(0);
        }
    });

    settings.doLayout();
});*/

/**
 * Initialize tooltips.
 */
$(document).ready(function() {
    Ext.QuickTips.init();
});
