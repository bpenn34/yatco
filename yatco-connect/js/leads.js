// NEW FUNCTIONS
var yt_lead_common_inputs=['FirstName', 'LastName', 'Email', 'Phone', 'owner_or_industry'];

function yt_restore_lead_common_inputs(form) {
   yt_lead_common_inputs.forEach(function(input_name) {
      
    var cookie_val = getCookie('yatco-contact-form-'+input_name);

    if (typeof cookie_val != 'undefined' && cookie_val != 'undefined') {
      jQuery('input[name='+  input_name +']', form).val( cookie_val );
    }
  });
}

function yt_set_lead_common_inputs(data) {
  yt_lead_common_inputs.forEach(function(key) {
 
    setCookie('yatco-contact-form-'+key, data[ key ], 360);
 
  });
}

function yt_owner_or(ele_form) {
  if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
    yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
  }
  else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
    yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
  }
}

function YT_SubmitVesselLead(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendVesselLead', 'form_data': formData}).then(function(r_data) {
    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
  });
}

function YT_SubmitVesselLeadAboutFullSpec(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendVesselLead', 'form_data': formData}).then(function(r_data) {
    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Successfull Full Spec Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
  });
}

function YT_SubmitVesselBrochureLead(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendVesselLead', 'form_data': formData}).then(function(r_data) {
    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Brochure Lead',  ele_form.attr('id')+"_"+formData.VesselID); 

      window.open(formData.BrochureUrl, '_blank');

      //jQuery('#download_pdf_brochure')[0].src=formData.BrochureUrl;

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
  });
}

function YT_SubmitVesselLeadShowing(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendVesselLead', 'form_data': formData}).then(function(r_data) {
    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Scheduled A Showing',  ele_form.attr('id')+"_"+formData.VesselID); 

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
  });
}

function YT_SubmitVesselLeadQulifiedBroker(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendVesselLead', 'form_data': formData}).then(function(r_data) {
    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Qulified Broker',  ele_form.attr('id')+"_"+formData.VesselID); 

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
  });
}

function YT_SubmitBrokerContactForm(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Broker', 'Submitted', 'Form Submit Clicked', 'broker-'+formData.BrokerID);

  _YBA.send_lead({'label': 'BrokerContact', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Broker', 'Success', 'Successfull Broker Message', 'broker-'+formData.BrokerID);
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', 'broker-'+formData.BrokerID);
    }
    else {
      yt_ga_event('Broker', 'Unknow Issue', 'Boss Return Issue', 'broker-'+formData.BrokerID);
    }
  });
}

function YT_SubmitSoldContactForm(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Vessel Sold Lead', 'Submitted', 'Form Submit Clicked', 'broker-'+formData.BrokerID);

  _YBA.send_lead({'label': 'BrokerContact', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Vessel Sold Lead', 'Success', 'Successfull Sold Message', 'broker-'+formData.BrokerID);
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', 'broker-'+formData.BrokerID);
    }
    else {
      yt_ga_event('Vessel Sold Lead', 'Unknow Issue', 'Boss Return Issue', 'broker-'+formData.BrokerID);
    }
  });
}

function YT_SubmitServiceMlsContentForm(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Services MLS', 'Submitted', 'Form Submit Clicked', '');

  _YBA.send_lead({'label': 'ServicesMlsContact', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Services MLS', 'Success', 'Successfull Sold Message', '');
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Services MLS', 'AKI SPAM', 'Aki Spam Return Issue', '');
    }
    else {
      yt_ga_event('Services MLS', 'Unknow Issue', 'Boss Return Issue', '');
    }
  });
}

function YGC_SubmittingContactForm(e) {
  e.preventDefault();

  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  
  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  _YBA.send_lead({'label': 'SendGeneralContact', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).removeAttr('disabled');
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

  });
}

function SubmittingContactForm_Success(r, ele_form) {

  jQuery('[type=submit]', ele_form).removeAttr('disabled');

  if (typeof r.error != 'undefined') {
    alert(r.error);
  }
  else if (r.ResponseCode == 0 || r.Response == 'SPAM') {
    if (typeof _YCT != 'undefined') {
      _YCT.formSubmit( jQuery('input[type=email]', ele_form).val(), r.ExtraData );
    }

    jQuery('.hide-after-submit', ele_form).hide();
    
    jQuery('.form-success-message', ele_form).show();

  }

}

function GA_TrackTele(VesselID, kind) {
  yt_ga_event('Telephone', 'Click', kind, ''); 
  //yt_ga_event('send', 'event', VesselID, 'Telephone', kind);
  
  if (typeof _YCT != 'undefined') {
    _YCT.forsale.phone(VesselID);
  }
}

function YT_SubmitCharterLead(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Charter Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendCharterLead', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).removeAttr('disabled');
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Charter Lead', 'Success', 'Successfull Charter Lead', ele_form.attr('id')+"_"+formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Charter Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id')+"_"+formData.VesselID);
    }

  });
}

function YT_SubmitCharterFullSpecLead(e) {
  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Charter Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id')+"_"+formData.VesselID);

  _YBA.send_lead({'label': 'SendCharterLead', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).removeAttr('disabled');
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Charter Lead', 'Success', 'Successfull Charter Lead', ele_form.attr('id')+"_"+formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Owner', '');
      }
      else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Industry', '');
      }
    }
    else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue',  ele_form.attr('id')+"_"+formData.VesselID);
    }
    else {
      yt_ga_event('Charter Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id')+"_"+formData.VesselID);
    }

  });
}

// NEW ATTACH
jQuery(document).ready(function() {
  // VESSEL LEAD FORM
  jQuery('.contact-broker-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitVesselLead);

  });

  // FULL SPEC VESSEL LEAD FORM
  jQuery('.contact-broker-about-full-spec-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitVesselLeadAboutFullSpec);

  });

  // BROCHURE VESSEL LEAD FORM
  jQuery('.contact-wanted-forsale-brochure').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitVesselBrochureLead);

  });

  // VESSEL LEAD - SHOWING - FORM
  jQuery('.contact-broker-about-showing').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitVesselLeadShowing);

  });

  // VESSEL LEAD - Qulified - FORM
  jQuery('.contact-qulified-broker').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitVesselLeadQulifiedBroker);

  });

  // BROKER CONTACT
  jQuery('.contact-broker-for-real-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitBrokerContactForm);

  });

  // Broker sold lead
  jQuery('.sold-contact-broker-for-real-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitSoldContactForm);

  });

  // CHARTER LEAD FORM
  jQuery('.charter-lead-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitCharterLead);

  });

  // CHARTER FULL SPEC LEAD FORM
  jQuery('.charter-full-spec-lead-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitCharterLead);

  });

  // GENERAL CONTACT FORM
  jQuery('.yatco-general-contact-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YGC_SubmittingContactForm);

  });

  // SERVICES MLS CONTACT FORM
  jQuery('.yatco-services-mls-contact-form').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YT_SubmitServiceMlsContentForm);

  });

});
