<?php $this->load->view('header'); ?>

<script type="text/javascript">
var layoutLeft1 = new Ext.tree.TreePanel({
    region: 'north',
    title: "Projekte",
    height: 250,
    bodyStyle: 'margin-bottom: 6px;',
    autoScroll: true,
    enableDD: false,
    rootVisible: false,
    id: 'treePanel',
    root: {
        text: "Projekte",
        expanded: true,
        nodeType: 'async',
        children: [{
            text: 'Projekt 1',
            expanded: false
        }, {
            text: 'Projekt 2',
            expanded: false,
        }]
    }
});

var layoutLeft2 = new Ext.Panel({
    region: 'center',
    margin: '10 0 0 0',
    autoScroll: true,
    bodyStyle: 'padding: 10px; background: #eee;',
    html: 'Test'
});

var toolbarCenter = new Ext.Toolbar({
    items: ['->', {
        icon: BASE_PATH + 'assets/images/icons/minus-circle.png',
        text: 'Logout',
        handler: logout
    }]
});

var panelCenter = new Ext.TabPanel({
    xtype: 'tabpanel',
    resizeTabs: false,
    minTabWidth: 115,
    tabWidth: 135,
    enableTabScroll: true,
    layoutOnTabChange: true,
    border: false,
    activeItem: 'tab_welcome',
    autoDestroy: false,
    items: [{
        xtype: 'panel',
        id: 'tab_welcome',
        bodyStyle: 'padding: 10px',
        title: "Willkommen"
    }]
});

var layoutCenter = new Ext.Panel({
    id: 'content-panel',
    region: 'center',
    layout: 'card',
    margins: '0 5 5 0',
    activeItem: 0,
    border: true,
    tbar: toolbarCenter,
    items: [panelCenter]
});

var layoutMain = new Ext.Viewport({
    layout: 'border',
    renderTo: Ext.getBody(),
    items: [{
        region: 'north',
        autoHeight: true,
        height: 100,
        border: false,
            html: '<div id="header">Scattport</div>',
            margins: '0 0 5 0',
            style: 'margin-bottom: 4px;'
        }, {
            region: 'west',
            baseCls: 'x-plain',
            xtype: 'panel',
            autoHeight: true,
            width: 180,
            border: false,
            split: true,
            margins: '0 0 0 5',
            items: [layoutLeft1, layoutLeft2]
        }, layoutCenter]
});

function logout() {
    Ext.Ajax.request({
        url: BASE_URL + 'auth/do_logout',
        method: 'post',
        success: function(xhr) {
            window.location = BASE_URL + 'auth/login';
        }
    });
}

layoutMain.show();
</script>

<?php $this->load->view('footer'); ?>