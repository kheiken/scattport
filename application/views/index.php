<?php $this->load->view('header'); ?>

<script type="text/javascript">
var projectTree = new Ext.tree.TreePanel({
    region: 'west',
    title: "Projekte",
    height: 250,
    bodyStyle: 'margin-bottom: 6px;',
    autoScroll: true,
    enableDD: false,
    rootVisible: false,
    lines: false,
    useArrows: true,
    id: 'treePanel',
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
    dataUrl: BASE_URL + 'projects/getAvailable',
    root: {
        nodeType: 'async',
        text: "Projekte",
        expanded: true,
        id: 'projects'
    }
});

projectTree.on('click', loadProjectInfo);

var tabPanel = new Ext.TabPanel({
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
        title: "Willkommen",
        closable: true
    }]
});

var layoutCenter = new Ext.Panel({
    id: 'content-panel',
    region: 'center',
    layout: 'card',
    margins: '0 5 5 0',
    activeItem: 0,
    border: true,
    items: [tabPanel]
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
        items: [projectTree]
    }, layoutCenter]
});

function loadProjectInfo(n) {
	if (n.isLeaf()) {
		Ext.Ajax.request({
	        url: BASE_URL + 'projects/detail/' + n.prjId,
	        method: 'get',
	        success: function(result, request) {
				var theResponse = Ext.util.JSON.decode(result.responseText);

				tabPanel.add({
		            title: "New Tab",
		            html: "Lade Projekt...",
		            closable: true
		        }).show();
	       	},
	        failure: function(result, request) {
	       		switch(result.status) {
	       			case 404:
	       				Ext.MessageBox.alert("Fehler", "Das gewünschte Projekt konnte nicht gefunden werden.");
	       				break;
	       			case 401:
	       				Ext.MessageBox.alert("Fehler", "Sie besitzen nicht die nötigen Zugriffsrechte, um dieses Projekt zu lesen."
	       					+ "Wenden Sie sich an den Projektbesitzer, um Zugriff zu erhalten.");
	       				break;
	       		};
	       	}
	   	});
    }
}
</script>
<div id="main"></div>

<?php $this->load->view('footer'); ?>
