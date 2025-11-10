jQuery(document).ready(function ($) {
  
  if ($(".cfp-color-field").length) { // Check if element exists
      $('.cfp-color-field').wpColorPicker({
        // Enable alpha!
        palettes: true,
        clear: true,
        defaultColor: false,
        change: function(event, ui){
            // Do something if needed
        },
        // This tells the alpha extension to activate
        alpha: true 
    });
  }
  
  // Show the icon picker when checkbox is checked
  $(document).ready(function () {
    $(".cfp_enable_icon").each(function () {
      const index = $(this).data("index");
      const iconPicker = $("#icon-picker-" + index);

      // Check the checkbox state on page load
      if ($(this).is(":checked")) {
        iconPicker.css("display", "inline-block"); // Show icon picker
      } else {
        iconPicker.css("display", "none"); // Hide icon picker
      }
    });

    // Toggle behavior when checkbox is clicked
    $(".cfp_enable_icon").on("change", function () {
      const index = $(this).data("index");
      const iconPicker = $("#icon-picker-" + index);

      // Toggle icon picker visibility based on checkbox state
      if ($(this).is(":checked")) {
        iconPicker.css("display", "inline-block");
      } else {
        iconPicker.css("display", "none");
      }
    });
  });

  $(".cfp-icon-button").on("click", function () {
    $(".iconpicker-popover").css("display", "none");
    const index = $(this).data("index");
    const iconPickerElement = $("#icon-picker-" + index);
    // Check if icon picker is already initialized
    if (!iconPickerElement.data("iconpicker")) {
      iconPickerElement
        .iconpicker({
          placement: "bottom",
          hideOnSelect: true,
          selectedClass: "fa-lg",
        })
        .on("iconpickerSelected", function (event) {
          console.log("iconpickerSelected event: ", event);
          if (event.iconpickerValue) {
            const iconClass = event.iconpickerValue;
            $("input[name='cfp_form_fields[" + index + "][icon_class]']").val(
              iconClass
            );
            $(this)
              .find("button")
              .html('<i class="' + iconClass + '"></i> Select Icon');
            console.log("Button updated with selected icon");

            $(".iconpicker-popover").css("display", "none");
          } else {
            console.log(
              "No icon selected (event.iconpickerValue is undefined)"
            );
          }
        });
    }
    // Show the popover
    $(this).siblings(".iconpicker-popover").css("display", "block");
  });
});

jQuery(document).on("click", function (event) {
  // If click is outside the icon picker and button, hide all icon pickers
  if (
    !jQuery(event.target).closest(".cfp-icon-button, .iconpicker-popover")
      .length
  ) {
    jQuery(".iconpicker-popover").css("display", "none");
  }
});
