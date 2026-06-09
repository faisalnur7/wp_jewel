jQuery(function ($) {
  $(".wcpt-presets__item__preview").on("click", function (e) {
    e.stopPropagation();
  });

  // reload page with preset slug param
  $(".wcpt-presets__item:not(.wcpt-presets__item--locked)").on("click", function (e) {
    if ($(e.target).closest("a.wcpt-presets__item__preview").length) {
      return;
    }
    var $this = $(this),
      slug = $this.attr("data-wcpt-preset-slug"),
      currentUrl = window.location.href;

    // Check if the URL has a fragment (#)
    var urlParts = currentUrl.split("#");
    var baseUrl = urlParts[0]; // URL without the fragment
    var fragment = urlParts[1] ? "#" + urlParts[1] : ""; // Retain the fragment if it exists

    // Update the URL
    var newUrl = baseUrl + "&wcpt_preset=" + slug + fragment;
    window.location.href = newUrl;
  }),
    // dismiss preset applied message (only hide after server confirms; keeps banner on reload until dismissed)
    $(document).on("click", ".wcpt-preset-applied-message__dismiss", function () {
      var $btn = $(this);
      var $msg = $btn.closest(".wcpt-preset-applied-message");
      var postId = $msg.attr("data-post-id");
      if (
        typeof wcptPresets === "undefined" ||
        !postId ||
        !wcptPresets.ajaxUrl ||
        !wcptPresets.dismissNonce
      ) {
        return;
      }
      $btn.prop("disabled", true);
      $.post(wcptPresets.ajaxUrl, {
        action: "wcpt_dismiss_preset_applied_message",
        post_id: postId,
        nonce: wcptPresets.dismissNonce,
      })
        .done(function (res) {
          if (res && res.success) {
            $msg.slideUp();
          } else {
            $btn.prop("disabled", false);
          }
        })
        .fail(function () {
          $btn.prop("disabled", false);
        });
    });

  // copy shortcode
  $(".wcpt-preset-applied-message__shortcode-copy-button").on(
    "click",
    function () {
      var $this = $(this);
      $input = $this.siblings("input");
      $input.select();
      document.execCommand("copy");
    }
  );
});
