function select()
{
	var isAllSelected = false;
	var form = $("#groupActionsForm");
	var deleteAllButton = $("#deleteAllButton");
	var selectHolder = form.find ("div#selectHolder");
	var groupActions = form.find ("select#groupActions");

	groupActions.bind ("click", function()
	{
		var sel = $(this).find ("option:selected");

		if (sel.prop ("id") == "deleteAllButton")
		{
			if (confirm ("Вы уверены?")) 
			{
				form.submit();
			}
		}
	});

	this.selRow = function (row)
	{
		$(row).toggleClass ("selected");
		var id = row.getAttribute ( "data-userid" );
		var selected = selectHolder.find ("input#"+id);

		if (selected.length <= 0)
			this.appendToForm (id);
		else selected.remove();

		var action = true;
		$(".postRow").each (function()
		{
			if ($(this).hasClass("selected"))
			{
				action = false;
			}
		});

		deleteAllButton.prop ("disabled", action);
	}

	this.appendToForm = function (id)
	{
		var el = document.createElement ("input");
		el.type = "hidden";
		el.name = "rows[]";
		el.value = id;
		el.id = id;

		selectHolder.append ($(el));
	}

	this.selectAll = function()
	{
		var self = this;

		$(".postRow").each (function()
		{
			self.selRow (this);
		});

		deleteAllButton.prop ("disabled", isAllSelected);
		isAllSelected = !isAllSelected;
	}
}

$(function() 
{
	var sel = new select();
	$(".postRow").bind ("click", function()
	{
		sel.selRow (this);
	});

	$("#selectAll").bind ("click", function()
	{
		sel.selectAll();
	});
});