jQuery(document).ready(function ($) {
  var $tbody = $("#cfp-fields-container tbody");
  console.log("Sortable target found:", $tbody.length); // Debug: Check if tbody is found
  if ($tbody.length) {
    $tbody
      .sortable({
        items: "tr.cfp-field", // Explicitly target rows
        placeholder: "cfp-field-placeholder",
        update: function (event, ui) {
          $tbody.find("tr.cfp-field").each(function (index) {
            $(this).attr("data-index", index);
            $(this)
              .find("select, input")
              .each(function () {
                var name = $(this).attr("name");
                if (name) {
                  $(this).attr(
                    "name",
                    name.replace(/\[\d+\]/, "[" + index + "]")
                  );
                }
              });
          });
        },
      })
      .disableSelection();
    console.log("Sortable initialized"); // Debug: Confirm initialization
  } else {
    console.log("Error: #cfp-fields-container tbody not found");
  }
});
