/**
 * Generates the settings window.
 * 
 * @class SettingsWindow
 * @extends Ext.Window
 */
SettingsWindow = Ext.extend(Ext.Window, {
    title: lang['settings_window_title'],
    id: 'settings-window',
    width: 400,
    autoHeight: true,
    closeAction: 'hide',
    draggable: false,
    resizable: false,
    modal: true,
    initComponent: function() {
        this.items = [{
            xtype: 'form',
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
                    labelWidth: 170,
                    autoHeight: true,
                    bodyStyle: 'padding: 10px'
                },
                items: [{
                    xtype: 'panel',
                    id: 'password',
                    title: lang['settings_window_panel_password'],
                    items: [{
                        fieldLabel: lang['settings_window_old_password'],
                        name: 'old_password'
                    }, {
                        fieldLabel: lang['settings_window_new_password'],
                        name: 'new_password'
                    }, {
                        fieldLabel: lang['settings_window_new_password_confirm'],
                        name: 'new_password_confirm'
                    }]
                }, {
                    xtype: 'panel',
                    title: lang['settings_window_panel_profile'],
                    items: [{
                        fieldLabel: lang['settings_window_firstname'],
                        name: 'firstname'
                    }, {
                        fieldLabel: lang['settings_window_lastname'],
                        name: 'lastname'
                    }, {
                        fieldLabel: lang['settings_window_institution'],
                        name: 'institution'
                    }, {
                        fieldLabel: lang['settings_window_phone'],
                        name: 'phone'
                    }, {
                        fieldLabel: lang['settings_window_email'],
                        name: 'email'
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
                    this.hide();
                }
            }]
        }];

        // call parent
        SettingsWindow.superclass.initComponent.apply(this);
    },
    beforeShow: function() {
        Ext.getCmp('settings-form').load({
            url : BASE_URL + 'auth/settings',
            waitMsg: "Lade..."
        });

        // call parent
        SettingsWindow.superclass.beforeShow.apply(this);
    }
});

// register xtype to allow for lazy initialization
Ext.reg('settingswindow', SettingsWindow);
