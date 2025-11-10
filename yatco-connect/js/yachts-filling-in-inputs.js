//helper for fixing large list issue for mobile
function setupFilteredAutocomplete(inputSelector, datalistId, dataSource, getValueFn) {
	var input = jQuery(inputSelector);
	var datalist = jQuery('#' + datalistId);
	var typingTimer;
	var isFiltering = false;

	input.on('input', function () {
		clearTimeout(typingTimer);
		var searchTerm = this.value.toLowerCase().trim();

		// Clear if less than 2 characters
		if (searchTerm.length < 2) {
			if (!isFiltering) {
				datalist.empty();
			}
			return;
		}

		typingTimer = setTimeout(function () {
			isFiltering = true;
			datalist.empty();

			// Filter matches
			var matches = [];
			for (var i = 0; i < dataSource.length && matches.length < 50; i++) {
				var value = getValueFn(dataSource[i]);
				if (value.toLowerCase().indexOf(searchTerm) !== -1) {
					matches.push(value);
				}
			}

			// Use fragment for single DOM update
			if (matches.length > 0) {
				var fragment = document.createDocumentFragment();
				for (var i = 0; i < matches.length; i++) {
					var opt = document.createElement('option');
					opt.value = matches[i];
					fragment.appendChild(opt);
				}
				datalist[0].appendChild(fragment);
			}

			isFiltering = false;
		}, 250);
	});

	// Clear on blur to save memory
	input.on('blur', function () {
		setTimeout(function () {
			datalist.empty();
		}, 200);
	});
}


// Fill Select Inputs With Options
function yt_fill_options(ele, list) {
	for (var i = 0; i < list.length; i++) {
		if (list[i].id != null && list[i].text != '') {

			var new_option = document.createElement('option');

			new_option.text = list[i].text;

			if (ele.getAttribute('data-yatco-fil-just-text-val') == 'true') {
				new_option.value = list[i].text;
				new_option.setAttribute('data-id', list[i].id);
			}
			else {
				new_option.value = list[i].id;
			}


			ele.options.add(new_option);
		}
	}
}

function yt_init_super_selector(ele) {
	var attr_label = ele.data('yatco-fill-options');
	var cur_input = ele;

	console.log(attr_label);

	_YBA.label_options(attr_label).then(function (opts) {
		yt_data_fills[attr_label] = opts[yt_data_fills];

		yt_fill_super_options(cur_input[0], opts[attr_label]);

		cur_input.attr('filled', 'filled');

		yt_init_super_selector_search_and_uncheck(cur_input, opts[attr_label]);

		YT_SelectAfterLoading(cur_input);

	});

}

function yt_init_super_selector_search_and_uncheck(cur_input, list) {
	console.log(cur_input);
	console.log(list);

	if (cur_input.hasClass('inited')) {

		jQuery('.search', cur_input).unbind("input");

		jQuery('.search', cur_input).on('input', function () {

			var filtered_results = list.filter(function (opt) {
				var lower_val = opt.text.toLowerCase();
				var input_val_lower = (jQuery('.search', cur_input).val()).toLowerCase();

				return lower_val.indexOf(input_val_lower) != -1;
			});

			yt_fill_super_options_hide(cur_input[0], filtered_results);
		});

		/*jQuery('.uncheck-all', cur_input).click(function() {

			jQuery('.yt-super-option', cur_input).each(function() {

				jQuery('input', jQuery(this)).prop('checked', false);

			});

		});*/

	}
	else {
		cur_input.addClass('inited');

		cur_input.prepend('<input type="text" class="search" placeholder="Search..">');

		jQuery('.search', cur_input).on('input', function () {

			var filtered_results = list.filter(function (opt) {
				var lower_val = opt.text.toLowerCase();
				var input_val_lower = (jQuery('.search', cur_input).val()).toLowerCase();

				return lower_val.indexOf(input_val_lower) != -1;
			});

			yt_fill_super_options_hide(cur_input[0], filtered_results);
		});

		jQuery('.uncheck-all', cur_input).click(function () {

			jQuery('.yt-super-option', cur_input).each(function () {

				jQuery('input', jQuery(this)).prop('checked', false);

			});

		});
	}

}

function yt_fill_super_options(ele, list) {

	jQuery('.yt-super-options', ele).html('');

	var input_name = jQuery(ele).attr('name');

	for (var i = 0; i < list.length; i++) {
		if (list[i].id != null && list[i].text != '') {
			//console.log(list[i].text);

			var new_option = document.createElement('div');
			new_option.className = 'yt-super-option';

			new_option.innerHTML = `<label id="option-${list[i].id}" data-boss-id="${list[i].id}"><input type="radio" name="${input_name}" value="${list[i].id}">${list[i].text}</label>`;

			jQuery('.yt-super-options', ele).append(new_option);
		}
	}

	jQuery('.yt-super-option', ele).each(function () {

		jQuery('label', jQuery(this)).click(function () {

			if (jQuery('input', jQuery(this)).prop('checked')) {
				jQuery(this).addClass('checked');
			}
			else {
				jQuery(this).removeClass('checked');
			}

		});
	});
}

function yt_fill_super_options_with_promise(ele, list) {

	return new Promise(function (resolve, reject) {

		jQuery('.yt-super-options', ele).html('');

		var input_name = jQuery(ele).attr('name');

		for (var i = 0; i < list.length; i++) {
			if (list[i].id != null && list[i].text != '') {
				//console.log(list[i].text);

				var new_option = document.createElement('div');
				new_option.className = 'yt-super-option';

				new_option.innerHTML = `<label id="option-${list[i].id}" data-boss-id="${list[i].id}"><input type="radio" name="${input_name}" value="${list[i].id}">${list[i].text}</label>`;

				jQuery('.yt-super-options', ele).append(new_option);
			}
		}

		jQuery('.yt-super-option', ele).each(function () {

			jQuery('label', jQuery(this)).click(function () {

				if (jQuery('input', jQuery(this)).prop('checked')) {
					jQuery(this).addClass('checked');
				}
				else {
					jQuery(this).removeClass('checked');
				}

			});
		});

		resolve();

	});

}

function yt_fill_super_options_hide(ele, list) {

	var list_just_ids = list.map(function (opt) { return opt.id; });

	jQuery('.yt-super-options label', ele).each(function () {

		var id = jQuery(this).data('boss-id');

		if (list_just_ids.indexOf(id) == -1) {
			jQuery(this).addClass('opt-hide').removeClass('opt-show');
		}
		else {
			jQuery(this).addClass('opt-show').removeClass('opt-hide');
		}
	});
}

// Select After Loading
function YT_SelectAfterLoading(ele) {

	if (jQuery(ele).data('value') !== "") {

		if (jQuery(ele).hasClass('yt-super-select')) {
			var input_name = jQuery(ele).attr('name');
			var input_val = ele.data('value');

			jQuery('input[name="' + input_name + '"][value="' + input_val + '"]', jQuery(ele)).prop('checked', true);
			jQuery('input[name="' + input_name + '"][value="' + input_val + '"]', jQuery(ele)).parent().addClass('checked');
		}
		else {

			jQuery(ele).val(jQuery(ele).data('value'));

		}

	}

}

// Countries
function GetCountryList(value) {
	value = value || '';

	if (jQuery('.fill-country').length > 0 && value != '') {
		_YBA.label_options({ label: 'GeoListActiveVesselRegionCountries', RegionId: value }).then(function (lst) {
			GetCountryList_Success(lst.GeoListActiveVesselRegionCountries);
		});
	}

}

function GetCountryList_Success(lst) {
	var dd = jQuery('.fill-country');
	dd.empty();

	if (lst.length == 0) {
		dd.append('<option value="">None</option>');
	}
	else {
		dd.append('<option value="">Any Country</option>');

		for (var i = 0; i < lst.length; i++) {
			if (dd.attr('data-yatco-fil-just-text-val') == 'true') {
				dd.append('<option data-id="' + lst[i].id + '" value="' + lst[i].text + '">' + lst[i].Country + '</option>');
			}
			else {
				dd.append('<option value="' + lst[i].id + '">' + lst[i].text + '</option>');
			}


		}
	}

	YT_SelectAfterLoading(dd);
}



// STATES
function GetStateList(value) {
	value = value || '';

	if (jQuery('.fill-state').length > 0 && value != '') {
		_YBA.label_options({ label: 'States', countrySEO: value }).then(function (lst) {
			GetStateList_Success(lst.States);
		});
	}
}

function GetGeoStateList(value) {
	value = value || '';

	if (jQuery('.fill-state').length > 0 && value != '') {
		_YBA.label_options({ label: 'GeoListActiveVesselStates', CountryId: value }).then(function (lst) {
			GetStateList_Success(lst.GeoListActiveVesselStates);
		});
	}
}

function GetStateList_Success(lst) {
	var dd = jQuery('.fill-state');
	dd.empty();

	if (lst.length == 0) {
		dd.append('<option value="">None</option>');
	}
	else {
		dd.append('<option value="">Any State</option>');

		for (var i = 0; i < lst.length; i++) {
			if (dd.attr('data-yatco-fil-just-text-val') == 'true') {
				dd.append('<option data-id="' + lst[i].id + '" value="' + lst[i].text + '">' + lst[i].text + '</option>');
			}
			else {
				dd.append('<option value="' + lst[i].id + '">' + lst[i].text + '</option>');
			}


		}
	}

	YT_SelectAfterLoading(dd);
}

// CITIES
function GetGeoCityList(value) {
	value = value || '';

	if (jQuery('.fill-state').length > 0 && value != '') {
		_YBA.label_options({ label: 'GeoListActiveCities', StateId: value }).then(function (lst) {
			GetCityList_Success(lst.GeoListActiveCities);
		});
	}
}

function GetGeoCountryCityList(value) {
	value = value || '';

	if (jQuery('.fill-state').length > 0 && value != '') {

		_YBA.label_options({ label: 'GeoListActiveVesselCountryCities', CountryId: value }).then(function (lst) {
			GetCityList_Success(lst.GeoListActiveVesselCountryCities);
		});

	}
}

function GetCityList_Success(lst) {
	var dd = jQuery('.fill-city');
	dd.empty();

	if (lst.length == 0) {
		dd.append('<option value="">None</option>');
	}
	else {
		dd.append('<option value="">Any City</option>');

		for (var i = 0; i < lst.length; i++) {
			dd.append('<option value="' + lst[i].id + '">' + lst[i].text + '</option>');
		}
	}

	YT_SelectAfterLoading(dd);
}

// Filling Association Regions
function GetAssociationRegions(lst) {
	jQuery('.fill-association-region').each(function () {
		var ddd = jQuery(this);

		_YBA.label_options({
			label: 'AssociationRegions',
			'id': jQuery(this).data('association-id'),
		}).then(function (lst) {
			var dd = ddd;
			dd.empty();
			dd.append('<option value="">All</option>');

			yt_fill_options(dd[0], lst.AssociationRegions);

			YT_SelectAfterLoading(dd);
		});
	});
}

/// data-yatco-fill-options
var yt_data_fills = {};

function yatco_data_fill_options(root_ele) {
	console.log(yt_data_fills);

	var list_of_fills = [];

	jQuery('select[data-yatco-fill-options]:not([filled])', root_ele).each(function () {
		var attr_label = jQuery(this).data('yatco-fill-options');
		var cur_input = jQuery(this);

		if (list_of_fills.indexOf(attr_label) == -1) {
			list_of_fills.push(attr_label);
		}
	});

	for (const property in yt_data_fills) {
		console.log(property);

		if (list_of_fills.indexOf(property) != -1) {

			var index = list_of_fills.indexOf(property);

			console.log('remoe');

			delete list_of_fills[index];

		}

	}

	if (list_of_fills.length >= 1) {

		_YBA.label_options(list_of_fills).then(function (opts) {
			for (const property in opts) {
				var options = opts[property];

				yt_data_fills[property] = options;

				jQuery('select[data-yatco-fill-options="' + property + '"]').each(function () {
					var attr_label = jQuery(this).data('yatco-fill-options');
					var cur_input = jQuery(this);

					if (cur_input.data('yatco-empty-after-fill') == true) {
						cur_input.empty();
					}

					yt_fill_options(cur_input[0], options);

					YT_SelectAfterLoading(cur_input);

				});
			}
		});

	}

	jQuery('.yt-super-select[data-yatco-fill-options]:not([filled])', root_ele).each(function () {

		yt_init_super_selector(jQuery(this));

		console.log(jQuery(this)[0]);

	});

}

jQuery(document).ready(function () {
	// Resoting Form Data
	jQuery('form[data-input-values]').each(function () {

		var values = jQuery(this).data('input-values');

		run_through_and_sync_form(jQuery(this), values);

	});

	if (
		(jQuery('.yatco-js-shortcode-yacht-results, .yatco-js-shortcode-yachts-search-form').length > 0)
		||
		(jQuery('.yatco-js-shortcode-charter-results, .yatco-js-shortcode-charter-search-form').length > 0)
	) {

		var root_ele = jQuery('.yatco-js-shortcode-yachts-search-form');

		jQuery('.yt-super-select[data-yatco-fill-options]:not([filled])', root_ele).each(function () {

			yt_init_super_selector(jQuery(this));

		});

	}
	else {
		// data-yatco-fill-options
		yatco_data_fill_options(jQuery('.small-form, .big-form, .yatco-shortcode-yacht-results, .yatco-shortcode-search-form'));

		jQuery('.small-form, .big-form').on('change', function (e) {

			var params = get_form_data(jQuery(this)[0]);

			run_through_and_sync_form(jQuery('.small-form, .big-form'), params);

		});
	}

	GetAssociationRegions();

	// Filling Country
	jQuery('select[data-yatco-fill-options="GeoListRegionsWithActiveCountries"]').each(function () {

		var regionID = jQuery(this).data('value');

		if (regionID == 1 || regionID == 12) {
			jQuery('.yt-state-col-field').removeClass('disabled');
			jQuery('.yt-state-col-field select').removeAttr('disabled');
		}
		else {
			jQuery('.yt-state-col-field').addClass('disabled');
			jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
		}

		GetCountryList(regionID);

	}).on('change', function () {

		var regionID = jQuery(this).val();

		if (regionID == 1 || regionID == 12) {
			jQuery('.yt-state-col-field').removeClass('disabled');
			jQuery('.yt-state-col-field select').removeAttr('disabled');
		}
		else {
			jQuery('.yt-state-col-field').addClass('disabled');
			jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
		}

		jQuery('select[name="LocationCountryID"]').html('<option value="">Pick Region First...</option>');
		jQuery('select[name="LocationStateID"]').html('<option value="">Pick Country First...</option>');
		jQuery('select[name="LocationCityID"]').html('<option value="">Pick State First...</option>');

		GetCountryList(regionID);

	});


	// Filling States
	/*jQuery('select[data-yatco-fill-options="Countries"]').each(function() {
		
		GetStateList(jQuery(this).data('value'));


	}).on('change', function () {


		var CountryID = jQuery(this).val();

		GetStateList(CountryID);


	});
*/
	jQuery('select[name="LocationCountryID"]').each(function () {

		var CountryID = jQuery(this).data('value');

		if (
			CountryID == 233 || CountryID == 39
			||
			CountryID == 14 || CountryID == 158
		) {

			jQuery('.yt-state-col-field').removeClass('disabled');
			jQuery('.yt-state-col-field select').removeAttr('disabled');

			jQuery('select[name="LocationStateID"]').html('<option value="">Pick Country First...</option>');
			jQuery('select[name="LocationCityID"]').html('<option value="">Pick State First...</option>');
			GetGeoStateList(CountryID);

		}
		else {
			jQuery('.yt-state-col-field').addClass('disabled');
			jQuery('.yt-state-col-field select').attr('disabled', 'disabled');

			jQuery('select[name="LocationCityID"]').html('<option value="">Pick Country First...</option>');

			GetGeoCountryCityList(CountryID);
		}


	}).on('change', function () {


		// USA (location ID 233), Canada (location ID 39) 
		// Australia (location ID 14), and New Zealand (location ID 158).

		if (jQuery(this).attr('data-yatco-fil-just-text-val') == 'true') {
			GetGeoStateList(jQuery(this).data('id'));

			if (
				CountryID == 233 || CountryID == 39
				||
				CountryID == 14 || CountryID == 158
			) {


				jQuery('.yt-state-col-field').removeClass('disabled');
				jQuery('.yt-state-col-field select').removeAttr('disabled');

			}
			else {

				jQuery('.yt-state-col-field').addClass('disabled');
				jQuery('.yt-state-col-field select').attr('disabled', 'disabled');

			}
		}
		else {

			var CountryID = jQuery(this).val();

			if (
				CountryID == 233 || CountryID == 39
				||
				CountryID == 14 || CountryID == 158
			) {

				jQuery('.yt-state-col-field').removeClass('disabled');
				jQuery('.yt-state-col-field select').removeAttr('disabled');

				GetGeoStateList(CountryID);

			}
			else {
				jQuery('.yt-state-col-field').addClass('disabled');
				jQuery('.yt-state-col-field select').attr('disabled', 'disabled');

				GetGeoCountryCityList(CountryID);
			}

		}

	});

	// Filling Cities

	jQuery('select[name="LocationStateID"]').each(function () {

		GetGeoCityList(jQuery(this).data('value'));

	}).on('change', function () {

		GetGeoCityList(jQuery(this).val());

	});

	// fill #builders_list
	if (jQuery('#builders_list').length > 0 && jQuery('#vessel_names_list').length > 0) {
		_YBA.call_api(
			"POST",
			'form-data-common',
			{
				label: 'ActiveBuilders,VesselNames',
				//label: 'PopularBuilders', 
				filter: ''
			}
		).then(function (data) {
			yt_data_fills['ActiveBuilders'] = data.ActiveBuilders;

			yt_data_fills['VesselNames'] = data.VesselNames;

			setupFilteredAutocomplete(
				'input[name="VesselName"][list]',
				'vessel_names_list',
				yt_data_fills.VesselNames,
				function (item) { return item; }
			);

			// Setup filtered autocomplete for Builders
			setupFilteredAutocomplete(
				'input[name="Builder"][list]',
				'builders_list',
				yt_data_fills.ActiveBuilders,
				function (item) { return item.text; }
			);




		});

	}
	else {
		if (jQuery('#builders_list').length > 0) {
			_YBA.call_api(
				"POST",
				'form-data-common',
				{
					label: 'ActiveBuilders',
					//label: 'PopularBuilders', 
					filter: ''
				}
			).then(function (data) {
				yt_data_fills['ActiveBuilders'] = data.ActiveBuilders;

				setupFilteredAutocomplete(
					'input[name="Builder"][list]',
					'builders_list',
					yt_data_fills.ActiveBuilders,
					function (item) { return item.text; }
				);
			});
		}

		if (jQuery('#vessel_names_list').length > 0) {
			_YBA.call_api(
				"POST",
				'form-data-common',
				{ label: 'VesselNames', filter: '' }
			).then(function (data) {

				yt_data_fills['VesselNames'] = data.VesselNames;

				setupFilteredAutocomplete(
					'input[name="VesselName"][list]',
					'vessel_names_list',
					yt_data_fills.VesselNames,
					function (item) { return item; }
				);
			});
		}
	}

	if (jQuery('#destinations_list').length > 0) {
		_YBA.call_api(
			"POST",
			'form-data-common',
			{ label: 'DDCharterDestinations', filter: '' }
		).then(function (data) {

			jQuery('#destinations_list').html('');

			var list = [];

			for (var b = 0; b < data.DDCharterDestinations.length; b++) {

				var item = data.DDCharterDestinations[b];

				list.push(item.text.replace('-- ', ''));

			}

			list.sort();

			for (var b = 0; b < list.length; b++) {
				jQuery('#destinations_list').append('<option value="' + list[b] + '"></option>');
			}

		});
	}


});