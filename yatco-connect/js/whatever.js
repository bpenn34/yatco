function get_form_data(form_ele) {
  var formData = new FormData( form_ele );
      
  var fd = Array.from(formData.entries()).reduce((memo, pair) => ({
    ...memo,
    [pair[0]]: pair[1],
  }), {});

  return fd;
}

/* ------ Start Of Cookies ------ */
var cookieTime=360;

function setCookie(c_name, value, exdays, host) {var exdate=new Date(); exdate.setDate(exdate.getDate() + exdays); var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()); document.cookie=c_name + "=" + c_value+ ((host==null)?"":";domain="+ host +""+";path=/;");}

function getCookie(c_name) {var i,x,y,ARRcookies=document.cookie.split(";"); for (i=0;i<ARRcookies.length;i++) {x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("=")); y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1); x=x.replace(/^\s+|\s+$/g,""); if (x==c_name) {return unescape(y);}}}

function deleteCookie(name, path, domain) {if (getCookie(name)) {document.cookie = name + "=" + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";}}	
/* ------ End Of Cookies ------ */

// Google Analytics 
function yt_ga_event(category, action, label, value) {
  console.log('ga event');
  
  if (typeof gtag !== 'undefined') {
    gtag('event', action, {
      'event_category': category, 
      'event_label': label, 
      'event_value': value,
      'event_callback': function() {

        console.log('ga event callback ('+ action +' - '+ category +' - '+ label +')');

      } 
    });
  }
  else if (typeof ga != 'undefined') {
    ga('send', 'event', category, action, label);
  }
  else {
    console.warn("Google Analytics Doesnt Seem To Be Installed.");
  }

  if (typeof _paq != 'undefined') {
    _paq.push(['trackEvent', category, action, label, value]);
  }
}

// Render Google ReCaptcha
function renderYATCOrecaptcha() {

  /*jQuery('.yatco-g-recaptcha').each(function() {

    grecaptcha.render(jQuery(this)[0], {});

  });*/

  /*jQuery('*:not(.yt-modal) .yatco-g-recaptcha').each(function() {

    grecaptcha.render(jQuery(this)[0], {});

  });*/

}

// re-initing modal jquery
jQuery(document).ready(function() {
  jQuery('[data-modal]').click(function() {

    var data_modal = jQuery(this).data('modal');

    yt_ga_event('Modal', 'Open', 'Modal Opened: '+ data_modal, data_modal);

    jQuery( data_modal ).yatco_modal({
    	closeText: 'X',
      modalClass: 'yt-modal-open',
      closeClass: 'yt-model-close'
    });
  });
});


// Ninja Forms Submit Event To Google
/*jQuery( document ).ready( function( $ ) {
  if (typeof Marionette != 'undefined') {
    // Create a new object for custom validation of a custom field.
    var ninjaFormsSubmittion = Marionette.Object.extend({
      initialize: function() {
        this.listenTo( Backbone.Radio.channel( 'forms' ), 'submit:response', this.actionSubmit );
      },

      actionSubmit: function( response ) {
        console.log( response );
        console.log( response.data.settings.title );

        // Do stuff here.
        if (typeof ga != 'undefined') {
          yt_ga_event('Ninja Form Submittion', response.data.settings.title, 'Submitted');
        }
      }
    });

    // Instantiate our custom field's controller, defined above.
    new ninjaFormsSubmittion();
  }
  else {
    console.log('Marionette is undefined. Just means ninja is not running on page.');
  }
});*/

jQuery(document).ready(function() {
  jQuery('img[src^="https://cloud.yatco.com"]').bind('contextmenu', function(e) {
      return false;
  }); 
});

// Submitting Newsletter 
function YTPLUGINSubmittingNewsletter(e) {

  e.preventDefault();
  
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);

  yt_set_lead_common_inputs(formData);

  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  yt_ga_event('Newsletter', 'Submitted', 'Form Submit Clicked', '');

  // Add New Tracking For Owner VS industry

  _YBA.send_lead({'label': 'ClientNewsletter', 'form_data': formData}).then(function(r_data) {

    SubmittingContactForm_Success(r_data, ele_form);

    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Newsletter', 'Success', 'Successful Newsletter Signup', '');
      setCookie('has_newsletter_signup_vONE', 1, 365);
    }
    else {
      yt_ga_event('Newsletter', 'Unknow Issue', 'Boss Return Issue', '');
    }
  });

}

jQuery( document ).ready( function( $ ) {

  jQuery('.yatco-client-newsletter-signup').each(function() {

    yt_restore_lead_common_inputs(jQuery(this));

    jQuery(this).on('submit', YTPLUGINSubmittingNewsletter);

  });

} );

