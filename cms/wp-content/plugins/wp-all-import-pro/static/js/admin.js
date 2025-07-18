/**
 * plugin admin area javascript
 */
(function($){$(function () {

	if ( ! $('body.wpallimport-plugin').length) return; // do not execute any code if we are not on plugin page

	$('.wp_all_import_send_to_codebox').on('click', async function () {

		var isCodeBoxActive = $('input[name="is_wp_codebox_active"]').val();

		if (isCodeBoxActive === '0') {

			$('.cross-sale-notice.codebox').slideDown();
		} else {

			var code = $('#wp_all_import_code').val();
			
			await wp_all_import_save_functions();

			$('.wp_all_import_functions_preloader').show();
			
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'wpai_send_to_codebox',
					security: wp_all_import_security,
					code: code
				},
				dataType: 'json',
				success: function (response) {
					$('#functions_editor_container').fadeOut(300, function () {
						const self = $(this);
						self.html(response.html).fadeIn(300);
						
						setTimeout(function () {
							self.fadeOut(400);
						}, 3000);
					});
					
					$('#wpai_function_editor_buttons').fadeOut(400);
					$('.wpai_go_to_codebox').fadeIn(400);
				},
				error: function () {
					alert('An error occurred while sending to CodeBox.');
				},
				complete: function () {
					$('.wp_all_import_functions_preloader').hide();
				}
			});
		}
	});

	$('.wp_all_import_revert_functions').on('click', function () {
		if (confirm('Are you sure you want to revert to the previous functions file?')) {

			$('.wp_all_import_functions_preloader').show();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'wpai_send_to_codebox',
					security: wp_all_import_security,
					codeboxaction: 'revert'
				},
				dataType: 'json',
				success: function (response) {
					alert(response.html);
					location.reload();
				},
				error: function () {
					alert('An error occurred while reverting the functions file.');
				},
				complete: function () {
					$('.wp_all_import_functions_preloader').hide();
				}
			});
		}
	});
	
	function overlayDivOverInput($input, divId) {
		const $localInput = $($input);

		// Get the name of the input/textarea
		const inputName = $input.attr('name');

		// Check if element is a textarea
		const isTextarea = $input.is('textarea');

		// Configure width value
		const width = isTextarea ? '55%' : '100%';

		// Apply position:relative to the parent of the input/textarea
		$localInput.parent().css({position: 'relative'});

		// Create the overlay div
		const $div = $('<div/>', {
			id: divId,
			'data-input-name': inputName,
			css: {position: 'absolute', top: 0, left: 0, 'z-index':-99},
			height:$localInput.outerHeight(),
			width: width
		});

		$div.insertAfter($input);

		// Returning the jQuery object
		return $div;
	}

	// Global declaration of $wpAllImportDrag and $wpAllImportOriginalColor so we can use them on dynamic elements.
	var $wpAllImportDrag = null;
	var $wpAllImportOriginalColor = '';

	function wpaiMakeDroppable(){
		let $targets = $('input, textarea');

		$targets.on('click', function (e) {
			if (!$wpAllImportDrag) return;

			let oldValue = $(this).val();
			let newValue = $wpAllImportDrag.data('xpath');
			$(this).val(oldValue + newValue);

			$wpAllImportDrag.css('color', $wpAllImportOriginalColor).css('font-weight', 'bold');
			$wpAllImportDrag = null;
		}).droppable({
			drop: function (event, ui) {
				let oldValue = $(this).val();
				let newValue = ui.draggable.data('xpath') || '';
				$(this).val(oldValue + newValue);
			},
			greedy: true,
			tolerance: 'touch',
			disabled: false
		});

	}

	function wpaiMakeDroppableTinyMce() {

		// Ensure tinymce is defined before use.
		if (typeof tinymce === 'undefined') {
			return;
		}

		// Get tinymce instance
		var ed = tinymce.get('content');

		// Function to get current iframe bounds.
		function getIframeBounds() {
			var tinymceIframe = $('#content_ifr');
			if (tinymceIframe.length === 0) {
				return null;
			}
			var iframeOffset = tinymceIframe.offset();
			return {
				top: iframeOffset.top - $(window).scrollTop(),
				left: iframeOffset.left - $(window).scrollLeft(),
				bottom: iframeOffset.top + tinymceIframe.height() - $(window).scrollTop(),
				right: iframeOffset.left + tinymceIframe.width() - $(window).scrollLeft()
			};
		}

		// Initial drop area coordinates.
		var dropArea = getIframeBounds();
		if (!dropArea) {
			return;
		}

		// Apply draggable to elements.
		$(".ui-draggable").draggable({
			helper: function () {
				return $('<div>').text($(this).data('xpath'));
			},
			start: function (event, ui) {
				// Create a duplicate node as a proxy
				proxy = ui.helper.clone().appendTo('body');
			},
			drag: function (event, ui) {
				// Recalculate drop area for iframe.
				dropArea = getIframeBounds();

				// Check if draggable is over the tinymce, update proxy to follow cursor and hide the helper
				if (
					event.clientX >= dropArea.left &&
					event.clientX <= dropArea.right &&
					event.clientY >= dropArea.top &&
					event.clientY <= dropArea.bottom
				) {
					proxy.css({ top: event.pageY, left: event.pageX });
					ui.helper.hide();
				} else {
					proxy.css({ top: 'auto', left: 'auto' });
					ui.helper.show();
				}
			},
			stop: function (event, ui) {
				// Recalculate drop area for iframe.
				dropArea = getIframeBounds();

				// Append to tinymce content if it was dropped on tinymce
				if (
					event.clientX >= dropArea.left &&
					event.clientX <= dropArea.right &&
					event.clientY >= dropArea.top &&
					event.clientY <= dropArea.bottom
				) {
					ed.setContent(
						ed.getContent() + $(this).data('xpath')
					);
				}
				// Clean up proxy and show the helper again
				proxy.remove();
				ui.helper.show();
			},
		});

		ed.on('click', function (e) {
			if (!$wpAllImportDrag) return;

			let oldValue = ed.getContent();
			let newValue = $wpAllImportDrag.data('xpath') || '';

			ed.setContent(oldValue + newValue);

			$wpAllImportDrag.css('color', $wpAllImportOriginalColor).css('font-weight', 'bold');
			$wpAllImportDrag = null;
		});
	}

	function wpaiMakeDroppableSingle($parent) {

		if($parent.hasClass('dragging')){
			return;
		}

		$parent.on('click', 'input, textarea', function (e) {
			if (!$wpAllImportDrag) return;

			let oldValue = $(this).val();
			let newValue = $wpAllImportDrag.data('xpath') || '';
			$(this).val(oldValue + newValue);

			$wpAllImportDrag.css('color', $wpAllImportOriginalColor).css('font-weight', 'bold');
			$wpAllImportDrag = null;
		});

		let $targets = $parent.find('input, textarea');
		let divCounter = 0; // counter to generate unique ids for divs

		$targets.each(function() {
			let $input = $(this);
			let divId = 'droppableDiv' + divCounter++; // generate a unique id based on the counter

			let $div = overlayDivOverInput($input, divId);

			// Apply jQuery UI droppable to the div
			$div.droppable({
				drop: function (event, ui) {
					let inputName = $(this).data('input-name');
					// Select only the closest sibling input/textarea with provided name
					let $inputOrTextarea = $(this).siblings("input[name='" + inputName + "'], textarea[name='" + inputName + "']").first();
					let oldValue = $inputOrTextarea.val();
					let newValue = ui.draggable.data('xpath') || '';
					$inputOrTextarea.val(oldValue + newValue);
				}
			});
		});

	}

	function wpaiObserveFieldAddition()
	{

		// Make drag and drop work for dynamically added elements.
		// Select the node that will be observed for child addition
		let wpaiXmlTargetNode = document.querySelector('.wpallimport-layout');

		if(!wpaiXmlTargetNode){
			return;
		}

		// Options for the observer (which mutations to observe)
		let wpaiXmlConfig = {childList: true, subtree: true};

		// Callback function to execute when mutations are observed
		let wpaiXmlCallback = function (mutationsList) {
			for (let mutation of mutationsList) {
				if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
					mutation.addedNodes.forEach((node) => {
						if (node.nodeType === Node.ELEMENT_NODE) {

								wpaiMakeDroppableSingle($(node));
								wpaiMakeDroppableTinyMce();

						}
					});
				}
			}
		};

		// Create an observer instance linked to the callback function
		let wpaiXmlObserver = new MutationObserver(wpaiXmlCallback);

		// Start observing the target node for configured mutations
		wpaiXmlObserver.observe(wpaiXmlTargetNode, wpaiXmlConfig);
	}

	wpaiObserveFieldAddition();

    function wpai_set_custom_select_image() {
        // The class name to add to the element.
        var class_name = jQuery('[class^="dd-selected-text dashicon"]').text().toLowerCase();
        class_name = class_name.replace( /\s+/g, '' );

        // This gets the image URL out of the class.
        var class_check = jQuery('[class^="dd-selected-text dashicon"]').attr( 'class' );
        class_check = class_check.replace( "dd-selected-text dashicon ", "" );

        // String of allowed images.
        var imgs = ['jpg','jpeg','jpe','gif','png','bmp'], length = imgs.length;
        while( length-- ) {
            if ( class_check.indexOf( imgs[ length ] ) != -1 ) {

                // They have defined an image URL, which means it's a custom image and we need to add the class.
                jQuery('[class^="dd-selected-text dashicon"]').addClass("wpaiimgfor" + class_name);
                jQuery('[class^="dd-selected-text dashicon"]').removeClass( class_check );

            }
        }
    }

    // Rapid Add-On API Images
    $(document).ready(function($){
        var class_check;
        var original_class;
        var new_class_name;
        var allstyles = "<style type='text/css'>";

        $.each($('[class^="dd-option-text"]'), function(key, value) {
            class_check = $(this).attr('class');
            if ( class_check.includes( 'dashicon' ) ) {

                // Grab the URL to the image by removing the other classes out of the string.
                class_check = class_check.replace( "dd-option-text dashicon ", "" );

                // Build the class name that we need to append to head.
                new_class_name = $(this).text().toLowerCase();
                new_class_name = new_class_name.replace( /\s+/g, '' );

                var imgs = ['jpg','jpeg','jpe','gif','png','bmp'],
                length = imgs.length;
                while( length-- ) {
                    if ( class_check.indexOf( imgs[ length ] ) != -1 ) {
                        // They've defined a custom image URL, so we need to append the class to the head and add it to the list item.
                        allstyles = allstyles + ".wpaiimgfor" + new_class_name + ":before { font-family: 'dashicons'; font-size: 24px; float: left; margin: 2px 5px 2px 2px;background-image: url(" + class_check + "); background-repeat: no-repeat; background-position: center center; content:'';height: 25px;width:24px; }";
                        allstyles = allstyles + "label.dd-option-text.dashicon.wpaiimgfor" + new_class_name + " { top: 2px !important; }";
                        $(this).addClass("wpaiimgfor" + new_class_name);
                        $(this).removeClass( class_check );
                    }
                }
            }
        });
        // Append all of the classes to head.
        allstyles = allstyles + "</style>";
        $( allstyles ).appendTo("head");
    });

	// fix wpallimport-layout position
	setTimeout(function () {
		$('table.wpallimport-layout').length && $('table.wpallimport-layout td.left h2:first-child').css('margin-top',  $('.wrap').has('.wpallimport-layout').offset().top - $('table.wpallimport-layout').offset().top);
	}, 10);

	// help icons
	$(document).tipsy({
		gravity: function() {
			var ver = 'n';
			if ($(document).scrollTop() < $(this).offset().top - $('.tipsy').height() - 2) {
				ver = 's';
			}
			var hor = '';
			if ($(this).offset().left + $('.tipsy').width() < $(window).width() + $(document).scrollLeft()) {
				hor = 'w';
			} else if ($(this).offset().left - $('.tipsy').width() > $(document).scrollLeft()) {
				hor = 'e';
			}
	        return ver + hor;
	    },
		live: 'a.wpallimport-help, .scheduling-help',
		html: true,
		opacity: 1
	}).each(function () { // fix tipsy title for IE
		$(this).attr('original-title', $(this).attr('title'));
		$(this).removeAttr('title');
	});

    // help icons
    $('.scheduling-disabled').parent().tipsy({
        gravity: function() {
            var ver = 'n';
            if ($(document).scrollTop() < $(this).offset().top - $('.tipsy').height() - 2) {
                ver = 's';
            }
            var hor = '';
            if ($(this).offset().left + $('.tipsy').width() < $(window).width() + $(document).scrollLeft()) {
                hor = 'w';
            } else if ($(this).offset().left - $('.tipsy').width() > $(document).scrollLeft()) {
                hor = 'e';
            }
            return ver + hor;
        },
        live: '.scheduling-disabled',
        html: true,
        delayOut: 2000,
        opacity: 1,
		trigger: "click"
    });

	// swither show/hide logic
	$('input.switcher').on("change", function (e) {
		if ($(this).is(':radio:checked')) {
			$(this).parents('form').find('input.switcher:radio[name="' + $(this).attr('name') + '"]').not(this).trigger('change');
		}
		let $targets = $('.switcher-target-' + $(this).attr('id'));
		let is_show = $(this).is(':checked'); if ($(this).is('.switcher-reversed')) is_show = ! is_show;
		if (is_show) {
			$targets.slideDown();
		} else {
			$targets.slideUp().find('.clear-on-switch').add($targets.filter('.clear-on-switch')).val('');
		}
	}).trigger('change');

	// swither show/hide logic
	$('input.switcher-horizontal').on('change', function (e) {
		if ($(this).is(':checked')) {
			$(this).parents('form').find('input.switcher-horizontal[name="' + $(this).attr('name') + '"]').not(this).trigger('change');
		}
		let $targets = $('.switcher-target-' + $(this).attr('id'));
		let is_show = $(this).is(':checked'); if ($(this).is('.switcher-reversed')) is_show = ! is_show;
		if (is_show) {
			$targets.animate({width:'205px'}, 350);
		} else {
			$targets.animate({width:'0px'}, 1000).find('.clear-on-switch').add($targets.filter('.clear-on-switch')).val('');
		}
	}).trigger('change');

	// autoselect input content on click
	$(document).on('click', 'input.selectable', function () {
		$(this).select();
	});

	// input tags with title
	$('input[title]').each(function () {
		var $this = $(this);
		$this.on('focus', function () {
			if ('' == $(this).val() || $(this).val() == $(this).attr('title')) {
				$(this).removeClass('note').val('');
			}
		}).on('blur', function () {
			if ('' == $(this).val() || $(this).val() == $(this).attr('title')) {
				$(this).addClass('note').val($(this).attr('title'));
			}
		}).trigger('blur');
		$this.parents('form').on('submit', function () {
			if ($this.val() == $this.attr('title')) {
				$this.val('');
			}
		});
	});

	// datepicker
	$('input.datepicker').datepicker({
		dateFormat: 'yy-mm-dd',
		showOn: 'button',
		buttonText: '',
		constrainInput: false,
		showAnim: 'fadeIn',
		showOptions: 'fast'
	}).on('change', function () {
		var selectedDate = $(this).val();
		var instance = $(this).data('datepicker');
		var date = null;
		if ('' != selectedDate) {
			try {
				date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			} catch (e) {
				date = null;
			}
		}
		if ($(this).hasClass('range-from')) {
			$(this).parent().find('.datepicker.range-to').datepicker("option", "minDate", date);
		}
		if ($(this).hasClass('range-to')) {
			$(this).parent().find('.datepicker.range-from').datepicker("option", "maxDate", date);
		}
	}).trigger('change');
	$('.ui-datepicker').hide(); // fix: make sure datepicker doesn't break wordpress wpallimport-layout upon initialization

	// no-enter-submit forms
	$('form.no-enter-submit').find('input,select,textarea').not('*[type="submit"]').on('keydown', function (e) {
		if (13 == e.keyCode) e.preventDefault();
	});

	$('a.collapser').each(function(){
		if ($(this).html() == "+"){
			$(this).parents('div:first').find('.collapser_content:first').hide();
		} else {
			$(this).parents('div:first').find('.collapser_content:first').fadeIn();
		}
		$(this).next('h3').css({'cursor':'pointer'});
	});

	$('a.collapser').on('click', function(){
		if ($(this).html() == "+") {
			$(this).html("-");
			$(this).parents('div:first').find('.collapser_content:first').fadeIn();
		} else {
			$(this).html("+");
			$(this).parents('div:first').find('.collapser_content:first').hide();
		}
	});

	$('a.collapser').each(function(){
		$(this).parents('.fieldset:first').find('h3:first').on('click', function(){
			$(this).prev('a.collapser').trigger('click');
		});
	});

	$('.change_file').each(function(){
		let $wrap = $('.wrap').has('.wpallimport-layout');
		let formHeight = ($('.wpallimport-layout').height() < 730) ? 730 : $('.wpallimport-layout').height();
		$('#file_selector').ddslick({
			width: 600,
			onSelected: function(selectedData){
				if (selectedData.selectedData.value != ""){
		    		$('#file_selector').find('.dd-selected').css({'color':'#555'});
					let filename = selectedData.selectedData.value;
					$('.change_file').find('input[name=file]').val(filename);
		    	} else {
		    		$('#file_selector').find('.dd-selected').css({'color':'#cfceca'});
		    	}
		    }
		});

		let fixWrapHeight = false;

		$('#custom_type_selector').ddslick({
			width: 590,
			onSlideDownOptions: function(o){
				formHeight = ($('.wpallimport-layout').height() < 730) ? 730 : $('.wpallimport-layout').height();
				$wrap.css({'height': formHeight + $('#custom_type_selector').find('.dd-options').height() + 'px'});
			},
			onSlideUpOptions: function(o){
				$wrap.css({'height': 'auto'});
			},
			onSelected: function(selectedData){
				if (fixWrapHeight) {
					$wrap.css({'height': 'auto'});
				} else {
					fixWrapHeight = true;
				}

				$('.wpallimport-upgrade-notice').hide();

		        $('input[name=custom_type]').val(selectedData.selectedData.value);
		        $('#custom_type_selector').find('.dd-selected').css({'color':'#555'});

				var is_import_denied = $('.wpallimport-upgrade-notice[rel="'+ selectedData.selectedData.value +'"]').length;

				if (is_import_denied){
					$('.wpallimport-upgrade-notice[rel="'+ selectedData.selectedData.value +'"]').slideDown();
					$('.wpallimport-submit-buttons').hide();
				} else {
					$('.wpallimport-submit-buttons').slideDown();
                }
                // Rapid Add-On API Images
                wpai_set_custom_select_image();
		    }
		});

		$('.wpallimport-import-from').on('click', function(){

			var showImportType = false;

			switch ($(this).attr('rel')){
				case 'upload_type':
					if ($('input[name=filepath]').val() != '') {
						showImportType = true;
					}
					$('.wpallimport-download-resource').hide();
					break;
				case 'url_type':
					if ($('input[name=url]').val() != '') {
						showImportType = true;
					}
					$('.wpallimport-download-from-checked').trigger('click');
					break;
				case 'file_type':
					if ($('input[name=file]').val() != '') {
						showImportType = true;
					}
					$('.wpallimport-download-resource').hide();
					break;
			}

			$('.wpallimport-import-from').removeClass('selected').addClass('bind');
			$(this).addClass('selected').removeClass('bind');
			$('.wpallimport-import-from').removeClass('selected').addClass('bind');
			$(this).addClass('selected').removeClass('bind');
			$('.change_file').find('.wpallimport-upload-type-container').hide();
			$('.change_file').find('.wpallimport-file-upload-result').attr('rel', $(this).attr('rel'));
			$('.change_file').find('.wpallimport-upload-type-container[rel=' + $(this).attr('rel') + ']').show();
			$('.change_file').find('#wpallimport-url-upload-status').html('');
			$('.change_file').find('input[name=new_type]').val( $(this).attr('rel').replace('_type', '') );
			//$('.first-step-errors').hide();

			if ($(this).attr('rel') == 'upload_type'){
				$('input[type=file]').trigger('click');
			}
		});
		$('.wpallimport-import-from.selected').trigger('click');

		$('.wpallimport-download-from').on('click', function(){
			if ($(this).attr('rel') === 'url') {
				$('.wpallimport-download-resource-step-two-url').show();
				$('.wpallimport-download-resource-step-two-ftp').hide();
			} else {
				$('.wpallimport-download-resource-step-two-url').hide();
				$('.wpallimport-download-resource-step-two-ftp').show();
			}
			$('.wpallimport-download-from').removeClass('wpallimport-download-from-checked');
			$(this).addClass('wpallimport-download-from-checked');
			$('.change_file').find('input[name=new_type]').val( $(this).attr('rel') );
		});
		$('.wpallimport-download-from.wpallimport-download-from-checked').trigger('click');

	});

	$('input[name=url]').on('change', function(){

	}).on('keyup', function (e) {
		if ($(this).val() != ''){
			$('.wpallimport-url-icon').addClass('focus');
			$(this).addClass('focus');
		} else {
			$('.wpallimport-url-icon').removeClass('focus');
			$(this).removeClass('focus');
		}
	});

	$('#taxonomy_to_import').ddslick({
		width: 300,
		onSelected: function(selectedData){
			if (selectedData.selectedData.value != ""){
				$('#taxonomy_to_import').find('.dd-selected').css({'color':'#555'});
				$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').show();
			} else {
				$('#taxonomy_to_import').find('.dd-selected').css({'color':'#cfceca'});
				$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
			}
			$('input[name=taxonomy_type]').val(selectedData.selectedData.value);
		}
	});
	
	$('#taxonomy_to_import li').each(function() {
        var toolTipText = $(this).find('.dd-option-value').val();
        $(this).attr('title', toolTipText);
    });

	// enter-submit form on step 1
	$('.wpallimport-step-1').each(function(){

		var $wrap = $('.wrap').has('.wpallimport-layout');

		var formHeight = ($('.wpallimport-layout').height() < 730) ? 730 : $('.wpallimport-layout').height();

		$('.wpallimport-import-from').on('click', function(){

			var showImportType = false;

			switch ($(this).attr('rel')){
				case 'upload_type':
					if ($('input[name=filepath]').val() != '') {
						showImportType = true;
					}
					$('.wpallimport-download-resource').hide();
					break;
				case 'url_type':
					if ($('input[name=url]').val() != '') {
						showImportType = true;
					}
					$('.wpallimport-download-from-checked').trigger('click');
					break;
				case 'file_type':
					if ($('input[name=file]').val() != '') {
						showImportType = true;
					}
					$('.wpallimport-download-resource').hide();
					break;
			}

			$('.wpallimport-import-from').removeClass('selected').addClass('bind');
			$('.wpallimport-import-types').find('h2').slideUp();
			$(this).addClass('selected').removeClass('bind');
			$('.wpallimport-choose-file').find('.wpallimport-upload-type-container').hide();
			$('.wpallimport-choose-file').find('.wpallimport-file-upload-result').attr('rel', $(this).attr('rel'));
			$('.wpallimport-choose-file').find('.wpallimport-upload-type-container[rel=' + $(this).attr('rel') + ']').show();
			$('.wpallimport-choose-file').find('#wpallimport-url-upload-status').html('');
			$('.wpallimport-choose-file').find('input[name=type]').val( $(this).attr('rel').replace('_type', '') );

			if ($('.auto-generate-template').attr('rel') == $(this).attr('rel')){
				$('.auto-generate-template').css({'display':'inline-block'});
			} else {
				$('.auto-generate-template').hide();
			}

			if ($(this).attr('rel') == 'upload_type'){
				$('input[type=file]').trigger('click');
			}
			if ( ! showImportType){
				$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideUp();
				$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
			} else {
				$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideDown();
				$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').show();
			}
		});

		$('.wpallimport-import-from.selected').trigger('click');

		$('.wpallimport-download-from-url').on('click', function(){

			let $type = $('input[name=type]').val();
			let $url = $('input[name=url]').val();
			let $ftp_host = $('input[name=ftp_host]').val();
			let $ftp_path = $('input[name=ftp_path]').val();
			let $ftp_root = $('input[name=ftp_root]').val();
			let $ftp_port = $('input[name=ftp_port]').val();
			let $ftp_username = $('input[name=ftp_username]').val();
			let $ftp_password = $('input[name=ftp_password]').val();
			let $ftp_private_key = $('textarea[name=ftp_private_key]').val();
			let $template = $('input[name=template]').val();

			switch ($type) {
				case 'ftp':
					if ("" == $ftp_host || $ftp_port == "" || $ftp_username == "" || $ftp_password == "") return;
					break;
				default:
					if ("" == $url) return;
					break;
			}

			$('#wpallimport-url-upload-status').html('');
			$('.error.inline').remove();
			$('.first-step-errors').hide();

			var request = {
				action: 'upload_resource',
				security: wp_all_import_security,
				type: $type,
				ftp_host: $ftp_host,
				ftp_path: $ftp_path,
				ftp_root: $ftp_root,
				ftp_port: $ftp_port,
				ftp_username: $ftp_username,
				ftp_password: $('input[name="ftp_password"]').val(),
				ftp_private_key: $ftp_private_key,
				file: $url,
				template: $template
		    };
		    $(this).attr({'disabled':'disabled'});

		    var $indicator = $('.img_preloader').css({'visibility':'visible'});

		    $('.wpallimport-upload-type-container[rel=url_type]').find('.wpallimport-note').find('span').hide();

		    var ths = $(this);

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: request,
				success: function(response) {
					if (response.success) {
						if (response.post_type) {
							let index = $('#custom_type_selector li:has(input[value="'+ response.post_type +'"])').index();
							if (index != -1) {
								if (response.taxonomy_type) {
									let tindex = $('#taxonomy_to_import li:has(input[value="'+ response.taxonomy_type +'"])').index();
									if (tindex != -1){
										$('#taxonomy_to_import').ddslick('select', {index: tindex });
									}
								}
								if (response.gravity_form_title) {
									let tindex = $('#gravity_form_to_import li:has(input[value="'+ response.gravity_form_title +'"])').index();
									if (tindex != -1){
										$('#gravity_form_to_import').ddslick('select', {index: tindex });
									}
								}
								$('#custom_type_selector').ddslick('select', {index: index });
								$('.auto-generate-template').css({'display':'inline-block'}).attr('rel', 'url_type');
							} else {
								$('.auto-generate-template').hide();
							}
						} else {
							$('.auto-generate-template').hide();
						}

						if ( response.post_type && response.notice !== false ) {
							var $note = $('.wpallimport-upload-type-container[rel=url_type]').find('.wpallimport-note');
							$note.find('span').html("<div class='wpallimport-free-edition-notice'>" + response.notice + "</div>").show();
							$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
							$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideUp();
							$('input[name=filepath]').val('');
						} else {
							$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideDown(400, function(){
								$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').show();
							});
							$('.wpallimport-choose-file').find('input[name=downloaded]').val(window.JSON.stringify(response.upload_result));
						}
					} else {
						if (response.is_valid) {
							$('.wpallimport-header').next('.clear').after(response.errors);
						} else {
							$('.error-file-validation').find('h4').html(response.errors);
							$('.error-file-validation').show();
						}
					}
					$indicator.css({'visibility':'hidden'});
					ths.removeAttr('disabled');
				},
				error: function(response) {
					$indicator.css({'visibility':'hidden'});
					ths.removeAttr('disabled');
					$('.wpallimport-header').next('.clear').after(response.responseText);
				},
				dataType: "json"
			});
		});

		var fixWrapHeight = false;

		$('#custom_type_selector').ddslick({
			width: 300,
			onSlideDownOptions: function(o){
				formHeight = ($('.wpallimport-layout').height() < 730) ? 730 : $('.wpallimport-layout').height();
				$wrap.css({'height': formHeight + $('#custom_type_selector').find('.dd-options').height() + 'px'});
			},
			onSlideUpOptions: function(o){
				$wrap.css({'height' : 'auto'});
			},
			onSelected: function(selectedData){
				if (fixWrapHeight){
					$wrap.css({'height': 'auto'});
				} else {
					fixWrapHeight = true;
				}

				$('.wpallimport-upgrade-notice').hide();

		        $('input[name=custom_type]').val(selectedData.selectedData.value);

				var is_import_denied = $('.wpallimport-upgrade-notice[rel="'+ selectedData.selectedData.value +'"]').length;

				if (is_import_denied){
					$('.wpallimport-upgrade-notice[rel="'+ selectedData.selectedData.value +'"]').slideDown();
				}

				if ($('.wpallimport-upload-resource-step-two:visible').length && ! is_import_denied) {
					$('#custom_type_selector').find('.dd-selected').css({'color':'#555'});
					$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').show();
				} else {
					$('#custom_type_selector').find('.dd-selected').css({'color':'#555'});
					$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
				}

				switch (selectedData.selectedData.value) {
					case 'taxonomies':
						$('.taxonomy_to_import_wrapper').slideDown();
						var selectedTaxonomy = $('input[name=taxonomy_type]').val();
						if (selectedTaxonomy == ''){
							$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
						}
						break;
					case 'gf_entries':
						$('.gravity_form_to_import_wrapper').slideDown();
						var selectedForm = $('input[name=gravity_form_title]').val();
						if (selectedForm == ''){
							$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
						}
						break;
					default:
						$('.taxonomy_to_import_wrapper').slideUp();
						$('.gravity_form_to_import_wrapper').slideUp();
						break;
				}

                // Rapid Add-On API Images
                wpai_set_custom_select_image();
		    }
		});

		$('#file_selector').ddslick({
			width: 600,
			onSelected: function(selectedData){

				$('.wpallimport-upload-type-container[rel=file_type]').find('.wpallimport-note').find('span').hide();

		    	if (selectedData.selectedData.value != ""){

		    		$('#file_selector').find('.dd-selected').css({'color':'#555'});

					var filename = selectedData.selectedData.value;
					$('#file_selector').find('.dd-option-value').each(function(){
						if (filename == $(this).val()) return false;
					});

					$('.wpallimport-choose-file').find('input[name=file]').val(filename);

					var request = {
						action: 'get_bundle_post_type',
						security: wp_all_import_security,
						file: filename
				    };

					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: request,
						success: function(response) {
							if (response.post_type) {
								var index = $('#custom_type_selector li:has(input[value="'+ response.post_type +'"])').index();
								if (index != -1) {
									if (response.taxonomy_type) {
										var tindex = $('#taxonomy_to_import li:has(input[value="'+ response.taxonomy_type +'"])').index();
										if (tindex != -1){
											$('#taxonomy_to_import').ddslick('select', {index: tindex });
										}
									}
									if (response.gravity_form_title) {
										var tindex = $('#gravity_form_to_import li:has(input[value="'+ response.gravity_form_title +'"])').index();
										if (tindex != -1){
											$('#gravity_form_to_import').ddslick('select', {index: tindex });
										}
									}
									$('#custom_type_selector').ddslick('select', {index: index });
									$('.auto-generate-template').css({'display':'inline-block'}).attr('rel', 'url_type');
								} else {
									$('.auto-generate-template').hide();
								}
							}

							if (response.post_type && response.notice !== false) {
								var $note = $('.wpallimport-upload-type-container[rel=file_type]').find('.wpallimport-note');
								$note.find('span').html("<div class='wpallimport-free-edition-notice'>" + response.notice + "</div>").show();
								$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
								$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideUp();
							} else {
								$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideDown(400, function(){
									$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').show();
								});
							}
						},
						error: function(response) {
							$('.wpallimport-header').next('.clear').after(response.responseText);
						},
						dataType: "json"
					});

		    	} else {
		    		if ($('.wpallimport-import-from.selected').attr('rel') == 'file_type') {
		    			$('.wpallimport-choose-file').find('input[name=file]').val('');
			    		$('#file_selector').find('.dd-selected').css({'color':'#cfceca'});
			    		$('.wpallimport-choose-file').find('.wpallimport-upload-resource-step-two').slideUp();
						$('.wpallimport-choose-file').find('.wpallimport-submit-buttons').hide();
		    		}
		    	}
		    }
		});

		$('.wpallimport-import-to').on('click', function(){
			if ($(this).attr('rel') == 'new'){
				$('.wpallimport-new-records').show();
				$('.wpallimport-existing-records').hide();
			} else {
				$('.wpallimport-new-records').hide();
				$('.wpallimport-existing-records').show();
			}
			$('.wpallimport-import-to').removeClass('wpallimport-import-to-checked');
			$(this).addClass('wpallimport-import-to-checked');
			$('input[name=wizard_type]').val($(this).attr('rel'));
			$('.wpallimport-choose-import-direction').attr({'rel' : $(this).attr('rel')});
			$('.dd-container').fadeIn();
		});

		$('.wpallimport-download-from').on('click', function(){
			if ($(this).attr('rel') === 'url') {
				$('.wpallimport-download-resource-step-two-url').show();
				$('.wpallimport-download-resource-step-two-ftp').hide();
			} else {
				$('.wpallimport-download-resource-step-two-url').hide();
				$('.wpallimport-download-resource-step-two-ftp').show();
			}
			$('.wpallimport-download-from').removeClass('wpallimport-download-from-checked');
			$(this).addClass('wpallimport-download-from-checked');
			$('.wpallimport-choose-file').find('input[name=type]').val( $(this).attr('rel') );
			$('.dd-container').fadeIn();
		});

		$('#custom_type_selector').hide();

		$('.wpallimport-import-to.wpallimport-import-to-checked').trigger('click');
		$('.wpallimport-download-from.wpallimport-download-from-checked').trigger('click');

		$('a.auto-generate-template').on('click', function(){
			$('input[name^=auto_generate]').val('1');
			$(this).parents('form:first').submit();
		});

		$('a.create-filters-step').on('click', function(){
			$('input[name^=go_to_create_filters]').val('1');
			$(this).parents('form:first').submit();
		});
	});
	//[/End Step 1]

	// template form: auto submit when `load template` list value is picked
	$('form.wpallimport-template').find('select[name="load_template"]').on('change', function () {
		$(this).parents('form').submit();
	});

	var serialize_ctx_mapping = function(){
		$('.custom_type[rel=tax_mapping]').each(function(){
	    	var values = new Array();
	    	$(this).find('.form-field').each(function(){
	    		if ($(this).find('.mapping_to').val() != "") {
	    			var skey = $(this).find('.mapping_from').val();
	    			if ('' != skey){
	    				var obj = {};
	    				obj[skey] = $(this).find('.mapping_to').val();
	    				values.push(obj);
	    			}
	    		}
	    	});
	    	$(this).find('input[name^=tax_mapping]').val(window.JSON.stringify(values));
	    });
	};

	// add expander
	$(document).on('click', '.xml-expander', function () {
		var method;
		if ('-' == $(this).text()) {
			$(this).text('+');
			method = 'addClass';
		} else {
			$(this).text('-');
			method = 'removeClass';
		}
		// for nested representation based on div
		$(this).parent().find('> .xml-content')[method]('collapsed');
		// for nested representation based on tr
		var $tr = $(this).parent().parent().filter('tr.xml-element').next()[method]('collapsed');
	});

	// [xml representation dynamic]
	$.fn.xml = function (opt) {
		if ( ! this.length) return this;

		var $self = this;
		var opt = opt || {};
		var action = {};
		if ('object' == typeof opt) {
			action = opt;
		} else {
			action[opt] = true;
		}
		action = $.extend({init: ! this.data('initialized')}, action);

		if (action.init) {
			this.data('initialized', true);
		}
		if (action.dragable) {

			this.find('.xml-tag.opening > .xml-tag-name, .xml-attr-name, .csv-tag.opening > .csv-tag-name, .ui-menu-item').each(function () {
				var $this = $(this);
				var xpath = '.';
				if ($this.is('.xml-attr-name'))
					xpath = '{' + ($this.parents('.xml-element:first').attr('title').replace(/^\/[^\/]+\/?/, '') || '.') + '/@' + $this.html().trim() + '}';
				else if($this.is('.ui-menu-item'))
					xpath = '{' + ($this.attr('title').replace(/^\/[^\/]+\/?/, '') || '.') + '}';
				else
					xpath = '{' + ($this.parent().parent().attr('title').replace(/^\/[^\/]+\/?/, '') || '.') + '}';

				$this.data('xpath', xpath).on('dblclick', function(e) {
					$wpAllImportDrag = $(this);
					$wpAllImportOriginalColor = $wpAllImportDrag.css('color');
					$wpAllImportDrag.css('color', '#46ba69').css('font-weight','900');
				}).draggable({
					helper: function() {
						return $('<div>').text($(this).data('xpath')).css({
							'padding': '5px',
							'border-radius': '5px'
						});
					},
					cursor: 'pointer',
					iframeFix:true
				}).css('cursor', 'pointer');
			});

			wpaiMakeDroppable();
		}

		return this;
	};

	// template form: preview button
	$('form.wpallimport-template').each(function () {
		var $form = $(this);

		// The form should not submit when Enter is pressed.
		$form.on('keypress', function (event) { 
			var keycode = (event.keyCode ? event.keyCode : event.which); 
			
			if (keycode === 13 && event.target.tagName !== 'TEXTAREA') { 
				event.preventDefault(); 
			} 
		});

		var $detected_cf = new Array();

		$form.find('.preview, .preview_images, .preview_taxonomies, .preview_prices').on('click', function () {
			var $preview_type = $(this).attr('rel');
			var $options_slug = $(this).parent('div').find('.wp_all_import_section_slug').val();

			if ($preview_type == 'preview_taxonomies') serialize_ctx_mapping();

			var $URL = 'admin.php?page=pmxi-admin-import&action=' + $preview_type + ((typeof import_id != "undefined") ? '&id=' + import_id : '');
			var $tagURL = 'admin.php?page=pmxi-admin-import&action=tag' + ((typeof import_id != "undefined") ? '&id=' + import_id : '');

			if ($options_slug != undefined) $URL += '&slug=' + $options_slug;

			$('.wpallimport-overlay').show();

			var $ths = $(this);

			$(this).pointer({
	            content: '<div class="wpallimport-preview-preload wpallimport-pointer-' + $preview_type + '"></div>',
	            position: {
	                edge: 'right',
	                align: 'center'
	            },
	            pointerWidth: ($preview_type == 'preview_images') ? 800 : 715,
	            close: function() {
	                $.post( ajaxurl, {
	                    pointer: 'pksn1',
	                    action: 'dismiss-wp-pointer'
	                });
	                $('.wpallimport-overlay').hide();
	            }
	        }).pointer('open');

	        var $pointer = $('.wpallimport-pointer-' + $preview_type).parents('.wp-pointer').first();

	        var $leftOffset = ($(window).width() - (($preview_type == 'preview_images') ? 800 : 715))/2;

	        $pointer.css({'position':'fixed', 'top' : '15%', 'left' : $leftOffset + 'px'});

			if (typeof tinyMCE != 'undefined') tinyMCE.triggerSave(false, false);

			$.post($URL, $form.serialize(), function (response) {

				$ths.pointer({'content' : response.html});

				$pointer.css({'position':'fixed', 'top' : '15%', 'left' : $leftOffset + 'px'});

				var $preview = $('.wpallimport-' + $preview_type);

				$preview.parent('.wp-pointer-content').removeClass('wp-pointer-content').addClass('wpallimport-pointer-content');

				var $tag = $('.tag');
				var tagno = parseInt($tag.find('input[name="tagno"]').val());
				$preview.find('.navigation a').unbind('click').off('click').on('click', function () {
					tagno += '#prev' == $(this).attr('href') ? -1 : 1;
					$tag.addClass('loading').css('opacity', 0.7);
					$preview.addClass('loading').css('opacity', 0.7);
					$.post($tagURL, {tagno: tagno, import_action: import_action, security: wp_all_import_security}, function (data) {
						var $indicator = $('<span />').insertBefore($tag);
						$tag.replaceWith(data.html);
						fix_tag_position();
						$indicator.next().tag().prevObject.remove();
						if ($('#variations_xpath').length){
							$('#variations_xpath').data('checkedValue', '').trigger('change');
						}
					    $preview.find('input[name="tagno"]').off();
					    $preview.find('.navigation a').off('click');
					    $form.find('.' + $preview_type).trigger('click');

					}, 'json');
					return false;
				});
				$preview.find('input[name="tagno"]').unbind('click').off('click').on('change', function () {
					tagno = (parseInt($(this).val()) > parseInt($preview.find('.pmxi_count').html())) ? $preview.find('.pmxi_count').html() : ( (parseInt($(this).val())) ? $(this).val() : 1 );
					$tag.addClass('loading').css('opacity', 0.7);
					$.post($tagURL, {tagno: tagno, security: wp_all_import_security}, function (data) {
						var $indicator = $('<span />').insertBefore($tag);
						$tag.replaceWith(data.html);
						fix_tag_position();
						$indicator.next().tag().prevObject.remove();
						if ($('#variations_xpath').length){
							$('#variations_xpath').data('checkedValue', '').trigger('change');
						}
					    $preview.find('input[name="tagno"]').off();
					    $preview.find('.navigation a').off('click');
					    $form.find('.' + $preview_type).trigger('click');
					}, 'json');
					return false;
				});

			}, 'json');
			return false;
		});

		$form.find('.set_encoding').on('click', function(e){
			e.preventDefault();
			$form.find('a[rel="preview"].preview').trigger('click');
		});

		$form.find('input[name$=download_images]').each(function(){
			if ($(this).is(':checked') && $(this).val() == 'gallery' ) {
				$(this).parents('.wpallimport-collapsed-content:first').find('.advanced_options_files').find('p:first').show();
				$(this).parents('.wpallimport-collapsed-content:first').find('.advanced_options_files').find('input').attr({'disabled':'disabled'});
			}
		});

		$form.find('input[name$=download_images]').on('click', function(){
			if ($(this).is(':checked') && $(this).val() == 'gallery' ) {
				$(this).parents('.wpallimport-collapsed-content:first').find('.advanced_options_files').find('p:first').show();
				$(this).parents('.wpallimport-collapsed-content:first').find('.advanced_options_files').find('input').attr({'disabled':'disabled'});
			} else {
				$(this).parents('.wpallimport-collapsed-content:first').find('.advanced_options_files').find('p:first').hide();
				$(this).parents('.wpallimport-collapsed-content:first').find('.advanced_options_files').find('input').removeAttr('disabled');
			}
		});

		// Auto-detect custom fields
		$form.find('.auto_detect_cf').on('click', function(){

			var parent = $(this).parents('.wpallimport-collapsed-content:first');
			var request = {
				action:'auto_detect_cf',
				fields: $('#existing_meta_keys').val().split(','),
				post_type: $('input[name=custom_type]').val(),
				security: wp_all_import_security
		    };
		    $(this).attr({'disabled':'disabled'});

		    var $indicator = $('<span class="img_preloader" style="top:0;"/>').insertBefore($(this)).show();

		    var ths = $(this);

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: request,
				success: function(response) {

					parent.find('input[name^=custom_name]:visible').each(function(){
						if ("" == $(this).val()) $(this).parents('tr').first().remove();
					});

					$detected_cf = response.result;

					var $added_fields_count = 0;
					if (response.result.length){
						for (var i = 0; i < response.result.length; i++){
							var allow_add = true;
							parent.find('input[name^=custom_name]:visible').each(function(){
								if (response.result[i].key == "" || response.result[i].key == $(this).val()) {
									allow_add = false;
									return false;
								}
							});
							// if this field doesn't present in custom fields section then put it there
							if ( allow_add ){
								parent.find('a.add-new-custom').trigger('click');
								var fieldParent = parent.find('.form-field:visible').last();
								fieldParent.find('input[name^=custom_name]:visible').last().val(response.result[i].key);
								fieldParent.find('textarea[name^=custom_value]:visible').last().val(response.result[i].val);
								if (response.result[i].is_serialized) fieldParent.find('.set_serialize').last().parent().trigger('click');
								$added_fields_count++;
							}
						}
					}

					$indicator.remove();

					$('.cf_detected').html(response.msg);
					$('.cf_welcome').hide();
					$('.cf_detect_result').fadeIn();

					ths.removeAttr('disabled');
				},
				error: function(request) {
					$indicator.remove();
					ths.removeAttr('disabled');
				},
				dataType: "json"
			});
		});

		// Clear all detected custom fields
		$form.find('.clear_detected_cf').on('click', function(){
			var parent = $(this).parents('.wpallimport-collapsed-content:first');
			if ($detected_cf.length){
				for (var i = 0; i < $detected_cf.length; i++){
					parent.find('input[name^=custom_name]:visible').each(function(){
						if ($detected_cf[i].key == $(this).val()) $(this).parents('tr').first().remove();
					});
				}
			}
			if ( ! parent.find('input[name^=custom_name]:visible').length){
				parent.find('a.add-new-custom').trigger('click');
			}
			$('.cf_detected').html('');
			$('.cf_detect_result').hide();
			$('.cf_welcome').fadeIn();
			$detected_cf = new Array();
		});

		// toggle custom field as serialized/default
		$form.find('.wpallimport-cf-menu li').on('click', function(){
			var $triggerEvent = $(this).find('a');
			if ($triggerEvent.hasClass('set_serialize')){
				var parent = $triggerEvent.parents('.form-field:first');
				var parent_custom_format = parent.find('input[name^=custom_format]:first');
				var parent_custom_value = parent.find('textarea[name^=custom_value]:first');
				if (parseInt(parent_custom_format.val())){
					parent_custom_format.val(0);
					parent.find('.specify_cf:first').hide();
					parent_custom_value.fadeIn();
					$triggerEvent.parent().removeClass('active');
				} else {
					parent_custom_format.val(1);
					parent_custom_value.hide();
					parent.find('.specify_cf:first').fadeIn();
					$triggerEvent.parent().addClass('active');
				}
			}
		});

		// [Serialized custom fields]

			// Save serialized custom field format
			$(document).on('click', '.save_sf', function(){
				var $source = $(this).parents('table:first');
				var $destination = $('div#' + $source.attr('rel'));
				$destination.find('table:first').html('');
				$source.find('input').each(function(i, e){
					$(this).attr("value", $(this).val());
				});
				$destination.find('table:first').html($source.html());
				$destination.parents('td:first').find('.pmxi_cf_pointer').pointer('destroy');
				$('.wpallimport-overlay').hide();
			});

			// Auto-detect serialized custom fields
			$(document).on('click', '.auto_detect_sf', function() {
				var $source = $(this).parents('table:first');
				var $destination = $('div#' + $source.attr('rel'));
				var $parentDestination = $destination.parents('tr:first');
				var $cf_name = $parentDestination.find('input[name^=custom_name]:first').val();

				if ($cf_name != ''){
					var request = {
						action:'auto_detect_sf',
						security: wp_all_import_security,
						post_type: $('input[name=custom_type]').val(),
						name: $cf_name
				    };
				    $(this).attr({'disabled':'disabled'});

					var $indicator = $('<span class="img_preloader" style="position: absolute; top:0;"/>').insertBefore($(this)).show();
					var ths = $(this);

					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: request,
						success: function(response) {
							if (response.result.length){
								$destination.find('tr.form-field').each(function(){
									if ( ! $(this).hasClass('template') ) $(this).remove();
								});
								for (var i = 0; i < response.result.length; i++){
									$destination.find('a.add-new-key').trigger('click');
									$destination.find('tr.form-field').not('.template').last().css({"opacity": 1}).find('input.serialized_key').attr("value", response.result[i].key);
									$destination.find('tr.form-field').not('.template').last().css({"opacity": 1}).find('input.serialized_value').attr("value", response.result[i].val);
								}
								$destination.parents('td:first').find('.pmxi_cf_pointer').pointer('destroy');
								$destination.parents('td:first').find('.pmxi_cf_pointer').trigger('click');
							} else {
								var $notice = $('<p style="color:red; position: absolute; top: -10px; padding:0; margin:0;">No fields detected.</p>').insertBefore(ths).show();
								setTimeout(function() {
									$notice.slideUp().remove();
								}, 2500);
							}
							$indicator.remove();
							ths.removeAttr('disabled');
						},
						error: function(request) {
							$indicator.remove();
							ths.removeAttr('disabled');
						},
						dataType: "json"
					});
				}
			});

		// [/ Serialized custom fields]

		// Save mapping rules for custom field
		$(document).on('click', '.save_mr', function(){
			let $source = $(this).parents('table:first');
			let $destination = $('div#' + $source.attr('rel'));
			let $is_active = false;
			$destination.find('table:first').html('');
			$source.find('input').each(function(i, e){
				$(this).attr("value", $(this).val());
				if ($(this).val() != "") {
					$is_active = true;
				}
			});
			let $box = $destination.parents('td.action:first');
			if ( $is_active ){
				$box.find('.set_mapping').parent().addClass('active');
			} else {
				$box.find('.set_mapping').parent().removeClass('active');
			}
			$destination.find('table:first').html($source.html());
			$destination.parents('td:first').find('.pmxi_cf_mapping').pointer('destroy');
			$('.wpallimport-overlay').hide();
		});

		// Taxonnomies
		$form.find('#show_hidden_ctx').on('click', function(){
			$form.find('tr.private_ctx').toggle();
		});

		// Test & Preview images
		$(document).on('click', '.test_images', function(){

			let ths = $(this);

			$(this).attr({'disabled':'disabled'});

			$('.img_preloader').show();
			$('.img_success').html('').hide();
			$('.img_failed').remove();

			let imgs = new Array();

			$('.images_list').find('li').each(function(){
				imgs.push($(this).attr('rel'));
			});

			let request = {
				action: 'test_images',
				security: wp_all_import_security,
				download: ths.attr('rel'),
				imgs:imgs
		    };

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: request,
				success: function(response) {
					$('.img_preloader').hide();
					if ( parseInt(response.success_images)) {
						$('.img_success').html(response.success_msg).show();
					}
					if (response.failed_msgs.length){
						for (var i = 0; i < response.failed_msgs.length; i++){
							$('.test_progress').append('<div class="img_failed">' + response.failed_msgs[i] + '</div>');
						}
						$('.img_failed').show();
					}
					ths.removeAttr('disabled');
				},
				error: function(request) {
					$('.img_failed').html(request.textStatus).show();
					ths.removeAttr('disabled');
				},
				dataType: "json"
			});

		});

		/* Merge Main XML file with sub file by provided fields */
		$form.find('.parse').on('click', function(){

			var submit = true;

			if ("" == $form.find('input[name=nested_url]').val()){
				$form.find('input[name=nested_url]').css({'background':'red'});
				submit = false;
			}

			if (submit){

				var ths = $(this);
				var $fileURL = $form.find('input[name=nested_url]').val();

				$(this).attr({'disabled':'disabled'});

				var request = {
					action:'nested_merge',
					security: wp_all_import_security,
					filePath: $fileURL,
			    };

			    var $indicator = $('<span class="img_preloader" style="top:10px;"/>').insertBefore($(this)).show();

			    $form.find('.nested_msgs').html('');

				$.ajax({
					type: 'POST',
					url: ajaxurl + ((typeof import_id != "undefined") ? '?id=' + import_id : ''),
					data: request,
					success: function(response) {
						$indicator.remove();

						if (response.success)
						{
							$form.find('.nested_files ul').append('<li rel="' + $form.find('.nested_files ul').find('li').length + '">' + $fileURL + ' <a href="javascript:void(0);" class="unmerge">remove</a></li>');
							$form.find('input[name=nested_files]').val(window.JSON.stringify(response.nested_files));

							var $tag = $('.tag');
							var $tagno = parseInt($tag.find('input[name="tagno"]').val());
							var $tagURL = 'admin.php?page=pmxi-admin-import&action=tag' + ((typeof import_id != "undefined") ? '&id=' + import_id : '');

							$tag.addClass('loading').css('opacity', 0.7);
							$.post($tagURL, {tagno: $tagno, import_action: import_action}, function (data) {
								var $indicator = $('<span />').insertBefore($tag);
								$tag.replaceWith(data.html);
								fix_tag_position();
								$indicator.next().tag().prevObject.remove();
								if ($('#variations_xpath').length){
									$('#variations_xpath').data('checkedValue', '').trigger('change');
								}
							}, 'json');
							return false;
						} else {
							$form.find('.nested_msgs').html(response.msg);
						}
						ths.removeAttr('disabled');
					},
					error: function(request) {
						$indicator.remove();
						ths.removeAttr('disabled');
					},
					dataType: "json"
				});
			}
		});

		/* Unmerge nested XMl/CSV files */
		$form.find('.unmerge').on('click', function(){

			var ths = $(this);

			$(this).attr({'disabled':'disabled'});

			var $indicator = $('<span class="img_preloader" style="top:5px;"/>').insertBefore($(this)).show();

			var request = {
				action:'unmerge_file',
				source: ths.parents('li:first').attr('rel'),
				security: wp_all_import_security
		    };

		    $form.find('.nested_msgs').html('');

			$.ajax({
				type: 'POST',
				url: ajaxurl + ((typeof import_id != "undefined") ? '?id=' + import_id : ''),
				data: request,
				success: function(response) {
					$indicator.remove();
					if (response.success){

						ths.parents('li:first').remove();
						$form.find('input[name=nested_files]').val(window.JSON.stringify(response.nested_files));

						var $tag = $('.tag');
						var $tagno = parseInt($tag.find('input[name="tagno"]').val());
						var $tagURL = 'admin.php?page=pmxi-admin-import&action=tag' + ((typeof import_id != "undefined") ? '&id=' + import_id : '');

						$tag.addClass('loading').css('opacity', 0.7);
						$.post($tagURL, {tagno: $tagno, import_action: import_action}, function (data) {
							var $indicator = $('<span />').insertBefore($tag);
							$tag.replaceWith(data.html);
							fix_tag_position();
							$indicator.next().tag().prevObject.remove();
							if ($('#variations_xpath').length){
								$('#variations_xpath').data('checkedValue', '').trigger('change');
							}
						}, 'json');
						return false;
					}
					else{
						$form.find('.msgs').html(response.errors);
						$form.find('.pmxi_counter').remove();
					}
					ths.removeAttr('disabled');
				},
				error: function(request) {
					$indicator.remove();
					ths.removeAttr('disabled');
				},
				dataType: "json"
			});
		});

		$form.find('input[name=nested_url]').on('focus', function(){
			$(this).css({'background':'#fff'});
		});

		var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
		var is_safari = navigator.userAgent.indexOf("Safari") > -1;
		var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;

		if ((is_safari && !is_chrome) || is_firefox){
			$form.find('textarea[name$=download_featured_image]').attr("placeholder", "http://example.com/images/image-1.jpg");
			$form.find('textarea[name$=featured_image]').attr("placeholder", "image-1.jpg");
			$form.find('textarea[name$=gallery_featured_image]').attr("placeholder", "image-1.jpg");
		} else {
			$form.find('textarea[name$=download_featured_image]').attr("placeholder", "http://example.com/images/image-1.jpg\nhttp://example.com/images/image-2.jpg\n...");
			$form.find('textarea[name$=featured_image]').attr("placeholder", "image-1.jpg\nimage-2.jpg\n...");
			$form.find('textarea[name$=gallery_featured_image]').attr("placeholder", "image-1.jpg\nimage-2.jpg\n...");
		}

		$form.find('input[name$=download_images]:checked').each(function(){
			if ($(this).val() == 'gallery') {
				$(this).parents('table:first').find('.search_through_the_media_library').slideUp();
			} else {
				$(this).parents('table:first').find('.search_through_the_media_library').slideDown();
			}
            // download images hosted elsewhere
            if ($(this).val() == 'yes'){
                $('.search_through_the_media_library_logic').show();
				$('.download_images').show();
            } else {
                $('.search_through_the_media_library_logic').hide();
				$('.download_images').hide();
            }
		});

		$form.find('input[name$=download_images]').on('click', function(){
			if ($(this).is(':checked') && $(this).val() == 'gallery') {
				$(this).parents('table:first').find('.search_through_the_media_library').slideUp();
			} else {
				$(this).parents('table:first').find('.search_through_the_media_library').slideDown();
			}
			// download images hosted elsewhere
			if ($(this).val() == 'yes'){
				$('.search_through_the_media_library_logic').slideDown();
				$('.download_images').slideDown();
			} else {
				$('.search_through_the_media_library_logic').slideUp();
				$('.download_images').slideUp();
			}
		});

		$form.find('.wpallimport-dismiss-cf-welcome').on('click', function(){
			$('.cf_welcome, .cf_detect_result').slideUp();
		});

	});

	// options form: highlight options of selected post type
	$('form.wpallimport-template input[name="type"]').on('click', function() {
		var $container = $(this).parents('.post-type-container');
		$('.post-type-container').not($container).removeClass('selected').find('.post-type-options').hide();
		$container.addClass('selected').find('.post-type-options').show();
	}).filter(':checked').trigger('click');
	// options form: add / remove custom params
	$('.form-table a.action[href="#add"]').on('click', function () {
		var $template = $(this).parents('table').first().find('tr.template');
		$template.clone(true).insertBefore($template).css('display', 'none').removeClass('template').fadeIn();
		return false;
	});

	// options form: auto submit when `load options` checkbox is checked
	$('input[name="load_options"]').on('click', function () {
		if ($(this).is(':checked')) $(this).parents('form').submit();
	});
	// options form: auto submit when `reset options` checkbox is checked
	$('form.wpallimport-template').find('input[name="reset_options"]').on('click', function () {
		if ($(this).is(':checked')) $(this).parents('form').submit();
	});
	$(document).on('click', '.form-table .action.remove a, .cf-form-table .action.remove a, .tax-form-table .action.remove a', function () {
		let $box = $(this).parents('tbody').first();
		$(this).parents('tr').first().remove();
		if ( ! $box.find('tr.form-field:visible').length ){
			$box.find('.add-new-entry').trigger('click');
		}
		return false;
	});

	var dblclickbuf = {
		'selected':false,
		'value':''
	};

	function insertxpath(){
		if ($(this).hasClass('wpallimport-placeholder')){
			$(this).val('');
			$(this).removeClass('wpallimport-placeholder');
		}
		if (dblclickbuf.selected) {
			$(this).val($(this).val() + dblclickbuf.value);
			$('.xml-element[title*="/'+dblclickbuf.value.replace('{','').replace('}','')+'"]').removeClass('selected');
			dblclickbuf.value = '';
			dblclickbuf.selected = false;
		}
	}

	var go_to_template = false;

	// selection logic
	$('form.wpallimport-choose-elements').each(function () {
		var $form = $(this);
		$form.find('.wpallimport-xml').xml();
		var $input = $form.find('input[name="xpath"]');
		var $next_element = $form.find('#next_element');
		var $prev_element = $form.find('#prev_element');
		var $goto_element =  $form.find('#goto_element');
		var $get_default_xpath = $form.find('#get_default_xpath');
		var $root_element = $form.find('#root_element');
		var $submit = $form.find('input[type="submit"]');
		var $csv_delimiter = $form.find('input[name=delimiter]');
		var $apply_delimiter = $form.find('input[name=apply_delimiter]');

		var $xml = $('.wpallimport-xml');

		var xpathChanged = function (reset_element) {
			if ($input.val() == $input.data('checkedValue')) return;

			$form.addClass('loading');
			$form.find('.xml-element.selected').removeClass('selected'); // clear current selection
			// request server to return elements which correspond to xpath entered
			$input.attr('readonly', true).unbind('change', xpathChanged).data('checkedValue', $input.val());
			$xml.css({'visibility':'hidden'});
			$('.wpallimport-set-csv-delimiter').hide();

			$xml.parents('fieldset:first').addClass('preload');
			go_to_template = false;
			$submit.hide();
			var evaluate = function(){
				$.post('admin.php?page=pmxi-admin-import&action=evaluate', {xpath: $input.val(), show_element: reset_element ? 1 : $goto_element.val(), root_element:$root_element.val(), is_csv: $apply_delimiter.length, delimiter:$csv_delimiter.val(), security: wp_all_import_security}, function (response) {
					if (response.result){
						$('.wpallimport-elements-preloader').hide();
						$('.ajax-console').html(response.html);
						$input.attr('readonly', false).on('change', function(){$goto_element.val(1); xpathChanged();});
						$form.removeClass('loading');

						$xml.parents('fieldset:first').removeClass('preload');
						$('.wpallimport-set-csv-delimiter').show();
						go_to_template = true;
						$('#pmxi_xml_element').find('option').each(function(){
							if ($(this).val() != "") $(this).remove();
						});
						$('#pmxi_xml_element').append(response.render_element);
						$('.wpallimport-root-element').html(response.root_element);
						$('.wpallimport-elements-count-info').html(response.count);
						$('.wp_all_import_warning').hide();
						if (response.count){
							$submit.show();
							if ($('.xml-element.lvl-1').length < 1) $('.wp_all_import_warning').css({'display':'inline-block'});
						} else {
							$submit.hide();
						}
					}
				}, "json").fail(function() {
					$xml.parents('fieldset:first').removeClass('preload');
					$form.removeClass('loading');
					$('.ajax-console').html('<div class="error inline"><p>No matching elements found for XPath expression specified.</p></div>');
				});
			}
			evaluate();
		};
		$next_element.on('click', function(){
			var matches_count = ($('.matches_count').length) ? parseInt($('.matches_count').html()) : 0;
			var show_element = Math.min((parseInt($goto_element.val()) + 1), matches_count);
			$goto_element.val(show_element).html( show_element ); $input.data('checkedValue', ''); xpathChanged();
		});
		$prev_element.on('click', function(){
			var show_element = Math.max((parseInt($goto_element.val()) - 1), 1);
			$goto_element.val(show_element).html( show_element ); $input.data('checkedValue', ''); xpathChanged();
		});
		$goto_element.on('change', function(){
			var matches_count = ($('.matches_count').length) ? parseInt($('.matches_count').html()) : 0;
			var show_element = Math.max(Math.min(parseInt($goto_element.val()), matches_count), 1);
			$goto_element.val(show_element); $input.data('checkedValue', ''); xpathChanged();
		});

		var reset_filters = function(){
			$('#apply_filters').hide();
			$('.filtering_rules').empty();
			$('#filtering_rules').find('p').show();
		}

		$get_default_xpath.on('click', function(){
			$input.val($(this).attr('rel'));
			if ($input.val() == $input.data('checkedValue')) return;
			reset_filters();
			$root_element.val($(this).attr('root'));
			$goto_element.val(1);
			xpathChanged(true);
		});
		$('.wpallimport-change-root-element').on('click', function(){
			$input.val('/' + $(this).attr('rel'));
			if ($input.val() == $input.data('checkedValue')) return;
			$('.wpallimport-change-root-element').removeClass('selected');
			$(this).addClass('selected');
			reset_filters();
			$('.root_element').html($(this).attr('rel'));
			$root_element.val($(this).attr('rel'));
			$goto_element.val(1);
			xpathChanged(true);
		});
		$input.on('change', function(){
			$goto_element.val(1);
			xpathChanged(true);
		}).trigger('change');
		$input.on('keyup', function (e) {
			if (13 == e.keyCode) $(this).trigger('change');
		});

		$apply_delimiter.on('click', function(){
			if ( ! $input.attr('readonly') ){
				$('input[name="xpath"]').data('checkedValue','');
				$goto_element.val(1);
				xpathChanged(true);
			}
		});

		$('#apply_filters').on('click', function(){

			var $input = $('input[name="xpath"]');
			var xpath = $input.val();

			filter = '[';
			xpath_builder($('.filtering_rules'), 0);
			filter += ']';

			$input.val( $input.val().split('[')[0] + filter);
			$input.data('checkedValue', '');
			$goto_element.val(1);
			xpathChanged(true);
			if ($('.filtering_rules').html().length) {
				$('.filtering-output').val(window.JSON.stringify($('.filtering_rules').html()));
			} else {
				$('.filtering-output').val('');
			}
		});
	});

	/* Advanced Filtering */

	$('.filtering_rules').pmxi_nestedSortable({
		handle: 'div',
		items: 'li',
		toleranceElement: '> div',
		update: function () {
			$('.filtering_rules').find('.condition').show();
			$('.filtering_rules').find('.condition:last').hide();
		}
	});

	$('#pmxi_add_rule').on('click', function(){

		var $el = $('#pmxi_xml_element');
		var $rule = $('#pmxi_rule');
		var $val = $('#pmxi_value');

		if ($el.val() == "" || $rule.val() == "") return;

		if ($rule.val() != 'is_empty' && $rule.val() != "is_not_empty" && $val.val() == "") return;

		var relunumber = $('.filtering_rules').find('li').length + "_" + $.now();

		var html = '<li><div class="drag-element">';
		html += '<input type="hidden" value="'+ $el.val() +'" class="pmxi_xml_element"/>';
		html += '<input type="hidden" value="'+ $rule.val() +'" class="pmxi_rule"/>';
		html += '<input type="hidden" value="'+ $val.val() +'" class="pmxi_value"/>';
		html += '<span class="rule_element">' + $el.val() + '</span><span class="rule_as_is">' + $rule.find('option:selected').html() + '</span><span class="rule_condition_value">"' + $val.val() +'"</span>';
		html += '<span class="condition"> <label for="rule_and_'+relunumber+'">AND</label><input id="rule_and_'+relunumber+'" type="radio" value="and" name="rule_'+relunumber+'" checked="checked" class="rule_condition"/><label for="rule_or_'+relunumber+'">OR</label><input id="rule_or_'+relunumber+'" type="radio" value="or" name="rule_'+relunumber+'" class="rule_condition"/> </span>';
		html += '</div><a href="javascript:void(0);" class="icon-item remove-ico"></a></li>';

		$('#wpallimport-filters, #apply_filters').show();
		$('#filtering_rules').find('p').hide();

		$('.filtering_rules').append(html);

		$('.filtering_rules').find('.condition').show();
		$('.filtering_rules').find('.condition:last').hide();

		$el.prop('selectedIndex',0);
		$rule.prop('selectedIndex',0);
		$val.val('');
		$('#pmxi_value').show();
		fix_tag_position();

	});

	$(document).on('change', '.rule_condition', function() {
		$('input[name=' + $(this).attr('name') + ']').removeAttr('checked');
		$(this).prop('checked', true).attr('checked', 'checked');
	});

	$(document).on('click', '.filtering_rules li a.remove-ico', function() {
		$(this).parents('li:first').remove();
		$('.filtering_rules').find('li:last div span.condition').hide();
		if (!$('.filtering_rules').find('li').length){
			$('#apply_filters').hide().trigger('click');
			$('#filtering_rules').find('p').show();
		}
		fix_tag_position();
	});

	$('#pmxi_rule').on('change', function(){
		if ($(this).val() == 'is_empty' || $(this).val() == 'is_not_empty') {
			$('#pmxi_value').hide();
		} else {
			$('#pmxi_value').show();
		}
	});

	var filter = '[';

	var xpath_builder = function(rules_box, lvl){

		var rules = rules_box.children('li');

		var root_element = $('#root_element').val();

		if (lvl && rules.length > 1) filter += ' (';

		rules.each(function(){

			var node = $(this).children('.drag-element').find('.pmxi_xml_element').val();
			var condition = $(this).children('.drag-element').find('.pmxi_rule').val();
			var value = $(this).children('.drag-element').find('.pmxi_value').val();

			var clause = ($(this).children('.drag-element').find('.condition').is(':visible')) ? $(this).children('.drag-element').find('input.rule_condition:checked').val() : false;

			var is_attr = false;

			if (node.indexOf('@') != -1){
				is_attr = true;
				node_name = node.split('@')[0];
				attr_name = node.split('@')[1];
			}

			if (is_attr) {
				filter += (node_name == root_element) ? '' : node_name.replace(/->/g, '/');
			} else {
				filter += node.replace(/->/g, '/');
			}

			if (is_attr) filter += '@' + attr_name;

			switch (condition){
				case 'equals':
					filter += ' = "%s"';
					break;
				case 'not_equals':
					filter += ' != "%s"';
					break;
				case 'greater':
					filter += ' > %s';
					break;
				case 'equals_or_greater':
					filter += ' >= %s';
					break;
				case 'less':
					filter += ' < %s';
					break;
				case 'equals_or_less':
					filter += ' <= %s';
					break;
				case 'contains':
					filter += '[contains(.,"%s")]';
					break;
				case 'not_contains':
					filter += '[not(contains(.,"%s"))]';
					break;
				case 'is_empty':
					filter += '[not(string())]';
					break;
				case 'is_not_empty':
					filter += '[string()]';
					break;
			}

			filter = filter.replace('%s', value);

			if (clause) filter += ' ' + clause + ' ';

			if ($(this).children('ol').length){
				$(this).children('ol').each(function(){
					if ($(this).children('li').length) xpath_builder($(this), 1);
				});
			}
		});
		if (lvl && rules.length > 1) filter += ') ';

	}

	$('form.wpallimport-choose-elements').find('input[type="submit"]').on('click', function(e){
		e.preventDefault();
		if (go_to_template) $(this).parents('form:first').submit();
	});

	var init_context_menu = function(){
		if ( $(".tag").length ){
			$('.xml-element').each(function(){
				var $ths = $(this);
				if ($(this).children('.xml-element-xpaths').find('li').length){
					$(this).children('.xml-content').css({'cursor':'context-menu'}).attr({'title' : 'Right click to view alternate XPaths'});
					$(this).contextmenu({
					    delegate: ".xml-content",
					    menu: "#" + $(this).children('.xml-element-xpaths').find('ul').attr('id'),
					    select: function(event, ui) {
					        //alert("select " + ui.cmd + " on " + ui.target.text());
					    }
					});
				}
			});
		}
	}

	// tag preview
	$.fn.tag = function () {
		this.each(function () {

			init_context_menu();

			var $tag = $(this);
			$tag.xml('dragable');
			var tagno = parseInt($tag.find('input[name="tagno"]').val());
			var $tagURL = 'admin.php?page=pmxi-admin-import&action=tag' + ((typeof import_id != "undefined") ? '&id=' + import_id : '');

			$tag.find('.navigation a').on('click', function () {
				tagno += '#prev' == $(this).attr('href') ? -1 : 1;
				$tag.addClass('loading').css('opacity', 0.7);
				$.post($tagURL, {tagno: tagno, import_action: import_action, xpath: $('input[name="xpath"]').val(), security: wp_all_import_security}, function (data) {
					var $indicator = $('<span />').insertBefore($tag);
					$tag.replaceWith(data.html);
					fix_tag_position();
					$indicator.next().tag().prevObject.remove();
					if ($('#variations_xpath').length){
						$('#variations_xpath').data('checkedValue', '').trigger('change');
					}

				}, 'json');
				return false;
			});
			$tag.find('input[name="tagno"]').on('change keypress', function (event) {

				// Check if the key pressed is enter, if not exit the function unless a
				// 'change' event triggered it.
				if(event.type === "keypress") {
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode != '13'){
						return;
					}
				}

				tagno = (parseInt($(this).val()) > parseInt($tag.find('.pmxi_count').html())) ? $tag.find('.pmxi_count').html() : ( (parseInt($(this).val())) ? $(this).val() : 1 );
				$(this).val(tagno);
				$tag.addClass('loading').css('opacity', 0.7);
				$.post($tagURL, {tagno: tagno, import_action: import_action, xpath: $('input[name="xpath"]').val(), security: wp_all_import_security}, function (data) {
					var $indicator = $('<span />').insertBefore($tag);
					$tag.replaceWith(data.html);
					fix_tag_position();
					$indicator.next().tag().prevObject.remove();
					if ($('#variations_xpath').length){
						$('#variations_xpath').data('checkedValue', '').trigger('change');
					}
				}, 'json');

			});
		});
		return this;
	};
	$('.tag').tag();
	// [/xml representation dynamic]

	$('.wpallimport-custom-fields').each(function(){
		$(this).find('.wp_all_import_autocomplete').each(function(){
			if ( ! $(this).parents('tr:first').hasClass('template')){
				$(this).autocomplete({
					source: eval('__META_KEYS'),
					minLength: 0
				}).on('click', function () {
					$(this).autocomplete('search', '');
					$(this).attr('rel', '');
				});
			}
		});

		$(this).find('textarea[name^=custom_value]').on('click', function(){
			var $ths = $(this);
			var $parent = $ths.parents('tr:first');
			var $custom_name = $parent.find('input[name^=custom_name]');
			var $key = $custom_name.val();

			if ($key != "" && $custom_name.attr('rel') != "done"){
				$ths.addClass('loading');
				$.post('admin.php?page=pmxi-admin-settings&action=meta_values', {key: $key, security: wp_all_import_security}, function (data) {
					if (data.meta_values.length){
						$ths.autocomplete({
							source: eval(data.meta_values),
							minLength: 0
						}).on('click', function () {
							$(this).autocomplete('search', '');
						}).trigger('click');
					}
					$custom_name.attr('rel','done');
					$ths.removeClass('loading');
				}, 'json');
			}
		});

		$('.wpallimport-cf-options').on('click', function(){
			$(this).next('.wpallimport-cf-menu').slideToggle();
		});
	});

	/* Categories hierarchy */

	$('ol.sortable').pmxi_nestedSortable({
        handle: 'div',
        items: 'li.dragging',
        toleranceElement: '> div',
        update: function () {
	       $(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).pmxi_nestedSortable('toArray', {startDepthCount: 0})));
	       if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
	    }
    });

    $('.drag-element').find('input').on('blur', function(){
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.ui-sortable:first').pmxi_nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

    $('.drag-element').find('input').on('change', function(){
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.ui-sortable:first').pmxi_nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

    $('.drag-element').find('input').on('mouseenter', function(){},function(){
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.ui-sortable:first').pmxi_nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

    $('.taxonomy_auto_nested').on('click', function(){
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('td:first').find('.ui-sortable:first').pmxi_nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

	$(document).on('click', '.sortable li a.remove-ico, .sortable li div a.remove-ico', function(){
	 	let parent_td = $(this).parents('td:first');
		$(this).parents('li:first').remove();
		parent_td.find('ol.sortable:first').find('li').each(function(i, e){
			$(this).attr({'id':'item_'+ (i+1)});
		});
		parent_td.find('.hierarhy-output').val(window.JSON.stringify(parent_td.find('.ui-sortable:first').pmxi_nestedSortable('toArray', {startDepthCount: 0})));
	 	if (parent_td.find('input:first').val() == '') {
			parent_td.find('.hierarhy-output').val('');
		}
	});

	$('.tax_hierarchical_logic').find('.remove-ico').on('click', function(){
		$(this).parents('li:first').remove();
	});

	$(document).on('click', '.add-new-ico', function() {
		let count = parseInt($(this).parents('tr:first').find('ol.sortable').find('li.dragging').last().attr('id').replace('item_', '')) + 1;
		let $template = $(this).parents('td:first').find('ol').children('li.template');
		$clone = $template.clone(true);
		$clone.addClass('dragging').attr({'id': $clone.attr('id') + '_' + count}).find('input[type=checkbox][name^=categories_mapping]').each(function(){
			$(this).attr({'id': $(this).attr('id') + '_' + count});
			$(this).next('label').attr({'for':$(this).next('label').attr('for') + '_' + count});
			$(this).next('label').next('div').addClass($(this).next('label').next('div').attr('rel') + '_' + count);
		});
		$clone.insertBefore($template).css('display', 'none').removeClass('template').droppable({
			drop: function (event, ui) {
				let input = $(this).find("input.xpath_field:first");
				let oldValue = input.val();
				let newValue = ui.draggable.data('xpath') || '';
				input.val(oldValue + newValue);
			},
			greedy:true
		}).fadeIn().find('input.switcher').trigger('change');
		let sortable = $(this).parents('.ui-sortable:first');
		if (sortable.length){
			$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify(sortable.pmxi_nestedSortable('toArray', {startDepthCount: 0})));
	    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
	    }
		//$('.widefat').on('focus', insertxpath );
	});

	$(document).on('click', '.add-new-cat', function(){
		var $template = $(this).parents('td:first').find('ul.tax_hierarchical_logic').children('li.template');
		var $number = $(this).parents('td:first').find('ul.tax_hierarchical_logic').children('li').length - 1;
		var $cloneName = $template.find('input.assign_term').attr('name').replace('NUMBER', $number);
		$clone = $template.clone(true);
		$clone.find('input[name^=tax_hierarchical_assing]').attr('name', $cloneName);
		$clone.insertBefore($template).css('display', 'none').removeClass('template').droppable({
			drop: function (event, ui) {
				let input = $(this).find("input.hierarchical_xpath_field:first");
				let oldValue = input.val();
				let newValue = ui.draggable.data('xpath');
				input.val(oldValue + newValue);
			}
		}).fadeIn().find('input.switcher').trigger('change');
	});

	$('ol.sortable').each(function(){
		if ( ! $(this).children('li').not('.template').length ) $(this).next('.add-new-ico').trigger('click');
	});

	$('.tagno').trigger('change');

	$('form.wpallimport-template.edit').each(function() {

		var $input = $('input[name="xpath"]');

		$input.on('change', function(){
			$('.tagno').trigger('change');
			// console.log('XPath changed - ', $(this).val());
			// $.post('admin.php?page=pmxi-admin-import&action=evaluate', {xpath: $(this).val(), show_element: 1, root_element:$('#root_element').val(), is_csv: $('input[name="is_csv"]').length, delimiter:$('input[name="is_csv"]').val(), security: wp_all_import_security}, function (response) {
			// 	console.log('Rsponse - ', response);
			// 	if (response.result){
			// 		$('.tagno').trigger('change');
			// 	}
			// }, "json").fail(function() {
			//
			// });
		}).trigger('change');
		$input.on('keyup', function (e) {
			if (13 == e.keyCode) $(this).trigger('change');
		});

		$('#apply_filters').on('click', function(){

			filter = '';
			if ($('.filtering_rules').children('li').length) {
				filter = '[';
				xpath_builder($('.filtering_rules'), 0);
				filter += ']';
			}

			$input.val( $input.val().split('[')[0] + filter);
			$input.data('checkedValue', '');
			$input.trigger('change');
			if ($('.filtering_rules').html().length) {
				$('.filtering-output').val(window.JSON.stringify($('.filtering_rules').html()));
			} else {
				$('.filtering-output').val('');
			}

			// $goto_element.val(1);
			// xpathChanged(true);
		});
	});

	$('form.wpallimport-template').find('input[type=submit]').on('click', function(e){

		e.preventDefault();

		$('.hierarhy-output').each(function(){
			var sortable = $(this).parents('td:first').find('.ui-sortable:first');
			if (sortable.length){
				$(this).val(window.JSON.stringify(sortable.pmxi_nestedSortable('toArray', {startDepthCount: 0})));
				if ($(this).parents('td:first').find('input:first').val() == '') $(this).val('');
			}
		});
		if ($(this).attr('name') == 'btn_save_only') $('.save_only').val('1');

		$('input[name^=in_variations], input[name^=is_visible], input[name^=is_taxonomy], input[name^=create_taxonomy_in_not_exists], input[name^=variable_create_taxonomy_in_not_exists], input[name^=variable_in_variations], input[name^=variable_is_visible], input[name^=variable_is_taxonomy]').each(function(){
	    	if ( ! $(this).is(':checked') && ! $(this).parents('.form-field:first').hasClass('template')){
	    		$(this).val('0').prop('checked', true);
	    	}
	    });

	    $('.custom_type[rel=serialized]').each(function(){
	    	var values = new Array();
	    	$(this).find('.form-field').each(function(){
    			var skey = $(this).find('.serialized_key').val();
    			if ('' == skey){
    				values.push($(this).find('.serialized_value').val());
    			} else {
    				var obj = {};
    				obj[skey] = $(this).find('.serialized_value').val();
    				values.push(obj);
    			}
	    	});
	    	$(this).find('input[name^=serialized_values]').val(window.JSON.stringify(values));
	    });

	    $('.custom_type[rel=mapping]').each(function(){
	    	var values = new Array();
	    	$(this).find('.form-field').each(function(){
	    		if ($(this).find('.mapping_to').val() != "") {
	    			var skey = $(this).find('.mapping_from').val();
	    			if ('' != skey){
	    				var obj = {};
	    				obj[skey] = $(this).find('.mapping_to').val();
	    				values.push(obj);
	    			}
	    		}
	    	});
	    	$(this).find('input[name^=custom_mapping_rules], .pmre_mapping_rules').val(window.JSON.stringify(values));
	    });

	    serialize_ctx_mapping();

		$(this).parents('form:first').submit();

	});

	$('.wpallimport-step-4').each(function(){
		$(this).find('input[name^=custom_duplicate_name]').autocomplete({
			source: eval('__META_KEYS'),
			minLength: 0
		}).on('click', function () {
			$(this).autocomplete('search', '');
			$(this).attr('rel', '');
		});
	});

	$(document).on('click', '.add-new-entry', function(){
		var $template = $(this).parents('table').first().children('tbody').children('tr.template');
		$number = $(this).parents('table').first().children('tbody').children('tr').length - 2;
		$clone = $template.clone(true);

		$clone.find('div[rel^=serialized]').attr({'id':'serialized_' + $number}).find('table:first').attr({'rel':'serialized_' + $number});
		$clone.find('div[rel^=mapping]').attr({'id':'cf_mapping_' + $number}).find('table:first').attr({'rel':'cf_mapping_' + $number});
		$clone.find('a.specify_cf').attr({'rel':'serialized_' + $number})
		$clone.find('a.pmxi_cf_mapping').attr({'rel':'cf_mapping_' + $number})
		$clone.find('.wpallimport-cf-menu').attr({'id':'wpallimport-cf-menu-' + $number}).menu();
		$clone.find('input[name^=custom_name]').autocomplete({
			source: eval('__META_KEYS'),
			minLength: 0
		}).on('click', function () {
			$(this).autocomplete('search', '');
			$(this).attr('rel', '');
		});
		$clone.insertBefore($template).css('display', 'none').removeClass('template').fadeIn();

		return false;
	});

	$(document).on('click', '.add-new-key', function(){
		var $template = $(this).parents('table').first().find('tr.template');
		$template.clone(true).insertBefore($template).css('display', 'none').removeClass('template').fadeIn();
	});

	/* END Categories hierarchy */

	$('form.options').each(function(){
		var $form = $(this);
		var $uniqueKey = $form.find('input[name=unique_key]');
		var $tmpUniqueKey = $form.find('input[name=tmp_unique_key]');
		$form.find('.wpallimport-auto-detect-unique-key').on('click', function(){
			$uniqueKey.val($tmpUniqueKey.val());
		});
	});

	$('form.edit').each(function(){
		var $form = $(this);
		$form.find('.wpallimport-change-unique-key').on('click', function(){
			var $ths = $(this);
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height: 290,
				width: 550,
				modal: true,
				draggable: false,
				buttons: {
					"Continue": function() {
						$( this ).dialog( "close" );
						$ths.hide();
						$('input[name=unique_key]').removeAttr('disabled').trigger('focus');
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		});
		var $uniqueKey = $form.find('input[name=unique_key]');
		var $tmpUniqueKey = $form.find('input[name=tmp_unique_key]');
		$form.find('.wpallimport-auto-detect-unique-key').on('click', function(){
			$uniqueKey.val($tmpUniqueKey.val());
		});
	});

	// chunk files upload
	if ($('#plupload-ui').length) {
		$('#plupload-ui').show();
		$('#html-upload-ui').hide();

		wplupload = $('#select-files').wplupload({
			runtimes : 'gears,browserplus,html5,flash,silverlight,html4',
			url : 'admin.php?page=pmxi-admin-settings&action=upload&_wpnonce=' + wp_all_import_security,
			container: 'plupload-ui',
			browse_button : 'select-files',
			file_data_name : 'async-upload',
			multipart: true,
			max_file_size: '1000mb',
			chunk_size: '1mb',
			drop_element: 'plupload-ui',
			multipart_params : {}
		});
	}

	/* END plupload scripts */

	$('#view_log').on('click', function(){
		$('#import_finished').css({'visibility':'hidden'});
		$('#logwrapper').slideToggle(100, function(){
			$('#import_finished').css({'visibility':'visible'});
		});
	});

	// Select Encoding
	$('#import_encoding').on('change', function(){
		if ($(this).val() == 'new'){
			$('#select_encoding').hide();
			$('#add_encoding').show();
		}
	});

	$('#cancel_new_encoding').on('click', function(){
		$('#add_encoding').hide();
		$('#select_encoding').show();
		$('#new_encoding').val('');
		$('#import_encoding').prop('selectedIndex', 0);
	});

	$('#add_new_encoding').on('click', function(){
		var new_encoding = $('#new_encoding').val();
		if ("" != new_encoding){
			$('#import_encoding').prepend('<option value="'+new_encoding+'">' + new_encoding + '</option>');
			$('#cancel_new_encoding').trigger('click');
			$('#import_encoding').prop('selectedIndex',0);
		}
		else alert('Please enter encoding.');
	});

	$('input[name=keep_custom_fields]').on('click', function(){
		$(this).parents('.input:first').find('.keep_except').slideToggle();
	});

    $('.pmxi_choosen').each(function(){
    	$(this).find(".choosen_input").select2({
    		tags: $(this).find('.choosen_values').html().split(','),
    		width: '80%',
    	});
    });

    if (typeof wpPointerL10n != "undefined") wpPointerL10n.dismiss = 'Close';

	$('.show_hints').on('click', function(){
		var $ths = $(this);
		$('.wpallimport-overlay').show();

		$(this).pointer({
            content: $('#' + $ths.attr('rel')).html(),
            position: {
                edge: 'right',
                align: 'center'
            },
            pointerWidth: 715,
            close: function() {
                $.post( ajaxurl, {
                    pointer: 'pksn1',
                    action: 'dismiss-wp-pointer'
                });
                $('.wpallimport-overlay').hide();
            }
        }).pointer('open');
	});

	// Serialized Custom Field Dialog
	$('.pmxi_cf_pointer').on('click', function(){
		var $ths = $(this);
		//$('.wpallimport-overlay').show();

		if ($ths.parents('.form-field:first').find('input[name^=custom_name]').val() == "") {
			$('#' + $ths.attr('rel')).find('.auto_detect_sf').hide();
		} else {
			$('#' + $ths.attr('rel')).find('.auto_detect_sf').show();
		}

		$(this).pointer({
            content: $('#' + $ths.attr('rel')).html(),
            position: {
                edge: 'top',
                align: 'center'
            },
            pointerWidth: 450,
            close: function() {
                $.post( ajaxurl, {
                    pointer: 'pksn1',
                    action: 'dismiss-wp-pointer'
                });
                //$('.wpallimport-overlay').hide();
            }
        }).pointer('open');
	});

	// Custom Fields Mapping Dialog
	$('.wpallimport-cf-menu li').on('click', function(){
		var $triggerEvent = $(this).find('a');
		if ($triggerEvent.hasClass('pmxi_cf_mapping')){

			//$('.wpallimport-overlay').show();
			var $ths = $triggerEvent;
			$triggerEvent.pointer({
	            content: $('#' + $ths.attr('rel')).html(),
	            position: {
	                edge: 'right',
	                align: 'center'
	            },
	            pointerWidth: 450,
	            close: function() {
	                $.post( ajaxurl, {
	                    pointer: 'pksn1',
	                    action: 'dismiss-wp-pointer'
	                });
	                //$('.wpallimport-overlay').hide();
	            }
	        }).pointer('open');
		}
	});

	$('.wpallimport-overlay').on('click', function(){
		$('.wp-pointer').hide();
		$('fieldset.wp-all-import-scheduling-help').hide();
		$(this).hide();
	});

	if ($('#wp_all_import_code').length){
		var editor = wp.codeEditor.initialize($('#wp_all_import_code'), wpai_cm_settings);
	    editor.codemirror.setCursor(1);
	    $('.CodeMirror').resizable({
		  resize: function() {
		    editor.setSize("100%", $(this).height());
		  }
		});
		var currentImportFunctions = editor.codemirror.getValue();
		editor.codemirror.on('change',function(cMirror){
			if ( currentImportFunctions != cMirror.getValue()){
				window.onbeforeunload = function () {
					return 'WARNING:\nFunctions are not saved, leaving the page will reset changes in Function editor.';
				};
			} else {
				window.onbeforeunload = false;
			}
		});
	}
	
	function wp_all_import_save_functions(){
		var request = {
			action: 'save_import_functions',
			data: editor.codemirror.getValue(),
			security: wp_all_import_security
		};
		$('.wp_all_import_functions_preloader').show();
		$('.wp_all_import_saving_status').removeClass('error updated').html('');
		return $.ajax({
			type: 'POST',
			url: ajaxurl + ((typeof import_id != "undefined") ? '?id=' + import_id : ''),
			data: request,
			success: function(response) {
				$('.wp_all_import_functions_preloader').hide();
				if (response.result) {
					window.onbeforeunload = false;
					$('.wp_all_import_saving_status').addClass('updated');
					setTimeout(function() {
						$('.wp_all_import_saving_status').removeClass('error updated').html('');
					}, 3000);
				} else {
					$('.wp_all_import_saving_status').addClass('error');
				}
				$('.wp_all_import_saving_status').html(response.msg).show();
			},
			error: function( jqXHR, textStatus ) {
				$('.wp_all_import_functions_preloader').hide();
			},
			dataType: "json"
		});
	}

    $('.wp_all_import_save_functions').on('click', function(){
		$('.cross-sale-notice.codebox').slideUp();
		
    	wp_all_import_save_functions();
    });

    $('.wp_all_import_ajax_deletion').on('click', function(e){
    	e.preventDefault();
    	var $ths = $(this);
    	$(this).attr('disabled', 'disabled');
	    var iteration = 1;
		var request = {
			action: 'delete_import',
			data: $(this).parents('form:first').serialize(),
			security: wp_all_import_security,
			iteration: iteration
		};
		var deleteImport = function(){
			request.iteration = iteration;
			$.ajax({
				type: 'POST',
				url: ajaxurl + ((typeof import_id != "undefined") ? '?id=' + import_id : ''),
				data: request,
				success: function(response) {
					iteration++;
					$ths.parents('form:first').find('.wp_all_import_deletion_log').html('<p>' + response.msg + '</p>');
					if (response.result){
						$('.wp_all_import_functions_preloader').hide();
						window.location.href = response.redirect;
					} else {
						deleteImport();
					}
				},
				error: function( jqXHR, textStatus ) {
					$ths.removeAttr('disabled');
					$('.wp_all_import_functions_preloader').hide();
				},
				dataType: "json"
			});
		}
		$('.wp_all_import_functions_preloader').show();
		deleteImport();
    });

	$('.wpallimport-collapsed').each(function(){
		if ( ! $(this).hasClass('closed')) $(this).find('.wpallimport-collapsed-content:first').slideDown();
	});

	$('.wpallimport-collapsed').find('.wpallimport-collapsed-header').not('.disabled').on('click', function(){
		var $parent = $(this).parents('.wpallimport-collapsed:first');
		if ($parent.hasClass('closed')){
			$parent.removeClass('closed');
			$parent.find('.wpallimport-collapsed-content:first').slideDown(400, function(){
				if ($('#wp_all_import_code').length) editor.codemirror.setCursor(1);
			});
		} else {
			$parent.addClass('closed');
			$parent.find('.wpallimport-collapsed-content:first').slideUp(400);
		}
	});

	$('#is_delete_posts').on('change', function(){
		if ($(this).is(':checked')){
			$('.wpallimport-delete-posts-warning').show();
		} else {
			$('.wpallimport-delete-posts-warning').hide();
		}
	});

	$('.wpallimport-dependent-options').each(function(){
		$(this).prev('div.input').find('input[type=text]:last, textarea:last').addClass('wpallimport-top-radius');
	});

	$('.wpallimport-delete-and-edit, .download_import_template, .download_import_bundle').on('click', function(e){
		e.preventDefault();
    	window.location.href = $(this).attr('rel');
    });

    $('.wpallimport-wpae-notify-read-more').on('click', function(e){
    	e.preventDefault();

    	var request = {
			action: 'dismiss_notifications',
			security: wp_all_import_security,
			addon: $(this).parent('div:first').attr('rel')
	    };

	    var ths = $(this);

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: request,
			success: function(response) {

			},
			dataType: "json"
		});

		$(this).parent('div:first').slideUp();

    	window.open($(this).attr('href'), '_blank');
    });

    // [ Delete Import]
    var wpai_are_sure_to_delete_import = function() {
    	if ( ! $('.delete-single-import').length ) return;

    	$('.delete-single-import').removeAttr('disabled');

    	if ( $('#is_delete_import').is(':checked') || $('#is_delete_posts').is(':checked')) {
    		$('.wp-all-import-sure-to-delete').show();
    	}
    	if ( ! $('#is_delete_import').is(':checked') && ! $('#is_delete_posts').is(':checked')) {
    		$('.wp-all-import-sure-to-delete').hide();
    		$('.delete-single-import').attr('disabled', 'disabled');
    	}
    	if ( $('#is_delete_import').is(':checked') && $('#is_delete_posts').is(':checked')) {
    		$('.sure_delete_posts_and_import').show();
    	}
    	if ($('#is_delete_import').is(':checked')) {
    		$('.sure_delete_import').show();
    	} else {
    		$('.sure_delete_import').hide();
    		$('.sure_delete_posts_and_import').hide();
    	}
    	if ($('#is_delete_posts').is(':checked')) {
    		$('.sure_delete_posts').show();
    	} else {
    		$('.sure_delete_posts').hide();
    		$('.sure_delete_posts_and_import').hide();
    	}
    }

    wpai_are_sure_to_delete_import();

    $('#is_delete_import, #is_delete_posts').on('click', function(){
    	wpai_are_sure_to_delete_import();
    });
    // [\ Delete Import]

	let get_delete_missing_notice_type = function() {
		let $is_delete_missing = $('input#is_delete_missing');
		if (!$is_delete_missing.is(':checked') || $is_delete_missing.data('backups-prompt') === 'disabled') {
			return 0;
		}
		if ($('input[name="delete_missing_logic"]:checked').val() == 'import' && $('input[name="delete_missing_action"]:checked').val() == 'keep' && $('input[name="is_send_removed_to_trash"]').is(':checked')) {
			return 1;
		}
		if ($('input[name="delete_missing_logic"]:checked').val() == 'import' && $('input[name="delete_missing_action"]:checked').val() == 'keep' && $('input[name="is_change_post_status_of_removed"]').is(':checked')) {
			return 2;
		}
		if ($('input[name="delete_missing_logic"]:checked').val() == 'import' && $('input[name="delete_missing_action"]:checked').val() == 'remove') {
			return 3;
		}
		if ($('input[name="delete_missing_logic"]:checked').val() == 'all' && $('input[name="delete_missing_action"]:checked').val() == 'keep' && $('input[name="is_send_removed_to_trash"]').is(':checked')) {
			return 4;
		}
		if ($('input[name="delete_missing_logic"]:checked').val() == 'all' && $('input[name="delete_missing_action"]:checked').val() == 'keep' && $('input[name="is_change_post_status_of_removed"]').is(':checked')) {
			return 5;
		}
		if ($('input[name="delete_missing_logic"]:checked').val() == 'all' && $('input[name="delete_missing_action"]:checked').val() == 'remove') {
			return 6;
		}
		return 0;
	}

	function is_valid_delete_missing_options() {
		let is_valid = true;
		if ( $('input[name="is_delete_missing"]').is(':checked') && $('input[name="delete_missing_action"]:checked').val() == 'keep' ) {
			if ( ! $('input[name="is_send_removed_to_trash"]').is(':checked')
				&& ! $('input[name="is_change_post_status_of_removed"]').is(':checked')
				&& ! $('input[name="is_update_missing_cf"]').is(':checked')
				&& ! $('input[name="missing_records_stock_status"]').is(':checked')
			) {
				is_valid = false;
			}
		}
		return is_valid;
	}

	let delete_missing_helper_text = function() {
		$('.helper-text').hide();
		if ( is_valid_delete_missing_options() ) {
			$('.delete-missing-error').addClass('hidden');
			$('.switcher-target-delete_missing_action_keep').removeClass('delete-missing-error-wrapper');
		} else {
			$('.delete-missing-error').removeClass('hidden');
			$('.switcher-target-delete_missing_action_keep').addClass('delete-missing-error-wrapper');
		}
		let notice_type = get_delete_missing_notice_type();
		// Show notice if any.
		if (notice_type) {
			$('.helper-text-' + notice_type).find('.status_of_removed').html($('select[name="status_of_removed"]').val());
			$('.helper-text-' + notice_type).show();
		}
	};

	delete_missing_helper_text();
	$('.switcher-target-is_delete_missing').find('input, select').on('change', function() {
		delete_missing_helper_text();
	});
	$('#is_delete_missing').on('change', function() {
		delete_missing_helper_text();
	});

    if ($('.switcher-target-update_choosen_data').length) {
    	var $re_import_options = $('.switcher-target-update_choosen_data');
    	var $toggle_re_import_options = $('.wpallimport-trigger-options');

    	if ($re_import_options.find('input[type=checkbox]').length == $re_import_options.find('input[type=checkbox]:checked').length) {
    		var $newtitle = $toggle_re_import_options.attr('rel');
    		$toggle_re_import_options.attr('rel', $toggle_re_import_options.html());
    		$toggle_re_import_options.html($newtitle);
    		$toggle_re_import_options.removeClass('wpallimport-select-all');
    	}
    }

    $('.wpallimport-trigger-options').on('click', function(){
    	var $parent = $(this).parents('.switcher-target-update_choosen_data:first');
    	var $newtitle = $(this).attr('rel');
    	if ( $(this).hasClass('wpallimport-select-all') ) {
    		$parent.find('input[type=checkbox]').not('.exclude-select-all').removeAttr('checked').trigger('click');
    		$(this).removeClass('wpallimport-select-all');
    	} else {
			$parent.find('input[type=checkbox]:checked').not('.exclude-select-all').trigger('click');
    		$(this).addClass('wpallimport-select-all');
    	}
    	$(this).attr('rel', $(this).html());
    	$(this).html($newtitle);
    });

    $('table.pmxi-admin-imports').each(function () {
    	let manage_table = $(this);
    	$(this).find('thead tr th.check-column :checkbox, tfoot tr th.check-column :checkbox').on('click', function () {
    		let is_checked = $(this).is(':checked');
			manage_table.find('tbody tr th.check-column :checkbox').prop('checked', function () {
				if (is_checked) {
					return true;
				}
				return false;
			});
		});
	});

	var fix_tag_position = function(){
		if ($('.wpallimport-layout').length && $('.tag').length && $('.wpallimport-content-section').length){
			let offset_top = $('.wpallimport-content-section').eq(0).offset().top;
			if ($('.xpath_filtering').length) {
				offset_top = $('.wpallimport-content-section').eq(2).offset().top;
			}
			let wordpress_adminbar_height = $('#wpadminbar').height();
			let position_top = $(document).scrollTop() + wordpress_adminbar_height + 20;

			$('.tag').css('margin-top', '0');

			if (position_top > offset_top){
				$('.tag').css({'top': position_top - offset_top});
				$('.wpallimport-xml').css({'max-height': ($(window).height() - 220) + 'px' });
			} else {
				$('.tag').css({'top': '0' });
				$('.wpallimport-xml').css({'max-height': ($(window).height() - 220) + 'px' });
			}
		}
	}

	fix_tag_position();

	$(document).on('scroll', function() {
    	fix_tag_position();
    });

});})(jQuery);
