jQuery(document).ready(function ($) {
  // Handle reset button click
  $(".wcpt-preview-form-reset-icon").on("click", function (e) {
    e.preventDefault();
    var $input = $(this).prev("input");
    var defaultShortcode = $input.data("default");
    $input.val(defaultShortcode);
    $input.closest("form").submit();
  });
});
