$j(document).ready(function() {
	$j('#container-1 > ul').tabs();
	
	var userDs = new Emerald_DataSource(
	{
		source: "/emerald-admin/user/loadUsers",
		uniqCol: "id"
	});
	
	var userMenu = new Emerald_DataSource_DynamicMenu($$("#userList .dynamicMenu"), userDs, $('activeUserCommand'));
	var userTable = new Emerald_DataSource_Table_Flat($("userList"));
	userTable.setSource(userDs,["#selector#","email","#command#","firstname","lastname"]);
	userTable.setColumnClassNames({
		"#selector#":"",
		"email":"identifyColumn",
		"#command#":"",
		"firstname":"",
		"lastname":""
	});
	userTable.addColumnCallback("email",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		cell.update(dataRow.email);
		tbRow.appendChild(cell);
		
		cell.observe("click", function(evt){
			var elm = Event.element(evt);
			if(elm.nodeName != "TR") elm = elm.up("tr");
			elm.down("input.rowSelector").click();
		});
		return cell;
	});
	userTable.addColumnCallback("#command#",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		
		var menu = $("userRowMenu").cloneNode(true);
		menu.show();
		cell.appendChild(menu);
		tbRow.appendChild(cell);
		
		new Emerald.RowMenu(menu.down("ul"),{hoverActivate: "tr"});
		menu.select(".ac_editUser").invoke("observe","click", (function(evt, id){
			Event.stop(evt);
			window.location = "/emerald-admin/user/viewUser/id/"+id;
		}).bindAsEventListener(this, dataRow.id));
		return cell;
	});
	userTable.loadPage(0);
	
	// Callbacks for editing users
	
	
	
	$$(".ac_deleteUser").invoke("observe","click", function(evt){
		Event.stop(evt);
		
		if(!Emerald.confirm(Emerald.t('user/delete_selected_users', [userDs.getSelection().length]))) return;
		
		$j.ajax({
			url: "/emerald-admin/user/deleteUser",
			data: { "ids[]": userDs.getSelection() },
			type: 'post',
			dataType: 'json',
			success: (function(data)
			{
				if(data.type == 4) {
					Emerald.message("common/operation_failed");
				} else {
					Emerald.message("user/users_deleted");
					userTable.massSelection(false);
					userTable.loadPage(0);
				}
			}).bind(this)
		});
	});
		
	// --------------
	
	var ds = new Emerald_DataSource(
	{
		source: "/emerald-admin/user/loadGroups",
		uniqCol: "id"
	});
	
	var dm = new Emerald_DataSource_DynamicMenu($$("#groupList .dynamicMenu"), ds, $('activeGroupCommand'));
	var dt = new Emerald_DataSource_Table_Flat($("groupList"));
	dt.setSource(ds,["#selector#","name","#command#"]);
	dt.setColumnClassNames({
		"#selector#":"",
		"name":"identifyColumn",
		"#command#":""
	});
	dt.addColumnCallback("name",function(tbRow, dataRow, colName)
	{
		var cell = new Element("td");
		cell.update(dataRow.name);
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
		
		var menu = $("groupRowMenu").cloneNode(true);
		menu.show();
		cell.appendChild(menu);
		tbRow.appendChild(cell);
		
		new Emerald.RowMenu(menu.down("ul"),{hoverActivate: "tr"});
		menu.select(".ac_editGroup").invoke("observe","click", (function(evt, id){
			Event.stop(evt);
			window.location = "/emerald-admin/user/viewGroup/id/"+id;
		}).bindAsEventListener(this, dataRow.id));
		return cell;
	});
	
	dt.loadPage(0);
	
	$$(".ac_deleteGroup").invoke("observe","click", function(evt){
		Event.stop(evt);
		
		if(!Emerald.confirm(Emerald.t('user/delete_selected_groups', [ds.getSelection().length]))) return;
		
		// if(!Emerald.confirm("(untranslated) delete all selected ("+ds.getSelection().length+") groups")) return;
		
		$j.ajax({
			url: "/emerald-admin/user/deleteGroup",
			data: { "ids[]": ds.getSelection() },
			type: 'post',
			dataType: 'json',
			success: (function(data)
			{
				if(data.type == 4) {
					Emerald.message("common/operation_failed");
				} else {
					Emerald.message("user/groups_deleted");
					dt.massSelection(false);
					dt.loadPage(0);
				}
			}).bind(this)
		});
	});	
});
