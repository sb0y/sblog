function sorting (table_obj)
{
	this.sorting = 0;
	this.sortMode = null;
	this.curCol = null;
	this.curTH = null;

	this.init = function (table_obj)
	{
		var t = this;
		var node, walk = 0;
		this.table = table_obj;
		var theadCount = 0;

		while (t.thead = t.table.tHead.childNodes[theadCount])
		{
			if (t.thead.nodeType == 1)
			{
				break;
			}

			++theadCount;
		}

		for (var a = 0; (node = t.thead.childNodes[a]); ++a)
		{
			if (node.nodeType != 1)
			{
				continue;
			}
			
			if (walk == this.curCol)
				node.firstChild.className = this.sorting?"colUp":"colDown";
			
			node.onclick = function() 
			{
				t.curTH = this;
				t.processTriggers();
				t.sort(); 
			};
			
			walk++;
		}
	}

	function nodeExtractor (node) 
	{
		var _result = "";
		
		if (node == null) 
		{
			return _result;
		}
		
		var childrens = node.childNodes;
		var i = 0;

		while (i < childrens.length)
		{
			var child = childrens.item(i);
			switch (child.nodeType) 
			{
				case 1: // ELEMENT_NODE
				case 5: // ENTITY_REFERENCE_NODE
					_result += nodeExtractor (child);
					break;
				case 3: // TEXT_NODE
				case 2: // ATTRIBUTE_NODE
				case 4: // CDATA_SECTION_NODE
					_result += child.nodeValue;
					break;
				case 6: // ENTITY_NODE
				case 7: // PROCESSING_INSTRUCTION_NODE
				case 8: // COMMENT_NODE
				case 9: // DOCUMENT_NODE
				case 10: // DOCUMENT_TYPE_NODE
				case 11: // DOCUMENT_FRAGMENT_NODE
				case 12: // NOTATION_NODE
				// skip
				break;
			}
			i++;
		}
		return _result;
	}
	
	this.numAsc = function (a, b)
	{
		//console.log ("numsort: "+a+" "+b);
		return a - b;
	}

	this.alphaAsc = function (a, b)
	{
		a = a.toLowerCase().replace(/^\s+|\s+$/g, '');
		b = b.toLowerCase().replace(/^\s+|\s+$/g, '');

		if (a > b)
		{
			return -1;
		} else if (a < b)
		{
			return 1;
		} else {
			return 0;
		}
	}
		
	this.how2sort = function (a, b, t)
	{
		a = String (a[0]).replace (/[^0-9a-zа-яё]/i, '');
		b = String (b[0]).replace (/[^0-9a-zа-яё]/i, '');

		a = a.replace (/\{.*\}/i, '');
		b = b.replace (/\{.*\}/i, '');

		if (a === "") a = String (0);
		if (b === "") b = String (0);
		
		if ( isNaN (parseInt (a)) || isNaN (parseInt (b)) )
		{
			a = String (a).replace (/,/, '.');
			b = String (b).replace (/,/, '.');
			
			return t.alphaAsc (a, b);
		} else {
			return t.numAsc (parseInt(a), parseInt(b));
		}
	}

	function toggleClass (classString, toggleClass, newClassValue)
	{
		var re = new RegExp (toggleClass);
		
		if (classString.match (re, classString))
		{
			if (newClassValue)
			{
				classString = classString.replace (re, newClassValue);
			} else {
				classString = classString.replace (re, toggleClass);
			}
		} else {
			classString += (" " + toggleClass);
		}
		
		return classString;
	}

	function getTableTitle (obj)
	{
		var srch = obj.getElementsByTagName ("div");
		var re = new RegExp (/tableText/);

		for (var i=0; srch.length > i; ++i)
		{
			if (srch[i].className.match (re))
			{
				return srch[i];
			}
		}
		
		return false;
	}

	this.processTriggers = function ()
	{
		this.sorting = !this.sorting;
		var el = this.curTH;
		
		el = getTableTitle (el);

		if (!el)
		{
			this.curTH = null;
			return;
		}

		var name = el.innerHTML, dad = el.parentNode.parentNode;
		
		for (var i = 0; (node = dad.getElementsByTagName("th").item(i)); ++i) 
		{
			var tmp = getTableTitle (node);			
			
			if (!tmp)
				continue;

			tmp.cssText = "cursor:pointer;padding-left:15px";

			if (tmp.innerHTML == name)
			{
				this.curCol = i;
				
				if (tmp.className.match (/colDown/) || tmp.className == "tableText")
				{
					tmp.className = toggleClass (tmp.className, "colDown", "colUp");
				} else if (tmp.className.match (/colUp/)) {
					
					tmp.className = toggleClass (tmp.className, "colUp", "colDown");
				}

			} else {
				tmp.className = "tableText";
			}
			
			tmp = null;
		}
	}

	this.sort = function()
	{
		if (this.curTH === null)
			return;

		var t = this;
		var el = this.curTH;
		
		var a = [];
		var name = el.firstChild.innerHTML;

		var dad = el.parentNode;
		var table = this.table;
		var tbody = table.tBodies[0];
		var procnode;
		
		for (var i = 0; (node = tbody.getElementsByTagName ("tr").item(i)); ++i) 
		{
			procnode = node.getElementsByTagName ("td");
			
			a[i] = [];
			a[i][0] = nodeExtractor (procnode[this.curCol]);
			a[i][1] = nodeExtractor (procnode[1]);
			a[i][2] = nodeExtractor (procnode[0]);
			a[i][3] = node;
			
		}
		
		a.sort ( function (a, b) { return t.how2sort (a, b, t) } );
		
		if (t.sorting) 
		{
			a.reverse();
		}
		
		var iter = 0;
		var strs = [];
		var idd = 0;
		
		for (var i = 0; i < a.length; ++i) 
		{
			if (a[i][3].className !== "")
			{
				strs = a[i][3].className.split(' ');
				
				if ((idd = strs.indexOf ("odd")) != -1)
				{
					strs.splice (idd, 1);
				}
			}
			
			if ((iter % 2) > 0)
			{
				strs.push ("odd");
			}
			++iter;
			
			a[i][3].className = strs.join (' ');
			
			tbody.appendChild(a[i][3]);
			strs = [];
		}
	}

	this.init (table_obj);
}

var sortInit = 
{
	initedTables: new Array(),

	init:function()
	{
		var self = this;

		try 
		{
			window.addEventListener ("load", function() {self.dom_init()}, false);
		} catch(e) {
			window.onload = function() {self.dom_init()};
		}
	},

	dom_init:function()
	{
		var tables = document.getElementsByTagName ("table");
		var tmp;

		for (var i=0; tmp=tables[i]; ++i)
		{
			if (tmp.className != "sortable")
				continue;

			this.initedTables.push (new sorting (tmp));
		}
	}
}

sortInit.init();