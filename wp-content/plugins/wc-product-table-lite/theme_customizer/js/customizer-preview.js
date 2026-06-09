(function ($) {
  "use strict";

  // Store the mapping for reference
  var mapping = wcpt_customizer_mapping || {};

  // Throttle function to limit how often a function can be called
  function throttle(func, limit) {
    var inThrottle;
    return function () {
      var args = arguments;
      var context = this;
      if (!inThrottle) {
        func.apply(context, args);
        inThrottle = true;
        setTimeout(function () {
          inThrottle = false;
        }, limit);
      }
    };
  }

  // Function to update CSS variables
  function updateCSSVariable(settingId, value) {
    // Get the CSS selector from mapping
    var selector = mapping[settingId];
    if (!selector) {
      console.warn("No mapping found for setting:", settingId);
      return;
    }

    var styleId = "wcpt-customizer-" + settingId;
    var style = document.getElementById(styleId);

    // If value is empty/null/undefined, remove the style element if it exists
    if (!value) {
      if (style) {
        style.remove();
      }
      return;
    }

    // Replace %val% with the new value
    var css = selector.replace("%val%", value);

    // Add or update the style
    if (!style) {
      style = document.createElement("style");
      style.id = styleId;
      document.body.appendChild(style);
    }

    style.textContent = css;

    // handle default blanks values
    if (value) {
      Object.keys(wcpt_selector_relations).forEach(function (key) {
        if (selector.includes(key)) {
          // Append the default blank var CSS to the current style
          style.textContent += "\n" + wcpt_selector_relations[key];
        }
      });
    }
  }

  // Function to bind to a setting
  function bindToSetting(settingId) {
    var fullSettingId = "wcpt_theme_customizer[" + settingId + "]";
    var setting = wp.customize(fullSettingId);
    if (!setting) {
      return;
    }
    setting.bind(function (value) {
      updateCSSVariable(settingId, value);
      // Trigger the global change callback if it exists
      if (window.wcptCustomizer.onChange) {
        window.wcptCustomizer.onChange(settingId, value);
      }
    });
  }

  // Wait for customizer to be ready
  wp.customize.bind("preview-ready", function () {
    // Loop through all settings in the mapping
    Object.keys(mapping).forEach(function (settingId) {
      bindToSetting(settingId);
    });
  });

  // Expose the API for external use
  window.wcptCustomizer = {
    // Set a throttled callback for any setting change
    setChangeCallback: function (callback) {
      if (typeof callback === "function") {
        this.onChange = throttle(callback, 250);
      }
    },
  };

  // wcpt layout callback
  window.wcptCustomizer.setChangeCallback(function (settingId, value) {
    $(".wcpt").trigger("wcpt_layout", { source: "customizer" });
  });
})(jQuery);
