// Highlight active thumbnail
document.querySelectorAll('.thumb').forEach((thumb, index) => {
    thumb.addEventListener('click', function() {
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});
// Initialize Glightbox for grid gallery
const gridGallery = GLightbox({
    selector: '.gallery-grid-item', // Only target grid gallery images
    loop: true, // Optional: allows looping through images
});

document.addEventListener("DOMContentLoaded", function() {
    let showMoreBtn = document.getElementById("showMoreGallery");
    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function() {
            let hiddenItems = document.querySelectorAll(".gallery-item.d-none");
            hiddenItems.forEach((item, index) => {
                if (index < 12) { // Show 3 more rows (12 images)
                    item.classList.remove("d-none");
                }
            });

            // Hide button if no more hidden items
            if (document.querySelectorAll(".gallery-item.d-none").length === 0) {
                showMoreBtn.style.display = "none";
            }
        });
    }
});

jQuery(document).ready(function ($) {
    $(".yt-empty-tag").each(function () {
        $(this).parent().remove(); // Remove the immediate parent element
    });

    $(".yt-default-video-placeholder").each(function () {
        const $el = $(this);
        if ($el.siblings().length > 0) {
            $el.remove(); // has siblings → just remove this element so if video is not available, it will remove on the video
        } else {
            $el.parent().remove(); // no siblings → remove the parent so if the video is not available, there wont be any empty div
        }
    });
    $(document).on('click', '.yt-load-more-button', function () {
        const $button = $(this);    
        // Reveal all hidden items
        $('.yt-hidden-item').fadeIn(300).removeClass('yt-hidden-item');
    
        // Optionally, hide the button after revealing
        $button.fadeOut(200);
    });    
});

//for contact form from backend trigger function
jQuery(document).ready(function () {
    // VESSEL LEAD FORM
    jQuery('.cfp-form').each(function () {
        yt_restore_lead_common_inputs(jQuery(this));
        jQuery(this).on('submit', YT_Submit_cfp);
    }); // FULL SPEC VESSEL LEAD FORM

    jQuery('.cpf-subscription-form').each(function () {
        yt_restore_lead_common_inputs(jQuery(this));
        jQuery(this).on('submit', YTPLUGINSubmittingNewsletter_cfp);
    });
});

function SubmittingContactForm_Success_cfp(r, ele_form) {

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

function saveForm_data(data){
    data.action = 'cfp_handle_form_submission';
    jQuery.post(ytData.ajax_url, data, function(response) {
    });
}

function YT_Submit_cfp(e) {
    e.preventDefault();
    var ele_form = jQuery(this);
    var formData = get_form_data(ele_form[0]);
    
    //Saving form data using ajax
    saveForm_data(formData);

    const formTypeActionMap = {
        1: 'SendGeneralContact',
        9: 'SendVesselLead',
        3: 'SendCharterLead'
    };
      
    const actionName = formTypeActionMap[formData.FormTypeID];

    var cfpFields = {};

    // Loop through the formData object
    Object.keys(formData).forEach(function(key) {
        var match = key.match(/^cfp_fields\[(.+?)\]$/);
        if (match) {
            // Extract fields inside cfp_fields[]
            cfpFields[match[1]] = formData[key];

        } else {
            // Include other fields directly
            if (!['cfp_form_id', 'cfp_form_title', 'g-recaptcha-response'].includes(key)) {
                cfpFields[key] = formData[key];
            }
            
        }
    });
    yt_set_lead_common_inputs(cfpFields);
    jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
    jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).html());
    jQuery('[type=submit]', ele_form).html('Processing...');
    //yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

    //change label depends on the form id
    _YBA.send_lead({
      //'label': 'sendContactFormData',
      'label': actionName, //Taking the action dynamicall from the FormTypeID
      'form_data': cfpFields
    }).then(function (r_data) {

        jQuery('[type=submit]', ele_form).removeAttr('disabled');
        SubmittingContactForm_Success_cfp(r_data, ele_form);

      jQuery('[type=submit]', ele_form).html(jQuery('[type=submit]', ele_form).data('og-val'));

      if (r_data.ResponseCode == 0) {
        yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead', ele_form.attr('id') + "_" + formData.VesselID);
  
        if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
          yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
        } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
          yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
        }
        
      } else if (typeof r_data.spam_aki != 'undefined') {
        yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
      } else {
        yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
      }
    });
}

function YTPLUGINSubmittingNewsletter_cfp(e) {
    e.preventDefault();
    var ele_form = jQuery(this);
    var formData = get_form_data(ele_form[0]);
    yt_set_lead_common_inputs(formData);

    var cfpFields = {};

    // Loop through the formData object
    Object.keys(formData).forEach(function(key) {
        var match = key.match(/^cfp_fields\[(.+?)\]$/);
        if (match) {
            // Extract fields inside cfp_fields[]
            cfpFields[match[1]] = formData[key];
        } else {
            // Include other fields directly
            if (!['cfp_form_id', 'cfp_form_title', 'g-recaptcha-response'].includes(key)) {
                cfpFields[key] = formData[key];
            }
        }
    });

    jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
    jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).html());
    jQuery('[type=submit]', ele_form).html('Processing...');
    yt_ga_event('Newsletter', 'Submitted', 'Form Submit Clicked', ''); // Add New Tracking For Owner VS industry
  
    _YBA.send_lead({
      'label': 'ClientNewsletter',
      'form_data': cfpFields
    }).then(function (r_data) {

        SubmittingContactForm_Success_cfp(r_data, ele_form);
        jQuery('[type=submit]', ele_form).html('Subscribed');
  
        if (r_data.ResponseCode == 0) {
            yt_ga_event('Newsletter', 'Success', 'Successful Newsletter Signup', '');
            setCookie('has_newsletter_signup_vONE', 1, 365);

        } else {
            yt_ga_event('Newsletter', 'Unknow Issue', 'Boss Return Issue', '');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
  const sliders = document.querySelectorAll('.myAutoGridSlider');

  sliders.forEach((slider, index) => {
    const customCardCount = parseInt(slider.getAttribute('data-card-count')) || 3;

    new Swiper(slider, {
      slidesPerView: 1.2,
      centeredSlides: false,
      grid: {
        rows: 1,
        fill: "row"
      },
      spaceBetween: 20,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false
      },
      speed: 1500,
      loop: true,
      breakpoints: {
        768: {
          slidesPerView: 2,
          grid: { rows: 1 }
        },
        1024: {
          slidesPerView: 3,
          grid: { rows: 1 }
        },
        1200: {
          slidesPerView: customCardCount,
          grid: { rows: 1 }
        }
      }
    });
  });
});

function equalizeCardHeightsPerSwiper() {
    const swipers = document.querySelectorAll('.swiper');

    swipers.forEach(swiper => {
      const cards = swiper.querySelectorAll('.swiper-slide .card');
      let maxHeight = 0;

      // Reset height first to avoid stacking issues
      cards.forEach(card => {
        card.style.height = 'auto';
      });

      // Find tallest .card
      cards.forEach(card => {
        const height = card.offsetHeight;
        if (height > maxHeight) {
          maxHeight = height;
        }
      });

      // Apply max height to all .card elements
      cards.forEach(card => {
        card.style.height = maxHeight + 'px';
      });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    equalizeCardHeightsPerSwiper();
    window.addEventListener('resize', equalizeCardHeightsPerSwiper); // optional
});

jQuery(document).ready(function ($) {
  $('.yt-read-more-button').each(function () {
    const $button = $(this);
    const collapsedText = $button.text().trim();
    const expandedText = $button.attr('data-toggle-text')?.trim() || 'Read Less';

    const $target = $button.siblings('[data-col-max-height]');
    const $nestedTarget = $button.parent().siblings('[data-col-max-height]');
    const $content = $target.length ? $target : $nestedTarget;

    if (!$content.length) return;

    const maxHeightAttr = $content.attr('data-col-max-height') || '0px';
    const maxHeight = parseInt(maxHeightAttr);

    // Initial styles for transition
    $content.css({
      'max-height': maxHeight + 'px',
    });

    if ($content[0].scrollHeight <= maxHeight) {
      $button.hide();
    }

    $button.on('click', function (e) {
      e.preventDefault();
      const isExpanded = $content.hasClass('expanded');

      if (isExpanded) {
        // Collapse to original max-height
        $content.removeClass('expanded').css('max-height', maxHeight + 'px');
        $button.text(collapsedText);
      } else {
        // Expand to scrollHeight
        const scrollHeight = $content[0].scrollHeight;
        $content.addClass('expanded').css('max-height', scrollHeight + 'px');
        $button.text(expandedText);
      }
    });
  });

  $('.api-variable').each(function () {
    const $apiVar = $(this);
    const content = $apiVar.text().trim();

    // Match empty or placeholder like {Flag} or {BasicInfo:Flag}
    const isEmptyOrPlaceholder = content === '' || /^\{[^{}]+\}$/.test(content);

    const $col = $apiVar.parent('.col');
    const $row = $col.parent('.row');

    if ($col.length && $row.length && isEmptyOrPlaceholder) {
        $row.remove();
    }
  });

});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".accordion-block").forEach(function (block) {
    const items = block.querySelectorAll(".accordion-item");

    items.forEach(item => {
      const title = item.querySelector(".accordion-title");
      const content = item.querySelector(".accordion-content");

      // Initial setup
      content.style.maxHeight = "0";
      content.style.overflow = "hidden";
      content.style.transition = "max-height 0.4s ease";
      content.style.display = "none";

      title.addEventListener("click", function () {
        const isAlreadyOpen = content.style.display === "block";

        // Loop through all items
        items.forEach(otherItem => {
          const otherContent = otherItem.querySelector(".accordion-content");
          const isCurrent = otherContent === content;

          if (!isCurrent) {
            otherContent.style.maxHeight = "0";
            setTimeout(() => {
              otherContent.style.display = "none";
            }, 400); // only after collapse finishes
          }
        });

        // Toggle current item
        if (!isAlreadyOpen) {
          content.style.display = "block";
          requestAnimationFrame(() => {
            content.style.maxHeight = content.scrollHeight + "px";
          });
        } else {
          content.style.maxHeight = "0";
          setTimeout(() => {
            content.style.display = "none";
          }, 400);
        }
      });
    });
  });
});