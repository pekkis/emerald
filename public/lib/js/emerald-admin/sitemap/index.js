// parse locale from the url - there could be an easier way of doing this
var locale = /\/locale\/(.*?)(\/|$)/.exec(window.location);
if(locale.length < 2) throw "Locale not found";
locale = locale[1];

// all possible actions 
var sitemapMethods = 
{
	addRootPage: function(evt)
	{
		Event.stop(evt);
		Emerald.Popup.open("/emerald-admin/sitemap/createPage/locale/"+locale+"/id/0", "pagepopup", "width=500px,height=500px");			
	},
	addSubPage: function(evt, id)
	{
		Event.stop(evt);
		Emerald.Popup.open("/emerald-admin/sitemap/createPage/locale/"+locale+"/id/"+id, "pagepopup", "width=500px,height=500px");			
	},
	editProperties: function(evt, id)
	{
		Event.stop(evt);
		Emerald.Popup.open("/emerald-admin/sitemap/editPage/id/"+id, "pagepopup", "width=500px,height=500px");	
	},
	deletePages: function(evt, dataSource)
	{
		Event.stop(evt);
		var ids = dataSource.getSelection();
		if(!Emerald.confirm("sitemap/delete_all_selected_pages",[ids.length])) return;
		
		$j.ajax({
			url: "/emerald-admin/sitemap/deletePage/",
			data: { "ids[]": ids },
			type: 'post',
			dataType: 'json',
			success: (function(data)
			{
				console.debug("data",data);
				if(data.type == 4) {
					Emerald.message("sitemap/delete_failed");
				} else {
					Emerald.message("sitemap/delete_success");
				}
				top.document.location.reload();
			}).bind(this)
		});
	},
	editPage: function(evt, id)
	{
		Event.stop(evt);
		var editw = window.open("/page/view/id/"+id,"editWin",'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
		if(window.focus()) editw.focus();
	},
	reorderPages: function(evt)
	{
		Event.stop(evt);
		Emerald.Popup.open("/emerald-admin/sitemap/reorder/locale/"+locale, "pagepopup", "width=500px,height=500px");
	},
	setHome: function(evt, id)
	{
		Event.stop(evt);
		$j.ajax({
			url: "/emerald-admin/sitemap/setHome/id/"+id,
			type: 'post',
			dataType: 'json',
			success: (function(data)
			{
				console.debug("data",data);
				if(data.type == 4) {
					Emerald.message("sitemap/set_home_failed");
				} else {
					Emerald.message("sitemap/set_home_success");
				}
				top.document.location.reload();
			}).bind(this)
		});
	}
};
// binds the per-row menu links to actions using the classnames
var bindRowMenu = function(menuElement, id)
{
	menuElement.select(".ac_addSubPage").invoke("observe","click", sitemapMethods.addSubPage.bindAsEventListener(this,id));
	menuElement.select(".ac_editProperties").invoke("observe","click", sitemapMethods.editProperties.bindAsEventListener(this,id));
	menuElement.select(".ac_editPage").invoke("observe","click", sitemapMethods.editPage.bindAsEventListener(this,id));
	menuElement.select(".ac_setHome").invoke("observe","click", sitemapMethods.setHome.bindAsEventListener(this,id));
}	
$j(document).ready(function() {

	$$('.localeSwither').invoke("observe","change", function(evt)
	{
		var elm = Event.element(evt);
		var locale = elm.getValue();
		window.location = "/emerald-admin/sitemap/index/locale/"+locale;
	});

	var url = "/emerald-admin/sitemap/branch";
	if(locale.length >1)
	{
		url += "/locale/"+locale;
	}
	
	var ds = new Emerald_DataSource_Hierarchial(
	{
		source: url,
		uniqCol: "id"
	});
	var dm = new Emerald_DataSource_DynamicMenu($$("#sitemapContent .dynamicMenu"), ds, $("activeCommand"));
	
	var dt = new Emerald_DataSource_Table_Hierarchial($("sitemapContent"),3);
	dt.setSource(ds,["title","#command#","#selector#","home","shard_id","created"]);
	dt.setColumnClassNames({
		"title":"identifyColumn",
		"#command#":"commandColumn"
	});
	
	dt.addColumnCallback("shard_id",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		cell.innerHTML = Emerald.t("common/shardnames/"+dataRow[colName]);
		tbRow.appendChild(cell);
		return cell;
	});
	dt.addColumnCallback("home",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		if(dataRow.isHome == "1")
		{
			cell.appendChild(new Element("img",{src: "/lib/gfx/nuvola/16x16/actions/gohome.png"}));
		}else{
			cell.innerHTML = "&nbsp;";
		}
		tbRow.appendChild(cell);
		return cell;
	});
	
	dt.addColumnCallback("title",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		var wrapper = new Element("span",{id: "eipId_"+dataRow.id});
		new Emerald_EIP_Element_Text(wrapper, 
		{
			postUrl: "/emerald-admin/sitemap/changeTitle",
			autoSave: true
		});
		
		wrapper.update(dataRow.title);
		cell.appendChild(wrapper);
		tbRow.appendChild(cell);
		
		cell.observe("click", function(evt){
			var elm = Event.element(evt);
			if(elm.nodeName != "TR") elm = elm.up("tr");
			elm.down("input.rowSelector").click();
		});
		
		return cell;
	});
	dt.addColumnCallback("#command#",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		
		var menu = $("nodeMenu").cloneNode(true);
		menu.show();
		cell.appendChild(menu);
		tbRow.appendChild(cell);
		
		new Emerald.RowMenu(menu.down("ul"),{hoverActivate: "tr"});
		bindRowMenu(menu.down("ul"), dataRow.id);
		return cell;
	});
	dt.setBranchControlColumn("title");
	dt.loadPage(0);
	$$(".ac_addRootPage").invoke("observe","click", sitemapMethods.addRootPage);
	$$(".ac_deletePage").invoke("observe","click", sitemapMethods.deletePages.bindAsEventListener(this,ds));
	$$(".ac_reorderPages").invoke("observe","click", sitemapMethods.reorderPages);
	
	
});
	
	