(function ($) {
  $(document).ready(function () {
    var textControl = $(".wcpt-customizer-number-picker");

    textControl.on("keydown", function (e) {
      var currentValue = $(this).val();
      var numericValue = parseFloat(currentValue);
      var unit = currentValue.replace(numericValue, "");

      if (e.which === 38) {
        // Up arrow key
        if (e.shiftKey) {
          numericValue += 10;
        } else {
          numericValue++;
        }
      } else if (e.which === 40) {
        // Down arrow key
        if (e.shiftKey) {
          numericValue -= 10;
        } else {
          numericValue--;
        }
      } else {
        return;
      }

      e.preventDefault();
      $(this)
        .val(numericValue + unit)
        .trigger("change");
    });
  });
})(jQuery);

(function ($) {
  "use strict";

  // Initialize number pickers with unit selectors
  function initNumberPickers() {
    $(".wcpt-customizer-number-picker").each(function () {
      var $input = $(this);
      var $wrapper = $('<div class="wcpt-number-picker-wrapper"></div>');
      var $unitSelect = $('<select class="wcpt-unit-select"></select>');

      // Add units
      var units = ["px", "em", "rem", "%"];
      units.forEach(function (unit) {
        $unitSelect.append(
          $('<option value="' + unit + '">' + unit + "</option>")
        );
      });

      // Wrap input and add unit select
      $input.wrap($wrapper);
      $input.after($unitSelect);

      // Handle unit changes
      $unitSelect.on("change", function () {
        var value = $input.val();
        var unit = $(this).val();

        // Remove existing unit
        value = value.replace(/[a-z%]+$/, "");

        // Add new unit
        $input.val(value + unit);
        $input.trigger("change");
      });

      // Set initial unit
      var value = $input.val();
      var currentUnit = value.match(/[a-z%]+$/);
      if (currentUnit) {
        $unitSelect.val(currentUnit[0]);
      }
    });
  }

  // Initialize spectrum color pickers
  function initColorPickers() {
    $(".wcpt-customizer-color-picker").each(function () {
      var $input = $(this);
      $input.spectrum({
        color: $input.val(),
        flat: false,
        allowEmpty: true,
        showAlpha: true,
        preferredFormat: "rgb",
        clickoutFiresChange: true,
        showInput: true,
        showButtons: true,
        show: function () {
          $input.spectrum("container").css("z-index", "999999");
        },
        move: function (color) {
          if (color) {
            $input.val(color.toRgbString()).trigger("change");
          } else {
            $input.val("").trigger("change");
          }
        },
        change: function (color) {
          if (color) {
            $input.val(color.toRgbString()).trigger("change");
          } else {
            $input.val("").trigger("change");
          }
        },
        hide: function (color) {
          if (!color) {
            $input.val("").trigger("change");
          }
        },
      });
    });
  }

  // Initialize when customizer is ready
  wp.customize.bind("ready", function () {
    initNumberPickers();
    initColorPickers();
  });

  // Handle number-text inputs
  $(document).on("input", ".wcpt-number-text", function () {
    var $input = $(this);
    var value = $input.val();

    // Extract number and unit
    var match = value.match(/^(-?\d*\.?\d+)(px|em|rem|%|vh|vw)?$/);
    if (match) {
      var number = parseFloat(match[1]);
      var unit = match[2] || "px";

      // Store the unit for later use
      $input.data("unit", unit);
    }
  });

  // Handle up/down arrow keys
  $(document).on("keydown", ".wcpt-number-text", function (e) {
    var $input = $(this);
    var id = $input.attr("id");
    var value = $input.val();
    var match = value.match(/^(-?\d*\.?\d+)(px|em|rem|%|vh|vw)?$/);

    if (match) {
      var number = parseFloat(match[1]);
      var unit = match[2] || "px";
      var step = unit === "em" ? 0.1 : parseFloat($input.attr("step")) || 1;

      // Multiply step by 10 if shift key is pressed
      if (e.shiftKey) {
        step *= 10;
      }

      if (e.key === "ArrowUp") {
        e.preventDefault();
        // Fix floating point precision
        number = Math.round((number + step) * 100) / 100;
        $input.val(number + unit).trigger("change");
      } else if (e.key === "ArrowDown") {
        e.preventDefault();
        // Fix floating point precision
        number = Math.max(0, Math.round((number - step) * 100) / 100);
        $input.val(number + unit).trigger("change");
      }
      // starter values for number inputs
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      if (id.includes("border_width") || id.includes("border_radius")) {
        $input.val("1px").trigger("change");
      } else if (id.includes("search_height")) {
        $input.val("20px").trigger("change");
      } else if (id.includes("search_width")) {
        $input.val("150px").trigger("change");
      } else if (id.includes("font_size")) {
        $input.val("12px").trigger("change");
      } else {
        $input.val("5px").trigger("change");
      }
    }
  });
})(jQuery);
