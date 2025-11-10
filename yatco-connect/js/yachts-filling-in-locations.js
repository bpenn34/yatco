function yt_super_selector_location_search(ele) {





}


jQuery(document).ready(function() {	
	console.log('hello world');

	// Filling Country
	jQuery('.yt-super-select[name=LocationRegionID][data-value]').each(function() {

		if (jQuery(this).data('value') != '') {
			_YBA.label_options({ label: 'GeoListRegionCountries', RegionId: jQuery(this).data('value') }).then(function(lst) {
				yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCountryID]')[0], lst.GeoListRegionCountries).then(function() {

					yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCountryID]'), lst.GeoListRegionCountries);

					YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCountryID]'));

					jQuery('.yt-country-col-field').removeClass('disabled');

					// Filling States
					_YBA.label_options({ label: 'GeoListStates', CountryId: jQuery('.yt-super-select[name=LocationCountryID]').data('value') }).then(function(lst) {

						yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], lst.GeoListStates).then(function() {

							yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationStateID]'), lst.GeoListStates);

							YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationStateID]'));

							if (jQuery('.yt-super-select[name=LocationStateID]').data('value') != '') {
								jQuery('.yt-state-col-field').removeClass('disabled');
							}
							
							// Filling States
							_YBA.label_options({ label: 'GeoListCities', StateId: jQuery('.yt-super-select[name=LocationStateID]').data('value') }).then(function(lst) {

								yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], lst.GeoListCities).then(function() {

									yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCityID]'), lst.GeoListCities);

									YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCityID]'));

									if (jQuery('.yt-super-select[name=LocationCityID]').data('value') != '') {
										jQuery('.yt-city-col-field').removeClass('disabled');
									}

								});
							});

						});
					});
				});


			});
		}


	});

	if (jQuery('.yt-super-select[name=LocationRegionID]').length) {
		_YBA.label_options({ label: 'GeoListRegionsWithActiveCountries' }).then(function(lst) {

			if (jQuery('.yt-super-select[name=LocationRegionID]').length) {
				var cur_input=jQuery('.yt-super-select[name=LocationRegionID]');

				yt_fill_super_options_with_promise(cur_input[0], lst.GeoListRegionsWithActiveCountries).then(function() {

					yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationRegionID]'), lst.GeoListRegionsWithActiveCountries);

					YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationRegionID]'));

					if (jQuery('.yt-super-select[name=LocationRegionID]').data('value') == '') {
						jQuery('.yt-country-col-field').addClass('disabled');
						jQuery('.yt-state-col-field').addClass('disabled');
						jQuery('.yt-city-col-field').addClass('disabled');
					}


					if (jQuery('.yt-super-select[name=LocationRegionID]').data('value') != '') {
						jQuery('.yt-country-col-field').removeClass('disabled');
					}
					

					jQuery('.yt-super-select input[name=LocationRegionID]').each(function() {

						jQuery(this).change(function() {

							if (jQuery(this).val() != '') {
								jQuery('.yt-country-col-field').removeClass('disabled');

								var regionID = jQuery(this).val();

								if (regionID == 1 || regionID == 12) {
									jQuery('.yt-state-col-field').removeClass('disabled');
									jQuery('.yt-state-col-field select').removeAttr('disabled');
								}
								else {
									jQuery('.yt-state-col-field').addClass('disabled');
									jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
								}

								yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], []);
								yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], []);

								_YBA.label_options({ label: 'GeoListRegionCountries', RegionId: jQuery(this).val() }).then(function(lst) {

									console.log(lst);
									
									console.log(jQuery('.yt-super-select[name=LocationCountryID]')[0]);

									yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCountryID]')[0], lst.GeoListRegionCountries).then(function() {

										yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCountryID]'), lst.GeoListRegionCountries);

										YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCountryID]'));
										
										jQuery('.yt-super-select input[name=LocationCountryID]').each(function() {
												
											jQuery(this).change(function() {

												var CountryID = jQuery(this).val();

												jQuery('.yt-state-col-field').removeClass('disabled');
												jQuery('.yt-city-col-field').removeClass('disabled');

												//seet classes
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

												yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], []);
												yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], []);

												// set options

												if (
													CountryID == 233 || CountryID == 39
													||
													CountryID == 14 || CountryID == 158
												) {
													_YBA.label_options({ label: 'GeoListStates', CountryId: jQuery(this).val() }).then(function(lst) {

														yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], lst.GeoListStates).then(function() {

															yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationStateID]'), lst.GeoListStates);

															YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationStateID]'));

															jQuery('.yt-super-select input[name=LocationStateID]').each(function() {
																jQuery(this).change(function() {

																	_YBA.label_options({ label: 'GeoListCities', StateId: jQuery(this).val() }).then(function(lst) {

																		yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], lst.GeoListCities).then(function() {

																			yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCityID]'), lst.GeoListCities);
					
																			YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCityID]'));


																		});
																	});


																});
															});



														});
													});
												}
												else {
													// country -> cities

													_YBA.label_options({ label: 'GeoListCountryCities', CountryId: jQuery(this).val() }).then(function(lst) {

														yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], lst.GeoListCountryCities).then(function() {

															yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCityID]'), lst.GeoListCountryCities);

															YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCityID]'));

														

														});
													});
												}


											});

										});
									});

								});
							}
							else {
								jQuery('.yt-country-col-field').addClass('disabled');
								jQuery('.yt-state-col-field').addClass('disabled');
								jQuery('.yt-city-col-field').addClass('disabled');
							}

						});

					});


				});
			}
		});

	}
});