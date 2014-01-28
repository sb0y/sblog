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
		if (catName === null)
			catName = $(this.parentNode).find ("#catName");
		
		if (catSlug === null)
			catSlug = $(this.parentNode).find ("#catSlug");

		addNewCat (catName.val(), catSlug.val(), function (newСatID)
		{
			// showAddCatForm (t.parentNode.parentNode);

			var output = [];
			var select = $(t.parentNode.parentNode).find ("select#catSelect");

			select.find("option").each (function (key, value)
			{
			 	output.push ('<option value="'+ value.value +'">'+ value.innerHTML +'</option>');
			});

			output.push ('<option selected value="'+ newСatID + '">' + catName.val() + '</option>');

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


	var $datepicker = $('input.dt-pic').pikaday({
        firstDay: 1,
        minDate: new Date('2000-01-01'),
        maxDate: new Date('2020-12-31'),
        format: 'DD-MM-YYYY',
        i18n: {
            previousMonth : 'Пред. месяц',
            nextMonth     : 'След. месяц',
            months        : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            weekdays      : ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
            weekdaysShort : ['Вс','Пн','Вт','Ср','Чт','Пт','Сб']
        },

        yearRange: [2000,2020]
    });

	$datepicker.pikaday('hide');
	$("#showCategoryEntry").bind ( "click", function() { $("#newCatInput").toggle ( "slow" ); } );
	$(".addPicLink").bind ( "click", function() 
	{ 
		addPictureByUrl ( this );
	} );

	$(".deletePosterLink").bind ("click", function()
	{
		deletePoster ( this.id, this );
	});

	var $postShortObj = $("#post-admin-body-short");
	showSymbLeft ( $postShortObj.get ( 0 ) );
	$postShortObj.bind ("keyup change keydown", function()
	{
		showSymbLeft ( this );
	});

});

function addNewCat (catName, catSlug, callOnSuccess)
{
	var postData = {"catName":catName, "catSlug":catSlug};
	$.post ( "addNewCat", postData, function ( data ) 
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

function deletePoster ( picID, object )
{
	var data = {"pictureDelete":picID, "picsDir":"posterImages", "dirName":picID};

	$.ajax({
		url: urlBase+"ajax/deletePoster",
		type: "POST",
		data: data,
		success: function (response)
		{
			if (response == "Ok")
			{
				var $obj = $(object).parent();
				$obj.empty();
				$obj.append ( $("<label for=\"poster\">Постер</label><span id=\"posterMsg\">Для данной статьи постер не выбран.</span><input id=\"posterUpld\" value=\"\" name=\"poster\" type=\"file\" class=\"file normal\" />") );
			}
		}
	});
}

function showSymbLeft ( obj )
{
	var highIter = 140;
	var symCount = obj.value.length;
	var $symbolsCount = $("#symbolsCount");

	if ( symCount <= 0 )
	{
		$symbolsCount.css ( "display", "none" );
		return;
	} else if ( symCount >= 1  ) {
		$symbolsCount.css ( "display", "block" );
	}

	$("#symbolCountInt").text ( highIter - symCount );
		
	if ( symCount >= highIter )
	{
		$symbolsCount.css ( "background", "#84001A" );
		$symbolsCount.css ( "color", "#FFFFFF" );
	
	} else if ( symCount >= ( highIter - 20 ) ) {
		
		$symbolsCount.css ( "background", "#E6DB6F" );
		$symbolsCount.css ( "color", "#000000" );
	
	} else {

		$symbolsCount.css ( "background", "#B6CF5F" );
		$symbolsCount.css ( "color", "#000000" );
	}
}