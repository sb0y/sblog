$(function() 
{
	$(".file").bind ("change", function()
	{
		alert ("hre");
		$.ajaxFileUpload
		(
			{
				url:"/adm/ajax/file", 
				secureuri:false,
				fileElementId:"picUpld",
				dataType: "json",
				success: function (data, status)
				{
					if (typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert (data.error);
						} else {
							alert (data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
	});
});