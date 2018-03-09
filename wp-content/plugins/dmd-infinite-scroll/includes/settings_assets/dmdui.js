var dmdui;
(function ($) {
	$(document).ready(function () {
		dmdui();
		$(document).on('click', '.dmduiElement.checkbox, .dmduiElement.radio', function(event) {
			event.preventDefault();
			$(this).prev().trigger('click');
		});
		$(document).on('click', '.dmduiElement.select', function(event) {
			event.preventDefault();
			event.stopPropagation();
			dmd_select_hide('none', $(this).find('ul'));
		});
		$(document).on('click', '.dmduiElement.select ul', function(event) {
			event.stopPropagation();
		});
		$(document).on('click', '.dmduiElement.select ul li', function(event) {
			event.stopPropagation();
			var select = $(this).parents('.dmduiElement.select');
			select.prev().val($(this).data('value')).trigger('change');
			select.find('span').text($(this).text());
			dmd_select_hide('hide', select.find('ul'));
		});
		$(document).on('click', function(event) {
			$('.dmduiElement.select ul').each(function(i, o)
			{
				dmd_select_hide('hide', $(o));
			});
		});
		$(document).on('click', '.dmd_settings .dmd_menu li', function(event)
		{
			$('.dmd_current').removeClass('dmd_current');
			$('.'+$(this).data('block')).addClass('dmd_current');
		});
		jQuery(document).on('click', '.dmd_style_box', function(event) {
			event.stopPropagation();
			var $styler = jQuery(this).parents('.dmd_styler');
			$styler.find('.dmd_style_show').removeClass('dmd_style_show');
			jQuery(this).addClass('dmd_style_show');
		});
		$(document).on('click', '.dmd_display_style', function(event){
			event.preventDefault();
			$('.'+$(this).data('name')).toggle();
			jQuery('.dmd_style_desc.center').each(function(i, o){
				jQuery(o).css('margin-left', -jQuery(o).width() / 2 );
			});
		});
		$(document).on('click', '.dmd_reset_style', function(event){
			event.preventDefault();
			$('.'+$(this).data('name')).find('input').val('');
		});
		jQuery('.dmd_style_desc.center').each(function(i, o){
			jQuery(o).css('margin-left', -jQuery(o).width() / 2 );
		});
		$('.dmd_color_picker').wpColorPicker();
		$('.dmd_color_picker').each(function(i, o) {
			$(o).parents('.wp-picker-container').attr('class', $(o).parents('.wp-picker-container').attr('class')+' '+$(o).attr('class'));
			$(o).parents('.wp-picker-container').removeClass('dmd_color_picker');
		});
	});
	dmdui = function () {
		$('.dmduiElement').remove();
		$('.dmdui').each(function( i, o ) {
			var $obj = $(o);
			if($obj.is('input[type=checkbox]')) {
				$obj.after('<span class="dmduiElement checkbox"></span>').css('display', 'none');
			} else if($obj.is('input[type=radio]')) {
				$obj.after('<span class="dmduiElement radio"></span>').css('display', 'none');
			} else if($obj.is('select')) {
				var replace = '<div class="dmduiElement select"><span>'+$obj.find('option:selected').text()+'</span><i class="fa fa-chevron-down"></i><ul class="dmd-hiden">';
				$obj.find('option').each(function(option_i, option_o) {
					var $option_o = $(option_o);
					replace += '<li data-value="'+$(option_o).val()+'">'+$(option_o).text()+'</li>';
				});
				replace += '</ul></div>';
				$obj.after(replace).css('display', 'none');
				dmd_select_hide('hide', $obj.next().find('ul'));
			}
		});
	}
	function dmd_select_hide(hide, $block)
	{
		if(($block.is('.dmd-hiden') && hide != 'hide') || hide == 'show')
		{
			dmd_select_hide('hide', jQuery('.dmduiElement.select ul'));
			$block.removeClass('dmd-hiden').addClass('dmd-show');
			$block.parents('.dmduiElement.select').find('.fa').removeClass('fa-chevron-down').addClass('fa-chevron-up');
		}
		else
		{
			$block.removeClass('dmd-show').addClass('dmd-hiden');
			$block.parents('.dmduiElement.select').find('.fa').removeClass('fa-chevron-up').addClass('fa-chevron-down');
		}
	}
	$(document).on('click', '.dmd_upload_image', function(event) {
		event.preventDefault();
		$button = $(this);
		var custom_uploader = wp.media({
			title: 'Select image',
			button: {
				text: 'Set image'
			},
			multiple: false 
		}).on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$button.prevAll(".dmd_image").html('<image src="'+attachment.url+'" alt="">');
			$button.prevAll(".dmd_input").find('input').val(attachment.url);
		}).open();
	});
	$(document).on('click', '.dmd_remove_image',function(event) {
		event.preventDefault();
		$(this).prevAll(".dmd_image").html('');
		$(this).prevAll(".dmd_input").find('input').val('').trigger('change');
	});
	$(document).on('click', '.dmd_save_and_reload',function(event) {
		event.preventDefault();
		$(this).parents('.dmd_setting_form').addClass('dmd_reload').trigger('submit');
	});
}(jQuery));