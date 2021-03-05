$(document).ready(function(){
	$('.contents').click(function(){
		$(this).parent().find('.user_content').toggle(300);
	});
	$("#div_admin").click(function(){
		if ($('.profile_admin').is(':visible'))
		{
			$(".profile_admin").slideUp("fast");
			
			
		}
		else
		{
			$(".profile_admin").slideDown("fast");
			
		}
	});
	$(document).click(function (e)
	{
	    var container = $("#div_admin");
	    //click ra ngoài đối tượng
	    if (!container.is(e.target) && container.has(e.target).length === 0)
	    {
	        $(".profile_admin").slideUp("fast");
	    }
	});
});
