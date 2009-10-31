/**
 * DataSource acts as an object bridge between client and server programs
 * 
 * It is configured with a source url for loading the data from and can be triggered
 * to refresh that data or only a part of it. The data refresh can be observed
 * by outside classes, who can then, for example, display the refreshed data.
 * 
 *  The data can also be marked as "selected" by an outside class - this makes
 *  it possible to make selections spanning multiple pages in paged tables.
 */
var Emerald_DataSource = Class.create();
Emerald_DataSource.prototype = {
	initialize: function(options)
	{
		this.options = Object.extend(
		{
			source: null, // the url to load data from
			uniqCol: "id" // column used for identifyind individual objects 
		},options);
		this.totalLength = 0;
		this.data = $H();
		this.observers = $A();
		this.selection = $A();
		this.pendingLoads = $A();
	},
	/**
	 * (Re)Loads the data between two given indices
	 * 
	 * @param {Int} startIndex
	 * @param {Int} endIndex
	 */
	load: function(startIndex,endIndex)
	{
		if(!startIndex) startIndex = 0;
		if(!endIndex) endIndex = 0;
		var hash = startIndex+"_"+endIndex;
		
		if(this.pendingLoads.indexOf(hash) > -1) return false;
		this.pendingLoads.push(hash);
		
		$j.ajax({
			url: this.options.source,
			async: true,
			data: 
			{
				start: startIndex,
				end: endIndex,
				sort: null
			},
			type: 'post',
			dataType: 'json',
			success: (function(data)
			{
				this.pendingLoads = this.pendingLoads.without(hash);
				var msg = data.message;
				this._processMessage(msg);
			}).bind(this)
		});
		return true;
	},
	_processMessage: function(msg)
	{
		console.debug("loaded msg", msg);
		if(msg.total !== undefined) this.totalLength = msg.total;
		for(var i = msg.indexStart ; i <= msg.indexEnd ; i++) this.data.set(i,msg.data.pop());
		this._notifyLoad(msg.indexStart,msg.indexEnd);
	},
	/**
	 * Adds an observer for the dataset
	 * 
	 * @param {Object} obj
	 */
	addObserver: function(obj)
	{
		this.observers.push(obj);
	},
	
	/**
	 * Returns the name of the unique column of the dataset
	 */
	getUnique: function()
	{ 
		return this.options.uniqCol; 
	},
	getData: function(start, end)
	{
		
		var obj = $A();
		for(var i = start ; i <= end ; i++ ) if(this.data.get(i)) obj.push(this.data.get(i));
		return obj;
	},
	getTotal: function()
	{
		return this.totalLength;
	},
	/**
	 * Returns the selection as an array of values from the object's unique column
	 */
	getSelection: function()
	{
		return this.selection;
	},
	/**
	 * Notifies the observer after a reload has happened
	 * 
	 * @scope protected
	 * @param {Int} start Starting index of the reload
	 * @param {Int} end Ending index of the reload
	 * @param {Array} data Array containing the datarows returned by the server
	 */
	_notifyLoad: function(start, end)
	{
		this.observers.each(function(obj)
		{ 
			if(obj.dsOnLoad) obj.dsOnLoad(start, end); 
		});
	},
	
	/**
	 * Toggles a selection of a single datarow
	 * 
	 * @param {Integer|Any} id The value of the unique column of the row
	 * @param {Boolean} isSelected Selection status
	 */
	toggleSelect: function(id, isSelected)
	{
		if(isSelected) 
		{
			this.selection.push(id);	
		} else {
			while(this.selection.indexOf(id) > -1)
				this.selection.splice(this.selection.indexOf(id), 1);
		}
		
		this.observers.each(function(obj)
		{ 
			if(obj.dsOnSelectToggle) obj.dsOnSelectToggle(id, isSelected); 
		});
	}
};

var Emerald_DataSource_Hierarchial = Class.extend(Emerald_DataSource, {
	initialize: function(options)
	{
		Emerald_DataSource.prototype.initialize.call(this, options);
		this.branches = $H();
	},
	_processMessage: function(msg)
	{
		this.branches.set(msg.indexStart, msg.data.pluck("id"));
		if(msg.total !== undefined) this.totalLength = msg.total;
		msg.data.each((function(dataRow){
			this.data.set(dataRow.id, dataRow);
		}).bind(this)) 
		
		this._notifyLoad(msg.indexStart,msg.indexStart);
	},
	getBranchIds: function(branchId)
	{
		return this.branches.get(branchId);
	}
});
var Emerald_DataSource_Flat = Class.extend(Emerald_DataSource, {
	
});




/**
 * A table displayer for the datasource
 * 
 * Can display data suitable for a "flat" table
 */
var Emerald_DataSource_Table_Flat = Class.create();
Emerald_DataSource_Table_Flat.prototype = 
{
	initialize: function(table, pageSize)
	{
		this.table = table;
		this.dataSource = null;
		
		this.dataCols = $A();
		this.columnCallbacks = $H();
		this.rowIdentifier = null;
		this.columnClassNames = $H();
		
		this.paging = {start:0, end:0, size: 20, current: 0};
		if(pageSize) this.paging.size = parseInt(pageSize);
		
		this.addColumnCallback("#selector#", this._appendSelector.bind(this));
		
		table.select(".prevPage").invoke("observe","click",this._prevPage.bind(this));
		table.select(".nextPage").invoke("observe","click",this._nextPage.bind(this));
		table.select(".selectAll").invoke("observe","click",this._handleMassSelect.bind(this, true));
		table.select(".selectNone").invoke("observe","click",this._handleMassSelect.bind(this, false));
	},
	/**
	 * Assigns the datasource for this table
	 * 
	 * @param {Emerald_DataSource} 	dataSource
	 * @param {Array} dataColumns 	Names of the dataColumns in the order they 
	 * 								are wanted to appear in the table. A special placeholder
	 * 								#selector# can be used to indicate the column for the selection 
	 * 								checkbox if any (the checkbox may not actually be appended to the table). 
	 */
	setSource: function(dataSource, dataColumns)
	{
		this.dataSource = dataSource;
		if(dataColumns)
		{
			this.dataCols = $A(dataColumns);
		}
		this.dataSource.addObserver(this);
		this.rowIdentifier = this.dataSource.getUnique();
	},
	setColumnClassNames: function(classHash)
	{
		this.columnClassNames = $H(classHash);
	},
	/**
	 * Adds a callback for a column, for advanced content processing
	 * The callback should return a td node already appended to the row
	 * 
	 * callback declaration should be as follows:
	 * function(tableRow, dataRow)
	 * where tablerow is the current row and datarow the node 
	 * 
	 * @param {Object} colName
	 * @param {Object} callBack
	 */
	addColumnCallback: function(colName, callBack)
	{
		if(typeof callBack == "function")
		{
			this.columnCallbacks.set(colName, callBack);
		}
	},
	/**
	 * Datasources callback 
	 * 
	 * @param {Int} start	The starting index of the dataset
	 * @param {Int} end		The ending index of the dataset
	 * @param {Array} data	Array containing the rows
	 */
	dsOnLoad: function(start, end)
	{
		this.paging.start = parseInt(start);
		this.paging.end = parseInt(end);
		var pElm = this.table.select("tbody").first(); 
		pElm.childElements().invoke("remove"); // start from index 0 - clear the table and just fill it again
		
		var data = this.dataSource.getData(start, end);
		for(var i = start ; i <= end ; i++ ) 
		{
			//if(data.length) this._appendToIndex(this._buildRow(data.pop()));
			if(data.length) pElm.appendChild(this._buildRow(data.pop()));
		}
		
		this.table.select(".currentPage").invoke("update",this.currentPage()+1);
		this.table.select(".totalPages").invoke("update",this.totalPages());
	},
	/**
	 * Builds and returns a single table row
	 * 
	 * @param {Object} dataRow
	 * @return {HTML_TR_Element}
	 */
	_buildRow: function(dataRow)
	{
		//var row = new Element("tr"); // {id: (this.table.id?this.table.id:"tbl")+"_"+dataRow[this.rowIdentifier]} // if we'd want to identify single rows
		var row = new Element("tr",
		{
			id: (this.table.id?this.table.id:"tbl")+"_"+dataRow[this.rowIdentifier]
		});
		document.body.appendChild(row);
		var columns = this.dataCols.length ? this.dataCols : $H(dataRow).keys();
		var clsName;
		columns.each((function(colName)
		{
			var cell = null;
			if(this.columnCallbacks.get(colName)) {
				cell = this.columnCallbacks.get(colName)(row, dataRow, colName);
			} else {
				cell = new Element("td");
				cell.innerHTML = (dataRow[colName] != undefined) ? dataRow[colName] : "&nbsp;";
				row.appendChild(cell);	
			}
			if(clsName = this.columnClassNames.get(colName)) cell.addClassName(clsName);
			
		}).bind(this));
		
		
		return row;
	},
	_getRow: function(id)
	{
		return $((this.table.id?this.table.id:"tbl")+"_"+id);
	},
	/**
	 * Appends a selector to the row
	 * 
	 * The default beaviour is to create a checkbox element, a cell for it and append it to the row.
	 * This method can also be overridden to just bind the row's onClick to something and skip the element creation.
	 * 
	 * @param {HTML_TR_Element} tableRow
	 * @param {Object} dataRow
	 * @param {String} colName
	 */
	_appendSelector: function(tableRow, dataRow, colName)
	{
		var rowId = parseInt(dataRow[this.rowIdentifier]);
		var cell = new Element("td",{className: "selectorColumn"});
		var selElm = new Element("input", {type: "checkbox", className: "rowSelector", name: "obj["+rowId+"]"});
		if(this.dataSource.getSelection().indexOf(rowId) > -1) selElm.checked = true;
		selElm.observe("click", this._handleSelectToggle.bind(this,rowId));
		
		cell.appendChild(selElm);
		tableRow.appendChild(cell);	
		return cell;
	},
	_handleSelectToggle: function(rowId)
	{
		var row = this._getRow(rowId);
		var selectElm = row.down("input.rowSelector");
		
		var isChecked = selectElm.checked;
		this.dataSource.toggleSelect(rowId, isChecked);
		this.table.select(".selectionCount").invoke("update",this.dataSource.getSelection().length);
		(isChecked) 
			? row.addClassName("selected")
			: row.removeClassName("selected");
	},
	/**
	 * Handles (de)selections performed to all nodes
	 * Currently there is no select all functionality
	 * @param {Object} isSelect
	 */
	massSelection: function(isSelect)
	{
		/* ei toimi select all:in kanssa */
		isSelect = false;
		
		// clone the array to avoid trouble with enumeration
		var currSel = Array.from(this.dataSource.getSelection());
		currSel.each((function(rowId){
			this.dataSource.toggleSelect(rowId, isSelect);
		}).bind(this));
		
		this.table.select("input.rowSelector").each((function(input){
			input.checked = isSelect;
			(isSelect) 
				? input.up("tr").addClassName("selected")
				: input.up("tr").removeClassName("selected");
		}).bind(this));
		
		this.table.select(".selectionCount").invoke("update",this.dataSource.getSelection().length);
	},
	/**
	 * CallBack for de-selecting all
	 * @param {Object} isSelect
	 */
	_handleMassSelect: function(isSelect)
	{
		this.massSelection(isSelect);
	},
	_insertAfter: function(row, trElm)
	{
		var pElm = trElm.up("tbody"); // oh yes, use that freakin tbody
		var seekElm = trElm.next("tr");
		
		(seekElm) 
				? pElm.insertBefore(row, seekElm)
				: pElm.appendChild(row);
	},
	loadPage: function(pageNro)
	{
		var startIndex = 0;
		var endIndex = 0;
		var totalCount;
		// if no total count set, this is the first load
		if((totalCount = this.dataSource.getTotal()) < 1)
		{
			endIndex = startIndex + this.paging.size -1;
		}
		else
		{
			var pageCount = Math.ceil(totalCount / this.paging.size);
			if(pageNro > pageCount -1) return;
			startIndex = pageNro * this.paging.size;
			endIndex = startIndex + this.paging.size -1;
		
			if(endIndex+1 >= totalCount)
			{
				endIndex = totalCount -1;
			}
			
		}
		if(startIndex > 0)
			this.paging.current = Math.floor((startIndex+1)/this.paging.size);
		else
			this.paging.current = 0;
		this.dataSource.load(startIndex, endIndex);
	},
	_nextPage: function(evt)
	{
		Event.stop(evt);
		this.loadPage(this.paging.current +1);
		return;
	},
	_prevPage: function(evt)
	{
		Event.stop(evt);
		if(this.paging.current > 0) this.loadPage(this.paging.current -1);
		return;
	},
	/**
	 * Returns the current page (starting from zero)
	 */
	currentPage: function()
	{
		return this.paging.current;
	},
	totalPages: function()
	{
		if((totalCount = this.dataSource.getTotal()) < 1)
		{
			return 0;
		}
		else
		{
			return Math.ceil(totalCount / this.paging.size);
		}
	}	
};

var Emerald_DataSource_Table_Hierarchial = Class.extend(Emerald_DataSource_Table_Flat, {
	initialize: function(table, pageSize)
	{
		Emerald_DataSource_Table_Flat.prototype.initialize.call(this, table, pageSize);
		this.branches = $H();
		this.branchControlCol = null;
		
		
	},
	setSource: function(dataSource, dataColumns)
	{
		var ret = Emerald_DataSource_Table_Flat.prototype.setSource.call(this, dataSource, dataColumns);
		
		var open = Emerald.Cookie.get("openBranches");
		console.debug(open);
		$A(open).each((function(id)
		{
			// timing problem : this.dataSource.load(parseInt(id));
		}).bind(this));
		
		
		return ret;
	},
	/**
	 * Callback for datasource onload event
	 * 
	 * @param {Object} start The loaded branch id (parent id)
	 */
	dsOnLoad: function(start, end)
	{
		this.paging.start = parseInt(start);
		this.paging.end = parseInt(end);
		
		
		this._buildBranch(start);
		this.table.select(".currentPage").invoke("update",this.currentPage()+1);
		this.table.select(".totalPages").invoke("update",this.totalPages());
	},
	_buildBranch: function(id)
	{
		console.debug("build branch", id);
		// data: array containing all nodes in the branch
		
		var data = $A();
		this.dataSource.getBranchIds(id).each((function(bId){
			data.push(this.dataSource.getData(bId, bId).first());
		}).bind(this));
		
		var tblRow = null;
		var branchRows = $A();
		
		var parentRow = this._getRow(id);
		
		data.each((function(row){
			tblRow = this._buildRow(row);
			branchRows.push(tblRow);
			if(data.length) 
			{
				(parentRow)
					? this._insertAfter(tblRow, parentRow)
					: this.table.down("tbody").appendChild(tblRow);	
			}
			parentRow = tblRow;
		}).bind(this));
		
		this.branches.set(id, branchRows);
		
	},
	_buildRow: function(dataRow)
	{
		var rowId = dataRow[this.rowIdentifier];
		var depth = this._getDepth(rowId);
		
		var row = new Element("tr",
		{
			id: (this.table.id?this.table.id:"tbl")+"_"+rowId,
			className: "depth_"+depth
		});
		document.body.appendChild(row);
		var columns = this.dataCols.length ? this.dataCols : $H(dataRow).keys();
		
		columns.each((function(colName)
		{
			var cell = null;
			if(this.columnCallbacks.get(colName)) {
				cell = this.columnCallbacks.get(colName)(row, dataRow, colName);
			} else {
				cell = new Element("td");
				breakPreventer = new Element("nobr");
				cell.appendChild(breakPreventer);
				breakPreventer.innerHTML = (dataRow[colName] != undefined) ? dataRow[colName] : "&nbsp;";
				row.appendChild(cell);	
			}
			if(clsName = this.columnClassNames.get(colName)) cell.addClassName(clsName);
			if(colName == this.branchControlCol)
			{
				this._appendBranchManager(cell, dataRow);
			}
			
		}).bind(this));
		
		return row;
	},
	_appendBranchManager: function(tableCell, dataRow)
	{
		
		var rowId = parseInt(dataRow[this.rowIdentifier]);
		var wrapper = new Element("label", {className: "branchManager"});
		
		// Ie doesn't show the bg image for empty elm.
		wrapper.appendChild(document.createTextNode('\u00A0'));
		
		var mgrElm = new Element("input", {type: "checkbox", name: "branch["+rowId+"]"});
		wrapper.observe("click", this._handleBranchToggle.bindAsEventListener(this,rowId));
		if((parseInt(dataRow.child_cnt) < 1))
		{
			wrapper.setStyle({visibility: "hidden"});
		}else wrapper.appendChild(mgrElm);
		
		tableCell.insert({top: wrapper});
		tableCell.addClassName("branchControl");
		//tableRow.appendChild(cell);	
	},
	_handleBranchToggle: function(evt, rowId)
	{
		Event.stop(evt);
		var elm = Event.element(evt);
		if(elm.nodeName != "INPUT") 
		{
			// if the point of origin was not the input, we must invert the checkbox
			elm = elm.down("input");
		}
		
				
		var isOpen = !elm.checked;
		var okToFlip = true;
		if(this.branches.get(rowId)) // if already loaded
		{
			this._recursiveToggle(rowId, isOpen);
		} else {
			// returns false if changes pending - no updating of the icons
			okToFlip = this.dataSource.load(rowId);
		}
		if(okToFlip) elm.checked = !elm.checked;
		(elm.checked)
			? elm.up("label").addClassName("active")
			: elm.up("label").removeClassName("active");
			
		
		this._saveStatus(rowId, elm.checked);
	},
	_saveStatus: function(id, checked)
	{
		var open = $A(Emerald.Cookie.get("openBranches"));
		if(checked)
		{
			open.push(id);
		}else
		{
			open = open.without(id);
		}
		Emerald.Cookie.set("openBranches", open);
		
	},
	_recursiveToggle: function(branchId, isOpen)
	{
		var brIds = this.dataSource.getBranchIds(branchId);
		brIds.each((function(rowId){
			
			var row = this._getRow(rowId);
			
			if(this.branches.get(rowId) && row.down("label.branchManager input").checked)
			{
				this._recursiveToggle(rowId, isOpen);
			}
			(isOpen) ? row.show() : row.hide();
			
		}).bind(this));
	},
	_getDepth: function(rowId)
	{
		var depth = -1;
		var data = null;
		var parentId = rowId;
		do
		{
			depth++;
			data = this.dataSource.getData(parentId,parentId).first();
			parentId = data.parent_id;
		}while(parentId);
		return depth;
	},
	setBranchControlColumn: function(columnName)
	{
		this.branchControlCol = columnName;
	}
});

/**
 * A dynamic meny, contents change according to the number of selected items
 * Recognized classnames:
 * - rq_exactlyOne : menu item active only if a single node is selected
 * - rq_atLeastOne : menu item active only if elements selected
 */
var Emerald_DataSource_DynamicMenu = Class.create();
Emerald_DataSource_DynamicMenu.prototype = 
{
	initialize: function(lists, dataSource, commandDisplayer)
	{
		this.lists = lists;
		this.dataSource = dataSource;
		this.dataSource.addObserver(this);
		this.commandDisplayer = commandDisplayer;
		this.lists.map(this._bindHover.bind(this));
		this.refresh();
	},
	dsOnSelectToggle: function()
	{
		console.debug("menu", this.dataSource.getSelection());
		this.refresh();
	},
	refresh: function()
	{
		this.lists.map(this._updateList.bind(this));
	},
	_bindHover: function(listElement)
	{
		listElement.select("li span").invoke("hide");
		listElement.select("li").invoke("observe","mouseover", this._setActive.bindAsEventListener(this,true));
		listElement.select("li").invoke("observe","mouseout", this._setActive.bindAsEventListener(this,false));
		//listElement.select("li").map(this._bindTextDisplayer.bind(this));
	},
	_setActive: function(evt, isActive)
	{
		console.debug("setactive",isActive);
		if(this.commandDisplayer)
		{
			var elm = Event.element(evt);
			if(elm.nodeName != "LI") elm = elm.up("li");
			var text = elm.down("span");
			if(isActive)
			{
				this.commandDisplayer.innerHTML = text.innerHTML;
				
			} else {
				text.innerHTML = this.commandDisplayer.innerHTML;
				this.commandDisplayer.innerHTML = "";
			}
			
		}	
	},
	_updateList: function(listElement)
	{
		var selCount = this.dataSource.getSelection().length;
		
		listElement.select("li").invoke("show");
		
		if(selCount < 1)
		{
			listElement.select("li.rq_atLeastOne").invoke("hide");
		}
		if(selCount != 1)
		{
			listElement.select("li.rq_exactlyOne").invoke("hide");
		}
	}
};
