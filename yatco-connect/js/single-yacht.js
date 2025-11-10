jQuery(document).ready(function() {
  if (typeof _YCT != 'undefined') {
    _YCT.forsale.view(jQuery('meta[name="yatco-vessel-id"]').attr('content'));
  }

  // Top Slider
  var ele_top_slider = document.querySelector('#yt-light-slider-gallery');

  if (ele_top_slider) {
    var flkty = new Flickity( ele_top_slider, {
      "imagesLoaded": true,
      "freeScroll": true, 
      "lazyLoad": true,
      "setGallerySize": false
    });
  }

  jQuery('img[src^="https://cloud.yatco.com"]').bind('contextmenu', function(e) {
      return false;
  }); 

  // Read More / Less
  new Readmore('#ycd-description', { 
    speed: 75, 
    collapsedHeight: 500,
    moreLink: '<a href="#" class="read-more">+ Read more</a>',
    lessLink: '<a href="#" class="read-less">- Read less</a>'
  });

  new Readmore('#rates-list', { 
    speed: 75, 
    collapsedHeight: 172,
    moreLink: '<a href="#" class="read-more">+ More Rates</a>',
    lessLink: '<a href="#" class="read-less">- Hide Rates</a>'
  });

  // Single-Yacht Gallery Read More
  new Readmore('.read-more-on-photos', {
    speed: 75, 
    collapsedHeight: (jQuery('#lightgallery .col-pic').height()*3),
    moreLink: '<div class="yt-text-center view-all-button"><a href="#" class="yt-btn read-more yt-btn-alt-dark-blue">VIEW ALL PHOTOS</a></div>',
    lessLink: '<div class="yt-text-center view-all-button"><a href="#" class="yt-btn read-less yt-btn-alt-dark-blue">VIEW LESS PHOTOS</a></div>'
  });

  jQuery('.gallery-just-cut').height( (jQuery('#lightgallery .col-pic').height()*3) );

  // Accordion
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
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

  // Bigger Gallery
  lightGallery(document.getElementById('lightgallery'), {
    plugins: [
      lgZoom, 
      lgThumbnail, 
      lgVideo,  
      lgRotate, 
      //lgShare
    ],
    speed: 500,
    //licenseKey: 'your_license_key',
    thumbnail:true,
    animateThumb: false,
    showThumbByDefault: false,
    download: false,
  });

  var lg = document.getElementById('lightgallery');

  // Perform any action just before opening the gallery
  lg.addEventListener('lgAfterOpen', () => {
    jQuery('img[src^="https://cloud.yatco.com"]').bind('contextmenu', function(e) {
      return false;
    }); 
  });

  jQuery('#lightgallery .col-pic').click(function(e) {
    if (typeof _YCT != 'undefined') {
      _YCT.forsale.gallery( jQuery('#lightgallery').data('vessel-id') );
    }
  });
});

