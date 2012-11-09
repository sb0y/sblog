$(function() {
	
	$(".loginButton").bind ("click", function() 
	{
		var popup = new Popup;
		popup.showWindow ("login");
	});

	$("#search").example ("Поиск ...");
});