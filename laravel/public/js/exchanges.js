// tab
$(function(){
	$(".exchanges__select__tab a").click(function(){
		$(this).parent().addClass("active").siblings(".active").removeClass("active");
		var exchanges__select = $(this).attr("href");
		$(exchanges__select).addClass("active").siblings(".active").removeClass("active");
		return false;
	});
});
