function yt_category_based_off_of_type(ele) {

	if (ele.val() == '1' || ele.val() === 1 ) {
		_YBA.label_options({ label: 'SailCategories' }).then(function(lst) {
			jQuery('select[name=MainCategoryID]').empty();

			jQuery('select[name=MainCategoryID]').prepend('<option value="">All</option>');

			if (jQuery('.yt-super-select[name=MainCategoryID]').length) {
				var cur_input=jQuery('.yt-super-select[name=MainCategoryID]');

				yt_fill_super_options_with_promise(cur_input[0], lst.Categories).then(function() {

					yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=MainCategoryID]'), lst.Categories);

					YT_SelectAfterLoading(jQuery('.yt-super-select[name=MainCategoryID]'));

					jQuery('.yt-sub-category-col-field').addClass('disabled');
					jQuery('.yt-sub-category-col-field select').attr('disabled', 'disabled');

					jQuery('.yt-super-select input[name=MainCategoryID]').each(function() {

						jQuery(this).change(function() {

							if (jQuery(this).val() != '') {

								jQuery('.yt-sub-category-col-field').removeClass('disabled');
								jQuery('.yt-sub-category-col-field select').removeAttr('disabled');

								_YBA.label_options({ label: 'SubCategories', mainID: jQuery(this).val() }).then(function(lst) {
									
									yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=SubCategoryID]')[0], lst.SubCategories).then(function() {

										yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=SubCategoryID]'), lst.SubCategories);


										YT_SelectAfterLoading(jQuery('.yt-super-select[name=SubCategoryID]'));


									});
								
								});
							}

						});

					});
				


				});
			}
			else {
				yt_fill_options(jQuery('select[name=MainCategoryID]')[0], lst.SailCategories);
			}

			YT_SelectAfterLoading(jQuery('select[name=MainCategoryID]'));
		});

	}
	else {
		_YBA.label_options({ label: 'Categories' }).then(function(lst) {
			jQuery('select[name=MainCategoryID]').empty();

			jQuery('select[name=MainCategoryID]').prepend('<option value="">All</option>');

			if (jQuery('.yt-super-select[name=MainCategoryID]').length) {
				var cur_input=jQuery('.yt-super-select[name=MainCategoryID]');

				yt_fill_super_options_with_promise(cur_input[0], lst.Categories).then(function() {

					yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=MainCategoryID]'), lst.Categories);

					YT_SelectAfterLoading(jQuery('.yt-super-select[name=MainCategoryID]'));

					jQuery('.yt-sub-category-col-field').addClass('disabled');
					jQuery('.yt-sub-category-col-field select').attr('disabled', 'disabled');

					jQuery('.yt-super-select input[name=MainCategoryID]').each(function() {

						jQuery(this).change(function() {

							if (jQuery(this).val() != '') {

								jQuery('.yt-sub-category-col-field').removeClass('disabled');
								jQuery('.yt-sub-category-col-field select').removeAttr('disabled', 'disabled');

								_YBA.label_options({ label: 'SubCategories', mainID: jQuery(this).val() }).then(function(lst) {
									
									yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=SubCategoryID]')[0], lst.SubCategories).then(function() {

										yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=SubCategoryID]'), lst.SubCategories);

										YT_SelectAfterLoading(jQuery('.yt-super-select[name=SubCategoryID]'));


									});

								});
							}

						});

					});


				});
			}
			else {
				yt_fill_options(jQuery('select[name=MainCategoryID]')[0], lst.Categories);
			}

			YT_SelectAfterLoading(jQuery('select[name=MainCategoryID]'));
		});
	}
}

jQuery(document).ready(function() {
	// Filling Sail/Motor Categories
	jQuery('select[name=VesselType], select[name=vesseltype], input[name=VesselType], input[name=vesseltype]').each(function() {				
		if (
			(jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox')
			&&
			! jQuery(this).prop('checked')
		) {
			return false;
		}

		yt_category_based_off_of_type(jQuery(this));
		
	}).change(function() {

		jQuery('select[name=SubCategoryID]').empty();
		jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');

		yt_category_based_off_of_type(jQuery(this));
	});

	// Filling Sub-Category
	jQuery('select[name=MainCategoryID], .yt-super-select[name=MainCategoryID]').each(function() {				
		if (jQuery(this).data('value') != '') {
			_YBA.label_options({ label: 'SubCategories', mainID: jQuery(this).data('value') }).then(function(lst) {
				console.log(lst.SubCategories);

				jQuery('select[name=SubCategoryID]').empty();
				jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');

				if (jQuery('.yt-super-select[name=SubCategoryID]').length) {
				
					jQuery('.yt-sub-category-col-field').removeClass('disabled');
					jQuery('.yt-sub-category-col-field select').removeAttr('disabled', 'disabled');

					yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=SubCategoryID]')[0], lst.SubCategories).then(function() {

						YT_SelectAfterLoading(jQuery('.yt-super-select[name=SubCategoryID]'));

					});
				}
				else {
					yt_fill_options(jQuery('select[name=SubCategoryID]')[0], lst.SubCategories);
					YT_SelectAfterLoading(jQuery('select[name=SubCategoryID]'));
				}
				
			});
		}
		else {
			jQuery('select[name=SubCategoryID]').empty();
			jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');
		}


	}).on('change', function () {

		if (jQuery(this).val() != '') {
			_YBA.label_options({ label: 'SubCategories', mainID: jQuery(this).val() }).then(function(lst) {
				jQuery('select[name=SubCategoryID]').empty();
				jQuery('select[name=SubCategoryID]').prepend('<option value="">-- ALL --</option>');

				yt_fill_options(jQuery('select[name=SubCategoryID]')[0], lst.SubCategories);
			});
		}
		else {
			jQuery('select[name=SubCategoryID]').empty();
			jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');
		}

	});
});

