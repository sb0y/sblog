function showAddCatForm (p)
{
	var jp = $(p);
	var newCatInput = jp.find ("#newCatInput");
	var a = jp.find ("a");

	newCatInput.toggle ("slow");

	if (newCatInput.css ("display") == "inline")
	{
		a.text ("Я передумал");
	} else a.text ("Добавить новую категорию");
}

function addNewCat (catName, catSlug, callOnSuccess)
{
	var postData = {"catName":catName, "catSlug":catSlug};

	$.post("/adm/blog/addNewCat", postData, function (data) 
	{
		var res = $.trim(data);
		res = res.split ('|');

		if ($.trim(res[0]) == "Ok")
		{
			
			if (typeof callOnSuccess == "function")
				callOnSuccess (parseInt (res[1]));
				
		} else alert ("Ошибка!\n\n"+data);
	});
}
