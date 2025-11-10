function yt_sync_input(ele_input, values) {

	if (!ele_input.attr('dont-touch')) {

		var name = ele_input.attr('name');
		var _val = null;

		// name = name.replace('_', '');
		//console.log(name);

		if (typeof values[name] != 'undefined' && values[name] != null) {
			_val = values[name];
		}
		else if (typeof values[name.toLowerCase()] != 'undefined' && values[name.toLowerCase()] != null) {
			_val = values[name.toLowerCase()];
		}
		else {
			_val = null;
		}

		//console.log(_val);

		if (typeof _val != 'undefined' && _val != null) {

			if (ele_input.attr('type') == 'checkbox' || ele_input.attr('type') == 'radio') {

				if (ele_input.attr('value') == _val) {

					ele_input.attr('checked', 'checked');

				}

			}
			else {
				ele_input.val(_val);
			}

		}

	}

}

function yt_input_number_formatter() {
	var val = jQuery(this).val();

	val = decodeURIComponent(val);

	console.log(val);

	var number_str = val.match(/(\d*)/g);

	if (Array.isArray(number_str)) {
		number_str = number_str.join('');
	}

	var formatted = new Intl.NumberFormat('en').format(number_str);

	if (formatted != NaN && formatted != 'NaN' && formatted !== "0" && formatted !== 0) {
		jQuery(this).val(formatted);
	}
}

function yt_ele_input_number_formatter(j_ele) {
	var val = j_ele.val();

	val = decodeURIComponent(val);

	console.log(val);

	var number_str = val.match(/(\d*)/g);

	if (Array.isArray(number_str)) {
		number_str = number_str.join('');
	}

	var formatted = new Intl.NumberFormat('en').format(number_str);

	if (formatted != NaN && formatted != 'NaN' && formatted !== "0" && formatted !== 0) {
		j_ele.val(formatted);
	}
}

function run_through_and_sync_form(jquery_ele, values) {

	var form_name = jquery_ele.attr('id');

	jQuery('input[name], select[name], textarea[name]', jquery_ele).each(function () {

		yt_sync_input(jQuery(this), values);

	});

	jQuery('input[name][form="' + form_name + '"], select[name][form="' + form_name + '"], textarea[name][form="' + form_name + '"]').each(function () {

		yt_sync_input(jQuery(this), values);

	});


	jQuery('input[name="pricerange_from"], input[name="pricerange_to"], input[name="loa_from"], input[name="loa_to"]', jquery_ele).each(function () {
		var $input = jQuery(this);
		// attach events
		$input.on('input', yt_input_number_formatter);
		$input.on('change', yt_input_number_formatter);

		// delay initial format so value isn’t lost
		setTimeout(function () {
			yt_ele_input_number_formatter($input);
		}, 50);
	});
}

function isElementInViewport(el) {

	// Special bonus for those using jQuery
	if (typeof jQuery === "function" && el instanceof jQuery) {
		el = el[0];
	}

	var rect = el.getBoundingClientRect();

	return (
		rect.top >= 0 &&
		rect.left >= 0 &&
		rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /* or $(window).height() */
		rect.right <= (window.innerWidth || document.documentElement.clientWidth) /* or $(window).width() */
	);
}

// Reset Search Form
function resetSearchForm(e) {
	e.preventDefault();

	//document.getElementById("desktop-quick-search").reset();
	//document.getElementById("desktop-expanded-search").reset();

	jQuery('form[data-shortcode-attributes]').each(function () {

		jQuery(this)[0].reset();

		var values = jQuery(this).data('shortcode-attributes');

		if (typeof values['load-more-button'] != 'undefined' && values['load-more-button'] == 1 || values['load-more-button'] == "1") {
			values.page_size = 12;
			values.page_index = 1;
		}

		run_through_and_sync_form(jQuery(this), values);
	});

}

function yt_js_resetSearchFormAndResult(e) {
	e.preventDefault();

	//document.getElementById("desktop-quick-search").reset();
	//document.getElementById("desktop-expanded-search").reset();

	jQuery('form[data-shortcode-attributes]').each(function () {

		jQuery(this)[0].reset();

		var values = jQuery(this).data('shortcode-attributes');

		if (typeof values['load-more-button'] != 'undefined' && (values['load-more-button'] == 1 || values['load-more-button'] == "1")) {
			values.page_size = 12;
			values.page_index = 1;
		}

		console.log(values);

		run_through_and_sync_form(jQuery(this), values);

		get_vessels_and_render(values);

	});

}

// Contact Modal
function openContactModal(MLSID, CompanyID, CompanyName, BrokerName) {

	jQuery('#yatco-contact-modal input[name=VesselID]').val(MLSID);
	jQuery('#yatco-contact-modal input[name=CompanyID]').val(CompanyID);

	jQuery('#yatco-contact-modal .broker-name').text(BrokerName);
	jQuery('#yatco-contact-modal .brokerage').text(CompanyName);

	jQuery("#yatco-contact-modal").yatco_modal({
		closeText: 'X'
	});

	yt_ga_event('Modal', 'Open', 'Modal Opened: Vessel Lead', '');

	// Restting Form 
	jQuery('#yatco-contact-modal textarea[name="Message"]').val('');

	jQuery('#yatco-contact-modal .hide-after-submit').show();

	jQuery('#yatco-contact-modal .form-success-message').hide();

}

function openVdContactModal(MLSID, CompanyID, CompanyName, BrokerName) {

	jQuery('#yatco-vd-contact-modal input[name=VesselID]').val(MLSID);
	jQuery('#yatco-vd-contact-modal input[name=CompanyID]').val(CompanyID);

	jQuery("#yatco-vd-contact-modal").yatco_modal({
		closeText: 'X'
	});

	yt_ga_event('Modal', 'Open', 'Modal Opened: Vessel Directory Lead', '');

	// Restting Form 
	jQuery('#yatco-vd-contact-modal textarea[name="Message"]').val('');

	jQuery('#yatco-vd-contact-modal .hide-after-submit').show();

	jQuery('#yatco-vd-contact-modal .form-success-message').hide();

}

function openBrokerContactModal(BrokerID, CompanyName, BrokerName) {

	jQuery('#single-company-modal-email-broker input[name=BrokerID]').val(BrokerID);

	jQuery('#single-company-modal-email-broker .broker-name').text(BrokerName);
	jQuery('#single-company-modal-email-broker .brokerage').text(CompanyName);

	jQuery("#single-company-modal-email-broker").yatco_modal({
		closeText: 'X'
	});

	yt_ga_event('Modal', 'Open', 'Modal Opened: Broker Lead', '');

	// Restting Form 
	jQuery('#single-company-modal-email-broker textarea[name="Message"]').val('');

	jQuery('#single-company-modal-email-broker .hide-after-submit').show();

	jQuery('#single-company-modal-email-broker .form-success-message').hide();

}

// openCharterLeadModal

function openCharterLeadModal(MLSID, CompanyID) {
	jQuery('#yatco-charter-lead-modal input[name=VesselID]').val(MLSID);
	jQuery('#yatco-charter-lead-modal input[name=CompanyID]').val(CompanyID);

	// document.querySelector('#yatco-contact-modal .brokerage').innerHTML=CompanyName;

	jQuery("#yatco-charter-lead-modal").yatco_modal({
		closeText: 'X'
	});

	if (typeof ga != 'undefined') {
		ga('send', 'event', 'Modal', 'Open', 'Charter Lead Modal From Results Page');
		ga('send', 'event', MLSID, 'Lead', 'Open');
	}

	// Restting Form 
	jQuery('#yatco-charter-lead-modal textarea[name="Message"]').val('');

	jQuery('#yatco-charter-lead-modal .hide-after-submit').show();

	jQuery('#yatco-charter-lead-modal .form-success-message').hide();
}

jQuery(document).ready(function () {
	var searchParams = new URLSearchParams(window.location.search);

	var shortcode_form_attr_data = jQuery('.big-form, .yatco-shortcode-search-form form').data('shortcode-attributes');

	if (jQuery('.yatco-shortcode-search-form form').length == 0 && jQuery('.yatco-shortcode-yacht-results').length > 0) {
		var shortcode_attr_data = jQuery('.yatco-shortcode-yacht-results').data('shortcode-attributes');

		shortcode_attr_data.override_with_attrs = '1';

		jQuery('body').append(`
			<div class="yatco-shortcode-yachts-search-form">
				<form id="desktop-expanded-search" data-shortcode-attributes>
					<input type="hidden" name="fromForm" value="1">
				</form>
			</div>
		`);


		jQuery('#desktop-expanded-search').data('shortcode-attributes', shortcode_attr_data);
	}

	jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr(
		'form',
		jQuery('.yatco-shortcode-search-form form').attr('id')
	);

	// Expand Search
	jQuery('.small-form .expand-search, .big-form .close-advandenced-search').click(function (event) {

		jQuery('.small-form').toggle();
		jQuery('.big-form').toggle();

		var expand_search_is_open = (jQuery('.big-form').css('display') == 'block');

		jQuery('.yatco-shortcode-yachts-search-form').data(
			'is-advanced-search-open',
			(expand_search_is_open) ? 1 : 0
		);

		jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr(
			'form',
			(expand_search_is_open) ? jQuery('.big-form').attr('id') : jQuery('.small-form').attr('id')
		);
	});

	if (
		searchParams.get("openSearchForm") == 'yes'
		||
		(typeof shortcode_form_attr_data == 'object' && typeof shortcode_form_attr_data.opensearchform != 'undefined' && shortcode_form_attr_data.opensearchform == 'yes')
	) {
		jQuery('.yatco-shortcode-yachts-search-form').data('is-advanced-search-open', 1);

		jQuery('.small-form').hide();
		jQuery('.big-form').show();

		jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr(
			'form',
			jQuery('.big-form').attr('id')
		);
	}
	else {
		if (jQuery('.yatco-shortcode-search-form form').length == 1) {
			jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr(
				'form',
				jQuery('.small-form').attr('id')
			);
		}

	}

	// Scroll To Results
	if (searchParams.has('page_index') || searchParams.get('openSearchForm') || searchParams.get('scroll-to-form')) {
		//document.getElementById('yatco-scroll-to-once-searched').scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"});

		if (jQuery('#yatco-scroll-to-once-searched').length) {
			jQuery([document.documentElement, document.body]).animate({
				scrollTop: (jQuery("#yatco-scroll-to-once-searched").offset().top - 75)
			}, 1000);
		}
	}

	// View All
	var more_link_html = '<div class="yt-text-center view-all-button"><a href="#" class="yt-btn read-more">VIEW ALL</a></div>';
	var less_link_html = '<div class="yt-text-center view-all-button"><a href="#" class="yt-btn read-less">VIEW LESS</a></div>';

	new Readmore('.yatco-shortcode-yacht-results.view-all-button', {
		speed: 75,
		collapsedHeight: (jQuery('.yatco-shortcode-yacht-results .col-yacht').height() * 3),
		moreLink: more_link_html,
		lessLink: less_link_html
	});

	new Readmore('.card-container.view-all-button', {
		speed: 75,
		collapsedHeight: (jQuery('.card-container .col-yacht').height() * 3),
		moreLink: more_link_html,
		lessLink: less_link_html
	});

	// View Style
	jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').each(function () {

		jQuery(this).change(function () {

			var form_id = jQuery(this).attr('form');

			jQuery('#' + form_id).submit();

		});
	});

	jQuery('.yatco-shortcode-yacht-results select[name=sortField], .yatco-shortcode-yacht-results select[name=sortDirection]').each(function () {

		jQuery(this).change(function () {
			jQuery('.big-form').submit();

		});
	});

	// Reset Search Form
	jQuery('.big-form [type=reset]').click(function (e) {
		e.preventDefault();

		jQuery('form[data-shortcode-attributes]').each(function () {
			jQuery(this)[0].reset();

			var values = jQuery(this).data('shortcode-attributes');

			run_through_and_sync_form(jQuery(this), values);
		});

	});

	jQuery('input[name="pricerange_from"], input[name="pricerange_to"], input[name="loa_from"], input[name="loa_to"]').each(function () {

		jQuery(this).attr('type', 'text');//.autoNumeric('init'); 

		jQuery(this).on('input', yt_input_number_formatter);
		jQuery(this).on('change', yt_input_number_formatter);

		yt_ele_input_number_formatter(jQuery(this));

	});
	/*
	
		jQuery('input[name="pricerange_to"]').attr('type', 'text').autoNumeric('init'); 
		jQuery('input[name="loa_from"]').attr('type', 'text').autoNumeric('init'); 
		jQuery('input[name="loa_to"]').attr('type', 'text').autoNumeric('init'); 	*/

	jQuery('form[data-shortcode-attributes]').each(function () {

		//var values={"SetMinMax": {"loa_to": {"min": 0, "max": "51"}}, "FirstArgs": {'loa_to': 51}};
		var values = jQuery(this).data('shortcode-attributes');

		// var input_names=Object.keys(values['SetMinMax']);

		var jquery_ele = jQuery(this);

		if (typeof values.SetMinMax != 'undefined') {

			jQuery('input[name], select[name], textarea[name]', jquery_ele).each(function () {

				if (!jQuery(this).attr('dont-touch')) {

					var name = jQuery(this).attr('name');
					var _val = null;

					//console.log(values['SetMinMax'][name]);

					if (typeof values.SetMinMax[name] != 'undefined') {
						var min_and_max = values.SetMinMax[name];

						jQuery(this).attr(min_and_max);

						if (
							name == 'pricerange_from' || name == 'pricerange_to'
							||
							name == 'loa_to' || name == 'loa_from'
						) {

							jQuery(this).attr('type', 'text').autoNumeric('update', {

								'vMin': min_and_max.min,
								'vMax': min_and_max.max,

							});

						}

					}
				}

			});

		}
	});

	// Search Accordions
	var acc = document.getElementsByClassName("yt-search-accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function () {
			/* Toggle between adding and removing the "active" class,
			to highlight the button that controls the panel */
			this.classList.toggle("active");

			/* Toggle between hiding and showing the active panel */
			var panel = this.nextElementSibling;

			if (panel.style.display === "block") {
				panel.style.display = "none";
			} else {
				panel.style.display = "block";
			}
		});
	}


});