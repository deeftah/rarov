var dmd_load_next_products, dmd_load_prev_products, dmd_load_products, dmd_try_load_products, dmd_reload_next_prev_pages;
(function ($) {
	var selectors_list = ['selector0'];
	var current_selector_name = 'shop';
	var ajax_request = false;
	var plugin_settings = dmd_is_data;
	var pages_links = [];
	var pages_links_list = {};
	var woocommerce_result = [{first:0, last:0}];
	dmd_try_load_products = function() {
		selectors_list.forEach(function(selector_list_el, selector_index) {
			if($(plugin_settings.selectors[selector_list_el].products_selector).length > 0) {
				if(plugin_settings.selectors[selector_list_el].replaced_setup.load_type == 'infinite_scroll') {
					var bottom_border = $(window).scrollTop() + $(window).height();
					var bottom_products = $(plugin_settings.selectors[selector_list_el].products_selector).offset().top + $(plugin_settings.selectors[selector_list_el].products_selector).height();
					bottom_products = bottom_products - plugin_settings.selectors[selector_list_el].infinite_scroll_threshold * 1;
					if(bottom_border > bottom_products && ! ajax_request)
					{
						set_new_selectors(selector_list_el);
						dmd_load_next_products();
					}
				}
				if(plugin_settings.selectors[selector_list_el].replaced_setup.previous_page_button && plugin_settings.selectors[selector_list_el].replaced_setup.previous_load_type == 'scroll') {
					if(plugin_settings.selectors[selector_list_el].replaced_setup.load_type == 'infinite_scroll' || plugin_settings.selectors[selector_list_el].replaced_setup.load_type == 'load_button') {
						var top_border = $(window).scrollTop();
						var top_products = $(plugin_settings.selectors[selector_list_el].products_selector).offset().top;
						top_products = top_products + plugin_settings.selectors[selector_list_el].infinite_scroll_threshold * 1;
						if(top_border < top_products && ! ajax_request)
						{
							set_new_selectors(selector_list_el);
							dmd_load_prev_products();
						}
					}
				}
			}
		});
	}
	function set_new_selectors(name) {
		current_selector_name = name;
		plugin_settings.current = plugin_settings.selectors[name];
		pages_links = pages_links_list[name];
	}
	dmd_load_next_products = function() {
		if( !ajax_request ) {
			if($(plugin_settings.current.next_page_selector).length > 0) {
				if(plugin_settings.current.replaced_setup.load_type == 'infinite_scroll' || plugin_settings.current.replaced_setup.load_type == 'load_button') {
					dmd_ajax_start('next');
					var next_page = pages_links[pages_links.length - 1].next;
					set_hide_lazy_load_animation();
					$.get(next_page, function(data) {
						dmd_replace_products(data, 'next');
						dmd_ajax_end(next_page);
					});
				}
			}
		}
	}
	dmd_load_prev_products = function() {
		if( !ajax_request ) {
			if($(plugin_settings.current.prev_page_selector).length > 0) {
				if(plugin_settings.current.replaced_setup.load_type == 'infinite_scroll' || plugin_settings.current.replaced_setup.load_type == 'load_button') {
					dmd_ajax_start('prev');
					var prev_page = pages_links[0].prev;
					set_hide_lazy_load_animation();
					$.get(prev_page, function(data) {
						dmd_replace_products(data, 'prev');
						dmd_ajax_end(prev_page);
					});
				}
			}
		}
	}
	dmd_load_products = function(url) {
		if( !ajax_request ) {
			dmd_ajax_start('next');
			set_hide_lazy_load_animation();
			$.get(url, function(data) {
				dmd_replace_products(data, '');
				dmd_ajax_end(url);
			});
		}
	}
	function set_hide_lazy_load_animation() {
		if( plugin_settings.current.replaced_setup.load_type == 'load_button' && plugin_settings.current.replaced_setup.lb_replace_products && ! plugin_settings.current.replaced_setup.lb_only_few_pages ) {
			$(plugin_settings.current.product_selector).addClass('animated').addClass(plugin_settings.current.replaced_setup.hide_lazy_load_anim)
			.removeClass(plugin_settings.current.replaced_setup.prev_lazy_load_anim).removeClass(plugin_settings.current.replaced_setup.lazy_load_anim);
		}
	}
	function dmd_replace_products(new_page, type) {
		var $new_page = $(new_page);
		if($new_page.find(plugin_settings.current.products_selector).length > 0) {
			$new_page.find(plugin_settings.current.product_selector).first().addClass('dmd_first_element');
			var current_last_position = false;
			if(type == 'next') {
				current_last_position = $(plugin_settings.current.product_selector).last().addClass('dmd_scroll_element').offset().top;
			} else if(type == 'prev') {
				current_last_position = $(plugin_settings.current.product_selector).first().addClass('dmd_scroll_element').offset().top;
			}
			var current_scroll_top = $(window).scrollTop();
			if( plugin_settings.current.replaced_setup.load_type == 'load_button' && plugin_settings.current.replaced_setup.lb_replace_products && ! plugin_settings.current.replaced_setup.lb_only_few_pages ) {
				type = '';
			}
			if( plugin_settings.current.replaced_setup.lazy_load ) {
				$new_page.find(plugin_settings.current.products_selector).find('img').each(function(i, o) {
					var src = $(o).attr('src');
					var srcset = $(o).attr('srcset');
					$(o).removeAttr('src').removeAttr('srcset').attr('data-src', src).attr('data-srcset', srcset).addClass('dmd_lazy_load');
				});
			}
			if(type == 'next') {
				$(plugin_settings.current.products_selector).append($new_page.find(plugin_settings.current.products_selector).html());
				$(plugin_settings.current.pagination_selector).html($new_page.find(plugin_settings.current.pagination_selector).html());
				add_page_to_links('next');
			} else if(type == 'prev') {
				$(plugin_settings.current.products_selector).prepend($new_page.find(plugin_settings.current.products_selector).html());
				$(plugin_settings.current.pagination_selector).html($new_page.find(plugin_settings.current.pagination_selector).html());
				add_page_to_links('prev');
			} else {
				$(plugin_settings.current.products_selector).html($new_page.find(plugin_settings.current.products_selector).html());
				$(plugin_settings.current.pagination_selector).html($new_page.find(plugin_settings.current.pagination_selector).html());
				dmd_reload_next_prev_pages();
				if( plugin_settings.current.scroll_top ) {
					$(window).scrollTop( $(plugin_settings.current.products_selector).offset().top + plugin_settings.current.scroll_top_threshold * 1 );
				}
			}
			if( plugin_settings.current.replaced_setup.lazy_load ) {
				$('.dmd_lazy_load').lazyLoadXT();
				var lazy_load_anim = plugin_settings.current.replaced_setup.lazy_load_anim;
				if(type == 'prev') {
					lazy_load_anim = '';
					lazy_load_anim = plugin_settings.current.replaced_setup.prev_lazy_load_anim;
				}
				$('.dmd_lazy_load').on('lazyload', function() {
					if( $(this).data('srcset') ) {
						$(this).attr('srcset', $(this).data('srcset'));
					}
				});
				var product_selector = plugin_settings.current.product_selector;
				if( lazy_load_anim ) {
					$('.dmd_lazy_load').parents(product_selector).css('opacity', 0);
				}
				$('.dmd_lazy_load').on('lazyshow', function() {
					if( lazy_load_anim ) {
						$(this).parents(product_selector).addClass(lazy_load_anim).addClass('animated');
						$(this).parents(product_selector).css('opacity', '');
					}
				});
				$('.dmd_lazy_load').removeClass('dmd_lazy_load');
			}
			var other_selectors = plugin_settings.current.other_selectors;
			if( other_selectors ) {
				var array_selectors = other_selectors.split(',');
				array_selectors.forEach(function(element, index) {
					$(element).replaceWith($new_page.find(element));
				});
			}
			var only_few_page = false;
			var only_few_page_count = 1;
			if(plugin_settings.current.replaced_setup.load_type == 'infinite_scroll') {
				only_few_page = plugin_settings.current.replaced_setup.is_only_few_pages;
				only_few_page_count = plugin_settings.current.replaced_setup.is_few_page_count;
			} else if(plugin_settings.current.replaced_setup.load_type == 'load_button') {
				only_few_page = plugin_settings.current.replaced_setup.lb_only_few_pages;
				only_few_page_count = plugin_settings.current.replaced_setup.lb_few_page_count;
			}
			var pr_count = 0;
			only_few_page_count = only_few_page_count * 1;
			if( only_few_page ) {
				$(plugin_settings.current.product_selector).each(function(i, o) {
					if($(o).is('.dmd_first_element')) {
						pr_count++;
					}
				});
				if( pr_count > ( only_few_page_count + 1 ) ) {
					pr_count = 0;
					if( type == 'next' ) {
						pages_links.shift();
						if( plugin_settings.current.wc_result_count && $(plugin_settings.current.wc_result_count).length > 0 ) {
							woocommerce_result.shift();
						}
					} else if( type == 'prev' ) {
						pages_links.pop();
						if( plugin_settings.current.wc_result_count && $(plugin_settings.current.wc_result_count).length > 0 ) {
							woocommerce_result.pop();
						}
					}
					$(plugin_settings.current.product_selector).each(function(i, o) {
						if($(o).is('.dmd_first_element')) {
							pr_count++;
						}
						if( type == 'next' ) {
							if(pr_count == 1) {
								$(o).remove();
							}
						} else if( type == 'prev' ) {
							if( pr_count > (only_few_page_count + 1) ) {
								$(o).remove();
							}
						}
					});
				}
			}
			if( plugin_settings.current.wc_result_count && $(plugin_settings.current.wc_result_count).length > 0 ) {
				var first = 0, last = 0;
				var text_count = '';
				if( $('.dmd_product_result_count').length > 0 ) {
					$('.dmd_product_result_count').replaceWith($new_page.find('.dmd_product_result_count'));
					first = $('.dmd_product_result_count').data('first');
					last = $('.dmd_product_result_count').data('last');
					text_count = $('.dmd_product_result_count').data('text');
				}
				if(type == 'next') {
					woocommerce_result.push({first:first, last:last});
				} else if(type == 'prev') {
					woocommerce_result.unshift({first:first, last:last});
				} else {
					woocommerce_result = [{first:first, last:last}];
				}
				first = 0;
				last = 0;
				woocommerce_result.forEach( function(element, index, array) {
					if( first == 0 ) {
						first = element.first;
					}
					last = element.last;
				});
				text_count = text_count.replace('-1', first);
				text_count = text_count.replace('-2', last);
				$(plugin_settings.current.wc_result_count).text(text_count);
			}
			if( current_last_position !== false && $('.dmd_scroll_element').length > 0 ) {
				var new_last_position = $('.dmd_scroll_element').offset().top;
				var scroll_position = new_last_position - current_last_position;
				$(window).scrollTop(current_scroll_top + scroll_position);
				$('.dmd_scroll_element').removeClass('dmd_scroll_element');
			}
		}
	}
	function dmd_ajax_start(type) {
		if( plugin_settings.js_before_load ) {
			eval(plugin_settings.js_before_load);
		}
		var current_scroll_top = $(window).scrollTop();
		var current_last_position = $(plugin_settings.current.product_selector).first().addClass('dmd_scroll_element').offset().top;
		ajax_request = true;
		if( type == 'next' ) {
			$(plugin_settings.current.products_selector).after('<div class="dmd_ajax_product_load"><img src="'+plugin_settings.load_image+'" alt="Products loading"></div>');
		} else if( type == 'prev' ) {
			$(plugin_settings.current.products_selector).before('<div class="dmd_ajax_product_load"><img src="'+plugin_settings.load_image+'" alt="Products loading"></div>');
		}
		if(plugin_settings.current.replaced_setup.load_type == 'pagination') {
			$(plugin_settings.current.pagination_selector).hide();
		}
		var new_last_position = $('.dmd_scroll_element').offset().top;
		var scroll_position = new_last_position - current_last_position;
		$(window).scrollTop(current_scroll_top + scroll_position);
		$('.dmd_scroll_element').removeClass('dmd_scroll_element');
	}
	function dmd_ajax_end(dmd_url_load) {
		var current_scroll_top = $(window).scrollTop();
		var current_last_position = $(plugin_settings.current.product_selector).first().addClass('dmd_scroll_element').offset().top;
		$('.dmd_ajax_product_load').remove();
		ajax_request = false;
		if(plugin_settings.current.replaced_setup.load_type == 'pagination') {
			$(plugin_settings.current.pagination_selector).show();
		}
		var change_history = true;
		var currentState = history.state;
		if( typeof currentState == 'object' && currentState != null ) {
			if( typeof currentState.dmd_url != 'undefined' ) {
				if( currentState.dmd_url == dmd_url_load ) {
					change_history = false
				}
			}	
		}
		if( change_history ) {
			history.pushState({dmd_url: dmd_url_load}, document.title, dmd_url_load);
		}
		var new_last_position = $('.dmd_scroll_element').offset().top;
		var scroll_position = new_last_position - current_last_position;
		$(window).scrollTop(current_scroll_top + scroll_position);
		$('.dmd_scroll_element').removeClass('dmd_scroll_element');
		load_next_prev_buttons();
		if( plugin_settings.js_after_load ) {
			eval(plugin_settings.js_after_load);
		}
	}
	dmd_reload_next_prev_pages = function() {
		pages_links = [];
		pages_links_list = {};
		var old_selector = current_selector_name;
		selectors_list.forEach(function(selector_list_el, selector_index) {
			pages_links_list[selector_list_el] = [];
			set_new_selectors(selector_list_el);
			add_page_to_links('next');
			$(plugin_settings.current.product_selector).first().addClass('dmd_first_element');
		});
		current_selector_name = old_selector;
		set_new_selectors(current_selector_name);
	}
	function add_page_to_links(type) {
		var prev_p = '';
		var next_p = '';
		if($(plugin_settings.current.prev_page_selector).length > 0) {
			prev_p = $(plugin_settings.current.prev_page_selector).attr('href');
		}
		if($(plugin_settings.current.next_page_selector).length > 0) {
			next_p = $(plugin_settings.current.next_page_selector).attr('href');
		}
		var page_p = {prev: prev_p, next: next_p};
		if( type == 'next' ) {
			pages_links.push(page_p);
		} else if( type == 'prev' ) {
			pages_links.unshift(page_p);
		}
		pages_links_list[current_selector_name] = pages_links;
	}
	function load_next_prev_buttons() {
		$('.dmd_next_page').remove();
		$('.dmd_previous_page').remove();
		var old_selector = current_selector_name;
		selectors_list.forEach(function(selector_list_el, selector_index) {
			set_new_selectors(selector_list_el);
			if(plugin_settings.current.replaced_setup.load_type == 'load_button') {
				if( pages_links[pages_links.length - 1].next ) {
					$(plugin_settings.current.products_selector).after('<div class="dmd_next_page"><a data-selector="'+selector_list_el+'" class="button" href="'+pages_links[pages_links.length - 1].next+'">Next page</a></div>');
				}
			}
			if(plugin_settings.current.replaced_setup.load_type == 'load_button' || plugin_settings.current.replaced_setup.load_type == 'infinite_scroll') {
				if(plugin_settings.current.replaced_setup.previous_page_button && plugin_settings.current.replaced_setup.previous_load_type == 'button') {
					if(pages_links[0].prev) {
						$(plugin_settings.current.products_selector).before('<div class="dmd_previous_page"><a data-selector="'+selector_list_el+'" class="button" href="'+pages_links[0].prev+'">Previous page</a></div>');
					}
				}
			}
		});
		current_selector_name = old_selector;
		set_new_selectors(current_selector_name);
	}
	$(document).ready(function () {
		selectors_list = plugin_settings.selectors_names;
		selectors_list.forEach(function(selector_list_el, selector_index) {
			if(plugin_settings.selectors[selector_list_el].mobile_other) {
				if( $(window).width() <= plugin_settings.selectors[selector_list_el].mobile.mobile_maximum_width ) {
					plugin_settings.selectors[selector_list_el].replaced_setup = plugin_settings.selectors[selector_list_el].mobile;
				}
			}
			set_new_selectors(selector_list_el);
			if( plugin_settings.current.replaced_setup.load_type == 'infinite_scroll' || plugin_settings.current.replaced_setup.load_type == 'load_button' ) {
				var html = '<div class="dmd_hide_pagination"><style>'+plugin_settings.current.pagination_selector+'{display:none!important;}</style></div>';
				$('body').append($(html));
			} else {
				$('.dmd_hide_pagination').remove();
			}
		});
		dmd_reload_next_prev_pages();
		dmd_try_load_products();
		$(document).scroll(dmd_try_load_products);
		var old_selector = current_selector_name;
		selectors_list.forEach(function(selector_list_el, selector_index) {
			set_new_selectors(selector_list_el);
			if( plugin_settings.current.pages_selector ) {
				$(document).on('click', plugin_settings.current.pages_selector, function(event) {
					set_new_selectors(selector_list_el);
					event.preventDefault();
					dmd_load_products($(this).attr('href'));
				});
			}
		});
		current_selector_name = old_selector;
		set_new_selectors(current_selector_name);
		load_next_prev_buttons();
		$(window).on('popstate', function(event) {
			var state = event.originalEvent.state;
			if( typeof state == 'object' && state != null && typeof(state.dmd_url) != 'undefined' ) {
				location.reload();
			}
		});
		if( $('.dmd_product_result_count').length > 0 ) {
			woocommerce_result[0].first = $('.dmd_product_result_count').data('first');
			woocommerce_result[0].last = $('.dmd_product_result_count').data('last');
		}
	});
	$(document).on('click', '.dmd_previous_page a', function(event) {
		set_new_selectors($(this).data('selector'));
		event.preventDefault();
		dmd_load_prev_products();
	});
	$(document).on('click', '.dmd_next_page a', function(event) {
		set_new_selectors($(this).data('selector'));
		event.preventDefault();
		dmd_load_next_products();
	});
}(jQuery));