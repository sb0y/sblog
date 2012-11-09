$(function() 
{
	var catName = null;
	var catSlug = null;

	$("#showCatEntry").bind ("click", function() 
	{
		if (catName === null)
			catName = $(this.parentNode).find ("#catName");
		
		if (catSlug === null)
			catSlug = $(this.parentNode).find ("#catSlug");

		catName.example ("Название категории");
		catSlug.example ("URL ссылки");

		showAddCatForm (this.parentNode);
	});

	$("#addCat").bind ("click", function()
	{
		var t = this;

		addNewCat (catName.val(), catSlug.val(), function (newСatID)
		{
			showAddCatForm (t.parentNode.parentNode);

			var output = [];
			var select = $(t.parentNode.parentNode).find ("select#catSelect");

			select.find("option").each (function (key, value)
			{
			 	output.push ('<option value="'+ value.value +'">'+ value.innerHTML +'</option>');
			});

			output.push ('<option selected value="'+ newСatID +'">'+ catName.val() +'</option>');

			select.html(output.join(''));

			catName.val ('');
			catSlug.val ('');

			alert ("Категория добавлена");
		});
	});

	$(".auto-select").bind ("click", function()
	{
		this.select();
	});

});
