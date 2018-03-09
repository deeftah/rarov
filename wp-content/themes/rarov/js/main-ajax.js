/*(function($){
	$(document).on('click', '#secondary ul li a', function(event) {
		event.preventDefault();
		var id = parseInt($(this).parents('li').attr('class').replace(/\D+/g,""));
		var templateUrl = ajaxglobal.templateUrl;
		$.ajax({
			url: ajaxglobal.ajaxurl,
			type: 'post',
			data: {
        'action': 'ajax_cat',
        'cat_id': id
      },
			dataType: 'html',
			success: function (result) {
				$('#response').html(" ");
				$('#response').html(result);
				$('.wp-pagenavi a.page.larger').each(function() {
					$(this).attr('href', $(this).attr('href').replace(templateUrl+'/wp-admin/admin-ajax.php?paged=', templateUrl+'/page/' ));
				});
			}
		})	
	});

})(jQuery);*/