document.addEventListener("DOMContentLoaded", function () {
  let fieldContainer = document.querySelector(
    "#cfp-fields-container table tbody"
  );
  let addButton = document.getElementById("add-field-button");

  if (!addButton || !fieldContainer) {
    console.log("Elements not found:", addButton, fieldContainer);
    return;
  }

  addButton.addEventListener("click", function () {
    console.log("Add Field button clicked!"); // Debugging line

    // Get all existing field rows and find the highest data-index
    const existingFields = fieldContainer.querySelectorAll("tr.cfp-field");
    let maxIndex = -1;
    existingFields.forEach((field) => {
      const index = parseInt(field.getAttribute("data-index"), 10);
      if (index > maxIndex) {
        maxIndex = index;
      }
    });

    // Next index is maxIndex + 1 (start at 0 if no fields exist)
    let index = maxIndex + 1;

    let fieldHTML = `
    <tr class="cfp-field" data-index="${index}">
        <td>
            <select name="cfp_form_fields[${index}][type]" class="cfp-field-type">
                <option value="text">Text</option>
                <option value="email">Email</option>
                <option value="tel">Phone</option>
                <option value="textarea">Textarea</option>
                <option value="select">Dropdown</option>
            </select>
        </td>
        <td>
            <input type="text" name="cfp_form_fields[${index}][name]" 
                   placeholder="Field Name (no spaces)">
        </td>
        <td>
            <input type="text" name="cfp_form_fields[${index}][label]" 
                   placeholder="Field Label">
        </td>
        <td>
            <span class="cfp-field-options hidden">
                <input type="text" name="cfp_form_fields[${index}][options]" 
                       placeholder="Dropdown options (comma separated)">
            </span>
        </td>
        <td style="text-align: center;">
            <label>
                <input type="checkbox" name="cfp_form_fields[${index}][required]" value="1">
                Required
            </label>
        </td>
        <td style="text-align: center;">
            <label for="cfp_enable_icon_${index}">
                <input type="checkbox" id="cfp_enable_icon_${index}" 
                       class="cfp_enable_icon" data-index="${index}"
                       name="cfp_form_fields[${index}][enable_icon]" value="1">
                Enable Icon
            </label>
            <label class="cfp-icon-picker" id="icon-picker-${index}" style="display: none;">
                <button type="button" class="cfp-icon-button" data-index="${index}">
                    <i class="fa-solid fa-icons"></i>
                </button>
            </label>
            <input type="hidden" class="icon_class" name="cfp_form_fields[${index}][icon_class]" value="">
        </td>
        <td style="text-align: center;">
            <button type="button" class="remove-field-button button button-secondary">Remove</button>
        </td>
    </tr>
    `;

    fieldContainer.insertAdjacentHTML("beforeend", fieldHTML);
  });

  // Remove field
  fieldContainer.addEventListener("click", function (event) {
    if (event.target.classList.contains("remove-field-button")) {
      console.log("Remove button clicked!"); // Debugging line
      event.target.closest(".cfp-field").remove();
    }
  });
});

jQuery(document).ready(function () {
  jQuery("#cfp-fields-container").on("change", ".cfp-field-type", function () {
    var $field = jQuery(this).closest(".cfp-field");
    var selectedType = jQuery(this).val();

    // Hide all option fields by default
    $field.find(".cfp-field-options").addClass("hidden");
    $field.find(".cfp-field-options-radio").addClass("hidden");
    $field.find(".cfp-field-options-checkbox").addClass("hidden");
    $field.find(".cfp-field-options-date").addClass("hidden");

    // Show the relevant option field based on the selected type
    if (selectedType === "select") {
      $field.find(".cfp-field-options").removeClass("hidden");
    } else if (selectedType === "radio") {
      $field.find(".cfp-field-options-radio").removeClass("hidden");
    } else if (selectedType === "checkbox") {
      $field.find(".cfp-field-options-checkbox").removeClass("hidden");
    } else if (selectedType === "date") {
      $field.find(".cfp-field-options-date").removeClass("hidden");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".cfp-tab");
  const contents = document.querySelectorAll(
    ".cfp-tab-content, .cfp-tab-setting"
  );

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => t.classList.remove("active"));
      contents.forEach((c) => c.classList.remove("active"));
      this.classList.add("active");
      const target = document.getElementById(
        "cfp-" + this.dataset.tab + "-tab"
      );
      if (target) {
        target.classList.add("active");
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const inputtabs = document.querySelectorAll(".input-tab-button");
  const inputcontents = document.querySelectorAll(
    ".cfp-input-fields-tab-content"
  );

  inputtabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      inputtabs.forEach((t) => t.classList.remove("active"));
      inputcontents.forEach((c) => c.classList.remove("active"));
      this.classList.add("active");
      const target = document.getElementById(this.dataset.tab + "-tab");
      if (target) {
        target.classList.add("active");
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const btntabs = document.querySelectorAll(".tab-button");
  const btncontents = document.querySelectorAll(".cfp-button-tab-content");

  btntabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      btntabs.forEach((t) => t.classList.remove("active"));
      btncontents.forEach((c) => c.classList.remove("active"));
      this.classList.add("active");
      const target = document.getElementById(this.dataset.tab + "-tab");
      if (target) {
        target.classList.add("active");
      }
    });
  });
});

jQuery(document).ready(function () {
  jQuery(".view-more-forms").on("click", function () {
    console.log("button is clicked");
    var submissionId = jQuery(this).data("submission-id");
    var row = jQuery(".view-more-row-" + submissionId); // Target the corresponding row
    row.toggle();
    if (row.is(":visible")) {
      jQuery(this).text("View Less");
    } else {
      jQuery(this).text("View More");
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  var manualCustomizationCheckbox = document.getElementById(
    "cfp_manual_customization"
  );
  var customizationSettings = document.getElementById(
    "manual-customization-settings"
  );

  function toggleCustomization() {
    customizationSettings.style.display = manualCustomizationCheckbox.checked
      ? "block"
      : "none";
  }

  manualCustomizationCheckbox.addEventListener("change", toggleCustomization);
  toggleCustomization();
});

jQuery(document).ready(function ($) {
  $(".cfp-reset-design-btn").on("click", function () {
    let postId = $(this).data("post-id");

    if (
      !confirm("Are you sure you want to reset design settings for this form?")
    ) {
      return;
    }

    $.ajax({
      url: ajaxurl, // WordPress AJAX URL
      type: "POST",
      data: {
        action: "cfp_reset_form_design",
        post_id: postId,
      },
      beforeSend: function () {
        $(this).prop("disabled", true).text("Resetting...");
      },
      success: function (response) {
        if (response.success) {
          alert(
            "Manual Customization for Form ID " + postId + " have been reset."
          );
          location.reload(); // Reload to reflect changes
        } else {
          alert("Failed to reset. Please try again.");
        }
      },
      complete: function () {
        $(this).prop("disabled", false).text("Reset Design");
      },
    });
  });
});

jQuery(document).ready(function ($) {
  // Show or hide icon picker on page load based on the checkbox state
  $(".cfp_enable_icon").each(function () {
    const index = $(this).data("index");
    const iconPicker = $("#icon-picker-" + index);

    // Check the checkbox state on page load
    if ($(this).is(":checked")) {
      iconPicker.css("display", "inline-block"); // Show icon picker if checked
    } else {
      iconPicker.css("display", "none"); // Hide icon picker if not checked
    }
  });

  // Toggle icon picker visibility when checkbox state changes
  $(document).on("change", ".cfp_enable_icon", function () {
    const index = $(this).data("index");
    const iconPicker = $("#icon-picker-" + index);

    // Toggle the display of the icon picker based on the checkbox state
    if ($(this).is(":checked")) {
      iconPicker.css("display", "inline-block"); // Show the icon picker
    } else {
      iconPicker.css("display", "none"); // Hide the icon picker
    }
  });

  // Handle the click event on the icon picker button
  $(document).on("click", ".cfp-icon-button", function () {
    // Hide any open icon picker popovers
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
          if (event.iconpickerValue) {
            const iconClass = event.iconpickerValue;
            // Update hidden input with the selected icon class
            $("input[name='cfp_form_fields[" + index + "][icon_class]']").val(
              iconClass
            );

            // Update the button text and icon
            $(this)
              .find("button")
              .html('<i class="' + iconClass + '"></i> Select Icon');

            // Optionally, hide the icon picker popover after selection
            $(".iconpicker-popover").css("display", "none");
          } else {
            console.log(
              "No icon selected (event.iconpickerValue is undefined)"
            );
          }
        });
    }

    // Show the icon picker popover
    iconPickerElement.find(".iconpicker-popover").css("display", "block");
  });
});
