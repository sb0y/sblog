function DOMLevelUP ( node, lvl )
{
    var tmp = node.parentNode;
    while ( ( --lvl ) )
    {
        tmp = tmp.parentNode;
    }

    return tmp;
}

var buttonAnim = {

    data:{},

    process:function ( button, id )
    {
        this.data[id] = { "txt":button.innerHTML, "obj":button };

        button.innerHTML = "";
        button.style.backgroundImage = "url('"+urlBase+"resources/images/buttonProcess.gif')";
        button.style.backgroundRepeat = "no-repeat";
        button.style.backgroundPosition = "center center";
        button.blur();
    },

    finish:function ( id )
    {
        this.data[id].obj.style.backgroundImage = "";
        this.data[id].obj.style.backgroundRepeat = "";
        this.data[id].obj.style.backgroundPosition = "";
        this.data[id].obj.innerHTML = this.data[id].txt;
        this.data[id] = null;
        delete this.data[id];
    }
}

function suggestionLogin ( callback )
{
    var wnd = new Popup;

    if ( typeof callback == "function" )
        wnd.onLoaded = callback;

    wnd.showWindow ( "suggestionLogin" );
}

$(function() {
        $(document).on('focusin','input#search', function(){
    		$(this).animate({width: "350px"}, 300);
        });
        $(document).on('focusout','input#search', function(){
            $(this).animate({width: "100px"}, 300);
        });
        $(document).on('click','.video_nav a', function(){
            var $target = $('.'+$(this).attr('data-target'));
            if($target.length)
            {
                $target.html('<a href="/video/'+$(this).attr('data-id')+'" style="background: url(/content/videoPreview/'+$(this).attr('data-pic')+') no-repeat 0px -60px; width: 635px; height: 360px; display: block; "></a>')
                $('.video_nav a').removeClass('active');
            	$(this).addClass('active');
            }
        });

	$(".loginButton").bind ("click", function() 
	{
		var popup = new Popup;
		popup.showWindow ("login");
	});

	$("#search").example ("Что ищем?");

    $.jGrowl.defaults.closerTemplate = "<div>Закрыть все уведомления</div>";
});
