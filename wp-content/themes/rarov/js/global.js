(function($)
{$(document).ready(function(){
	var cart;

	/*$('.mid-menu .price #green-click').on('click', function() {

		if ($( '#green-click' ).prop( "checked" )) {
			$('.device').each(function() {
				$(this).css('border-color', 'green');
			});
		}
		else	{
			$('.device').each(function() {
				$(this).css('border-color', 'white');
			});
		}
	});*/

	/*$('.shop-menu .cart span').html(cart);
	$('.device .btn').on('click', function(e) {
		e.preventDefault();
		cart += parseFloat($(this).siblings('.d_price').text());
		$('.shop-menu .cart span').html(cart.toFixed(2));
	});*/

	$('#top_menu').on('click', function(){
	$('.var-menu').toggleClass('show');
	});


	$('.product-categories > li a').on('click', function(e){
		if ($(this).parent('li').siblings('li').children('ul').attr('style', 'display:block')) {
			$(this).parent('li').siblings('li').children('ul').removeAttr('style');
		}
		if ($(this).next('ul').hasClass('children')) {
			e.preventDefault();
			$(this).next('ul').toggle(400); 	
			//$(this).children('a').children('img').toggleClass('opened');
			$(this).next('ul').children('li').unbind('click');
		}

		
	});


	$('section.shop-menu .device p.color a').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
	});

	$('.slider').slick({
    'slidesToShow': 1,
    'slidesToScroll': 1,
    'arrows': true,
    'prevArrow': '.prev-block.arrows',
    'nextArrow': '.next-block.arrows',
     'autoplay': true,
  	' autoplaySpeed': 2000
  });
});

// $(document).ready(function(){

//         var $menu = $("#secondary");

//         $(window).scroll(function(){
//         		var $header = $('header').height() + $('#aws_widget-2').height() + 50;
//         		var $h = $('#page').height();
//         		var $item = $('div.device.b-color').parent('div').height() + 30;
// 		        var $ft = $('footer').height() + $item + 30;
// 		        var $h1 = $h - $ft;
// 		        console.log($item);
// 		        console.log($ft);
// 		        console.log($h1);
// 		        console.log($(this).scrollTop());
//             if ( $(this).scrollTop() > $header && $menu.hasClass("default") && $(this).scrollTop() < $h1) {
//                 $menu.removeClass("default").addClass("fixed");
//             } else if($(this).scrollTop() <= $header && $menu.hasClass("fixed") || $(this).scrollTop() >= $h1) {
//                 $menu.removeClass("fixed").addClass("default");
//             }
//         });//scroll
//     });


})(jQuery);
