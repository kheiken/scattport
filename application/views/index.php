<?php $this->load->view('header'); ?>

<script type="text/javascript">
var projectPanel = new Ext.tree.TreePanel({
    id: 'project-tree',
    region: 'west',
    title: "Projekte",
    height: 400,
    bodyStyle: 'margin-bottom: 6px;',
    autoScroll: true,
    rootVisible: true,
    lines: false,
    tbar: [{
    	icon: BASE_PATH + 'assets/images/icons/box--plus.png',
        text: "Neues Projekt",
        scope: this
    },{
        id: 'delete',
        icon: BASE_PATH + 'assets/images/icons/box--minus.png',
        text: "Entfernen",
        scope: this
    }],
    root: {
        text: "Meine Projekte",
        expanded: true,
        children: [{
            text: 'Projekt 1',
            leaf: true
        }, {
            text: 'Projekt 2',
            leaf: true
        }]
    }
});

var layoutLeft2 = new Ext.Panel({
    region: 'west',
    margin: '10 0 0 0',
    autoScroll: true,
    bodyStyle: 'padding: 10px; background: #eee;',
    html: 'Test'
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
    items: [panelCenter]
});

var layoutMain = new Ext.Viewport({
    layout: 'border',
    items: [{
        height: 46,
        region: 'north',
        xtype: 'box',
        el: 'header',
        border: false
    }, {
        region: 'west',
        baseCls: 'x-plain',
        xtype: 'panel',
        autoHeight: true,
        width: 190,
        minWidth: 190,
        maxWidth: 300,
        border: false,
        split: true,
        margins: '0 0 0 5',
        items: [projectPanel, layoutLeft2]
    }, layoutCenter]
});

function logout() {
    $.ajax({
        url: BASE_URL + 'auth/do_logout',
        method: 'post',
        success: function(xhr) {
            window.location = BASE_URL + 'auth/login';
        }
    });
}
</script>

<div id="main"></div>

<?php $this->load->view('footer'); ?>