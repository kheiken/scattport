/**
 * Generates the settings window.
 * 
 * @class SettingsWindow
 * @extends Ext.Window
 * @author Eike Foken <kontakt@eikefoken.de>
 */
SettingsWindow = Ext.extend(Ext.Window, {
	title: lang['settings_window_title'],
	id: 'settings-window',
	width: 400,
	closeAction: 'hide',
	draggable: false,
	resizable: false,
	modal: true,
	initComponent: function() {
		this.items = [{
			xtype: 'form',
			id: 'settings-form',
			url: BASE_URL + 'auth/settings',
			method: 'post',
			border: false,
			items: [{
				xtype: 'tabpanel',
				border: false,
				activeTab: 0,
				defaults: {
					layout: 'form',
					defaultType: 'textfield',
					labelWidth: 170,
					height: 150,
					bodyStyle: 'padding: 10px'
				},
				items: [{
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
				}, {
					xtype: 'panel',
					id: 'password',
					title: lang['settings_window_panel_password'],
					items: [{
						fieldLabel: lang['settings_window_old_password'],
						name: 'old_password',
						inputType: 'password'
					}, {
						fieldLabel: lang['settings_window_new_password'],
						name: 'new_password',
						inputType: 'password'
					}, {
						fieldLabel: lang['settings_window_new_password_confirm'],
						name: 'new_password_confirm',
						inputType: 'password'
					}, {
						xtype: 'displayfield',
						value: lang['settings_window_password_note'],
						hideLabel: true
					}]
				}]
			}],
			buttons: [{
				text: lang['settings_window_save'],
				handler: function() {
					var theForm = Ext.getCmp('settings-form').getForm();

					if (theForm.isValid()) {
						theForm.submit({
							success: function() {
								Ext.getCmp('settings-window').hide();
							}
						});
					}
				}
			}, {
				text: lang['settings_window_cancel'],
				handler: function() {
					Ext.getCmp('settings-window').hide();
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
