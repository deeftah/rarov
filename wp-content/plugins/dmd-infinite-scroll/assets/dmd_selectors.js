(function ($) {
	var dmd_position = 1;
	var current_block = $('<div></div>');
	var products_selector = '', product_selector = '', pagination_selector = '', next_selector = '', prev_selector = '', pages_selector = '';
	$(document).ready(function () {
		$('body').append('<div class="dmd_selectors_info"><div class="dmd_selectors_text">Select products block, that contains all products. By default: ul.products</div><div class="dmd_current_selector"><input type="text"></div><a class="dmd_parent">Parent</a><a class="dmd_next_element_sel">Apply</a></div>');
	});
	$(document).on('click', 'a', function(event) {
		event.preventDefault();
	});
	$(document).on('click', '*', function(event) {
		event.preventDefault();
		event.stopPropagation();
		if( ! $(this).is('.dmd_selectors_info') && $(this).parents('.dmd_selectors_info').length == 0 ) {
			current_block.css('border', '');
			current_block = $(this);
			dmd_current_selector();
		}
	});
	$(document).on('click', '.dmd_parent', function(event) {
		if(! current_block.is('body')) {
			current_block.css('border', '');
			current_block = current_block.parent();
			dmd_current_selector();
		}
	});
	function dmd_current_selector() {
		var classes = current_block.attr('class');
		if( typeof classes == 'undefined' ) {
			classes = current_block.prop("tagName").toLowerCase();
		} else {
			classes = current_block.prop("tagName").toLowerCase()+'.'+classes.replace(/\s+/g,'.');
			if( dmd_position == 2 ) {
				classes = classes.replace(/\.first/g,'');
				classes = classes.replace(/\.last/g,'');
				classes = classes.replace(/\.sale/g,'');
				classes = classes.replace(/\.featured/g,'');
				classes = classes.replace(/\.instock/g,'');
				classes = classes.replace(/\.outofstock/g,'');
				classes = classes.replace(/\.downloadable/g,'');
				classes = classes.replace(/\.virtual/g,'');
				classes = classes.replace(/\.sold-individually/g,'');
				classes = classes.replace(/\.taxable/g,'');
				classes = classes.replace(/\.shipping-taxable/g,'');
				classes = classes.replace(/\.purchasable/g,'');
				classes = classes.replace(/\.has-default-attributes/g,'');
				classes = classes.replace(/\.has-children/g,'');
				classes = classes.replace(/\.post-\d+/g,'');
				classes = classes.replace(/\.product-type-.+?(\.|$)/g,'$1');
			}
		}
		$('.dmd_current_selector input').val(classes);
		current_block.attr('style', 'border: 2px solid red !important;'+current_block.attr('style'));
	}
	$(document).on('click', '.dmd_next_element_sel', function() {
		if( dmd_position == 1 ) {
			products_selector = $('.dmd_current_selector input').val();
			dmd_position = 2;
			$('.dmd_selectors_text').text('Product selector. Please remove specific classes. By default: .product');
		} else if( dmd_position == 2 ) {
			product_selector = $('.dmd_current_selector input').val();
			dmd_position = 3;
			$('.dmd_selectors_text').text('Pagination selector. Block to select pages. By default: .woocommerce-pagination');
		} else if( dmd_position == 3 ) {
			pagination_selector = $('.dmd_current_selector input').val();
			dmd_position = 4;
			$('.dmd_selectors_text').text('Next page link. By default: a.next');
		} else if( dmd_position == 4 ) {
			next_selector = $('.dmd_current_selector input').val();
			dmd_position = 5;
			$('.dmd_selectors_text').text('Previous page link. By default: a.prev');
		} else if( dmd_position == 5 ) {
			prev_selector = $('.dmd_current_selector input').val();
			dmd_position = 6;
			$('.dmd_selectors_text').text('Any other pages link. By default: a');
		} else if( dmd_position == 6 ) {
			pages_selector = $('.dmd_current_selector input').val();
			dmd_position = 7;
			$.post(dmd_is_data.ajaxurl, {action: 'save_dmd_selectors', products: products_selector, product: product_selector, pagination: pagination_selector, next: next_selector, prev: prev_selector, pages: pages_selector}, function(data){location = data;});
		}
		$('.dmd_current_selector input').val('');
		current_block = $('<div></div>');
	});
}(jQuery));