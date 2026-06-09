(function ($, view) {
  view.parent;

  view.render = function () {
    var $be = this.parent.$elm,
      parent = this.parent,
      view = this,
      data = this.parent.model.get_data();

    $be.empty(); // make blank

    function columnStructureHasAnyElements(colRows) {
      if (!colRows || !colRows.length) {
        return false;
      }
      for (var i = 0; i < colRows.length; i++) {
        if (colRows[i] && colRows[i].length) {
          return true;
        }
      }
      return false;
    }

    $.each(data, function (index, row) {
      if (!row) {
        return;
      }

      // create row
      var $row = $(
        '<div class="wcpt-block-editor-row" data-id="' + row.id + '">',
      ).data("wcpt-data", row);

      // determine column count (block editor uses column_count only)
      var column_count = parseInt(row.column_count || 1, 10);

      var _colEls = row.columns && row.columns.elements;
      var column2HasElements =
        _colEls && columnStructureHasAnyElements(_colEls[2] || _colEls["2"]);

      // With column_count 1, keep showing two columns in the editor until column 2 has no elements
      var renderAsTwoColumnLayout =
        column_count === 2 || (column_count === 1 && column2HasElements);

      if (renderAsTwoColumnLayout) {
        $row.addClass("wcpt-block-editor-row--columns");
      }

      // slider icon
      var slider_icon = $("#wcpt-icon-settings").length
          ? $("#wcpt-icon-settings").text()
          : "*",
        $slider_icon = $(slider_icon).addClass(
          "wcpt-element-block__settings-icon",
        );

      // append elements to row
      if (renderAsTwoColumnLayout) {
        var columnsConfig = row.columns || {};
        var columnsElements =
          (columnsConfig.elements &&
            $.extend(true, {}, columnsConfig.elements)) ||
          {};

        // Fallback: if there is no column structure but we have flat elements,
        // put everything into column 1, first column-row.
        if (
          (!columnsElements["1"] || !columnsElements["1"].length) &&
          (!columnsElements["2"] || !columnsElements["2"].length) &&
          row.elements &&
          row.elements.length
        ) {
          columnsElements = {
            1: [$.extend(true, [], row.elements)],
            2: [],
          };
        }

        var $columns = $('<div class="wcpt-block-editor-columns"></div>');

        // optional alignment / width hooks
        if (columnsConfig.horizontal_alignment) {
          $columns.addClass(
            "wcpt-columns-align-" + columnsConfig.horizontal_alignment,
          );
        }
        if (columnsConfig.width) {
          var rawWidth = String(columnsConfig.width);
          var widthSlug =
            rawWidth === "50%" || rawWidth === "50"
              ? "equal"
              : rawWidth.replace(/%/g, "");
          $columns.addClass("wcpt-columns-width-" + widthSlug);
        }

        ["1", "2"].forEach(function (colKey) {
          var colIndex = parseInt(colKey, 10);
          if (colIndex > 2) return;

          var colRows = columnsElements[colKey] || [];
          var $col = $(
            '<div class="wcpt-block-editor-column" data-column="' +
              colIndex +
              '"></div>',
          );

          if (column_count === 1 && column2HasElements && colIndex === 2) {
            $col.addClass("wcpt-block-editor-column--disabled");
            $col.prepend(
              '<div class="wcpt-block-editor-column-disabled-notice">' +
                "This column is disabled. Transfer or delete elements to remove it from editor." +
                "</div>",
            );
          }

          if (!colRows.length) {
            colRows = [[]];
          }

          colRows.forEach(function (rowElements, cRowIndex) {
            var $colRow = $(
              '<div class="wcpt-block-editor-column-row" data-column="' +
                colIndex +
                '" data-column-row="' +
                cRowIndex +
                '"></div>',
            );

            $.each(rowElements, function (el_index, element) {
              if (!element) return;
              var $element = $(
                '<div class="wcpt-element-block" data-type="' +
                  element.type +
                  '" data-id="' +
                  element.id +
                  '" title="Edit element settings">' +
                  view.get_label(element) +
                  $slider_icon.get(0).outerHTML +
                  "</div>",
              );
              $colRow.append($element.data("wcpt-data", element));
            });

            // add element trigger inside this column-row
            var $add_element = $(
              '<a href="#" class="wcpt-block-editor-add-element" data-column="' +
                colIndex +
                '" data-column-row="' +
                cRowIndex +
                '">+ Add element</a>',
            );
            $colRow.append($add_element);

            // delete inner column-row: show when column has multiple rows (so any row can be
            // deleted) or when this row has elements (single row can be cleared)
            if (colRows.length > 1 || rowElements.length) {
              var inner_del_icon = $("#wcpt-icon-trash-2").length
                  ? $("#wcpt-icon-trash-2").text()
                  : "x",
                $inner_del = $(
                  '<span class="wcpt-block-editor-delete-column-row" title="Delete row in column">' +
                    inner_del_icon +
                    "</span>",
                );
              $colRow.append($inner_del);
            }

            $col.append($colRow);
          });

          // add new row trigger for this column
          var $add_col_row = $(
            '<a href="#" class="wcpt-block-editor-add-column-row" data-column="' +
              colIndex +
              '">+ Add row</a>',
          );
          $col.append($add_col_row);

          $columns.append($col);
        });

        $row.append($columns);
      } else {
        // legacy / 1-column layout
        $.each(row.elements, function (el_index, element) {
          if (!element) return;
          var $element = $(
            '<div class="wcpt-element-block" data-type="' +
              element.type +
              '" data-id="' +
              element.id +
              '" title="Edit element settings">' +
              view.get_label(element) +
              $slider_icon.get(0).outerHTML +
              "</div>",
          );
          $row.append($element.data("wcpt-data", element));
        });

        // add element trigger
        var $add_element = $(
          '<a href="#" class="wcpt-block-editor-add-element">+ Add element</a>',
        );
        $row.append($add_element);
      }

      // row actions container (settings + delete) for easy positioning
      var $row_actions = $('<div class="wcpt-block-editor-row-actions"></div>');

      if (parent.config.edit_row) {
        var icon = $("#wcpt-icon-settings").length
            ? $("#wcpt-icon-settings").text()
            : "*",
          $settings = $(
            '<span class="wcpt-block-editor-edit-row" title="Edit row settings">' +
              icon +
              "</span>",
          );

        view.maybe_add_active_settings_class($settings, row);
        $row_actions.append($settings);
      }

      // delete row trigger: show when multiple rows, or when this row has content
      // (for last row, delete clears content instead of removing row)
      var row_has_content =
        data.length > 1 ||
        (function () {
          if (
            typeof row.elements !== "undefined" &&
            row.elements &&
            row.elements.length
          ) {
            return true;
          }
          if (row.columns && row.columns.elements) {
            var ce = row.columns.elements;
            var maxCol = column_count === 2 || column2HasElements ? 2 : 1;
            for (var c = 1; c <= maxCol; c++) {
              if (ce[c] && Array.isArray(ce[c])) {
                for (var r = 0; r < ce[c].length; r++) {
                  if (ce[c][r] && ce[c][r].length) return true;
                }
              }
            }
          }
          return false;
        })();
      if (parent.config.delete_row && row_has_content) {
        var icon = $("#wcpt-icon-trash-2").length
            ? $("#wcpt-icon-trash-2").text()
            : "x",
          $del = $(
            '<span class="wcpt-block-editor-delete-row" title="Delete row">' +
              icon +
              "</span>",
          );
        $row_actions.append($del);
      }

      if ($row_actions.children().length) {
        $row.append($row_actions);
      }

      // append row to editor
      $be.append($row);
    });

    // add row trigger
    if (this.parent.config.add_row) {
      var $add_row = $(
        '<a href="#" class="wcpt-block-editor-add-row">+ Add row</a>',
      );
      $be.append($add_row);
    }

    // make rows sortable
    if ($(".wcpt-block-editor-row", $be).length > 1) {
      $be.sortable({
        items: ".wcpt-block-editor-row",
        disabled: false,
        placeholder: "wcpt-block-editor-row ui-sortable-placeholder",
        start: function (event, ui) {
          // make the placeholder match the dragged row height (including column layouts)
          ui.placeholder.height(ui.item.height());
        },
      });
    } else {
      $be.sortable({
        items: ".wcpt-block-editor-row",
        disabled: true,
      });
    }

    // connect with
    var cw = this.parent.config.connect_with,
      $lb = $be.closest(".wcpt-block-editor-lightbox-screen");
    if ($lb.length) {
      cw =
        '[data-partial="' +
        $lb.attr("data-partial") +
        '"].wcpt-block-editor-lightbox-screen ' +
        cw;
    }

    // make blocks sortable
    $(".wcpt-block-editor-row", $be).each(function () {
      var $row = $(this),
        rowData = $row.data("wcpt-data") || {},
        useColumnRows = $row.children(".wcpt-block-editor-columns").length > 0;

      if (useColumnRows) {
        $row.find(".wcpt-block-editor-column-row").sortable({
          items: ".wcpt-element-block",
          connectWith: cw + ", " + cw + " .wcpt-block-editor-column-row",
          placeholder: "wcpt-element-block-placeholder",
          forcePlaceholderSize: true,
          start: function (event, ui) {
            ui.helper.width(ui.helper.width() + 0.5).height("");

            ui.placeholder
              .width(ui.item.outerWidth())
              .addClass("wcpt-element-block");
          },
        });
      } else {
        $row.sortable({
          items: ".wcpt-element-block",
          connectWith: cw + ", " + cw + " .wcpt-block-editor-column-row",
          placeholder: "wcpt-element-block-placeholder",
          forcePlaceholderSize: true,
          start: function (event, ui) {
            ui.helper.width(ui.helper.width() + 0.5).height("");

            ui.placeholder
              .width(ui.item.outerWidth())
              .addClass("wcpt-element-block");
          },
        });
      }
    });
  };

  view.maybe_add_active_settings_class = function ($settings_icon, row_data) {
    ((active_settings = false),
      (conditions = [
        "custom_field_enabled",
        "attribute_enabled",
        "category_enabled",
        "price_enabled",
        "stock_enabled",
        "product_type_enabled",
        "store_timings_enabled",
        "user_role_enabled",
      ]));

    if (row_data.condition) {
      conditions.forEach((_condition) => {
        if (row_data.condition[_condition]) {
          active_settings = true;
        }
      });
    }

    if (row_data.style) {
      for (const [selector, props] of Object.entries(row_data.style)) {
        for (const [prop, value] of Object.entries(props)) {
          if (value) {
            active_settings = true;
          }
        }
      }
    }

    if (active_settings) {
      $settings_icon.addClass("wcpt-block-editor-edit-row--has-settings");
      $settings_icon.attr(
        "title",
        "Row has active style or condition settings",
      );
    } else {
      $settings_icon.removeClass("wcpt-block-editor-edit-row--has-settings");
      $settings_icon.attr(
        "title",
        "This row has no active style or condition settings",
      );
    }
  };

  view.lightbox = function (options) {
    var default_ops = {
      $element: null,
      duplicate_remove: true,
      attr: {},
    };

    $.extend(true, default_ops, options);

    // create
    var $lightbox = $(
        '<div class="wcpt-block-editor-lightbox-screen"><div class="wcpt-block-editor-lightbox-content"></div></div>',
      ),
      $tray = $('<div class="wcpt-block-editor-lightbox-tray"></div>'),
      close_icon = $("#wcpt-icon-x").length ? $("#wcpt-icon-x").text() : "",
      $close = $(
        '<span class="wcpt-block-editor-lightbox-close" title="Close">' +
          close_icon +
          "</span>",
      ),
      remove_icon = $("#wcpt-icon-trash").length
        ? $("#wcpt-icon-trash").text()
        : "",
      $remove = $(
        '<span class="wcpt-block-editor-lightbox-remove" title="Trash">' +
          remove_icon +
          "</span>",
      ),
      duplicate_icon = $("#wcpt-icon-copy").length
        ? $("#wcpt-icon-copy").text()
        : "",
      $duplicate = $(
        '<span class="wcpt-block-editor-lightbox-duplicate" title="Clone">' +
          duplicate_icon +
          "</span>",
      );

    if (options.duplicate_remove) {
      $tray.append($duplicate.add($remove));
    } else {
      $tray.append($close);
    }

    $("> .wcpt-block-editor-lightbox-content", $lightbox).append($tray);

    $("body").append($lightbox);

    $lightbox
      .data("wcpt-block-element", options.$element ? options.$element : "")
      .attr({
        "wcpt-controller": "edit-element-lightbox",
        "wcpt-model-key": "block_element_data",
      })
      .attr(options.attr)
      .children(".wcpt-block-editor-lightbox-content")
      .append($('script[data-wcpt-partial="' + options.partial + '"]').text())
      .end()
      .show();

    // add modal flag
    $("body").addClass("wcpt-be-lightbox-on");

    // destroy
    // -- by clicking outside the lightbox content
    var _ = this;
    $lightbox.on("click", function (e) {
      if ($(e.target).is($lightbox)) {
        $lightbox.trigger("destroy");
      }
    });
    // -- by clicking the 'X' close button
    $(
      "> .wcpt-block-editor-lightbox-content > .wcpt-block-editor-lightbox-tray > .wcpt-block-editor-lightbox-close, > .wcpt-block-editor-lightbox-content > .wcpt-block-editor-lightbox-tray > .wcpt-block-editor-lightbox-done",
      $lightbox,
    ).on("click", function () {
      $lightbox.trigger("destroy");
    });
    // -- destroy event handler
    $lightbox.on("destroy", function () {
      $lightbox.change();
      $lightbox.remove();

      // remove modal flag
      if (!$(".wcpt-block-editor-lightbox-screen").length) {
        $("body").removeClass("wcpt-be-lightbox-on");
      }
    });

    // search
    var $search_input = $(
      ".wcpt-block-editor-element-type-list__search__input",
      $lightbox,
    );

    $search_input.on("keyup", function () {
      var $this = $(this),
        val = $this.val().trim().toLowerCase(),
        $list = $this.closest(".wcpt-block-editor-element-type-list"),
        $elm_button = $(".wcpt-block-editor-element-type", $list);

      if (!val) {
        $elm_button.show();
        return;
      }

      $elm_button.each(function () {
        var $this = $(this),
          label = $this.text().toLowerCase().trim();
        if (label.indexOf(val) == -1) {
          $this.hide();
        } else {
          $this.show();
        }
      });
    });

    $search_input.first().focus();

    return $lightbox;
  };

  ((view.get_label = function (element) {
    var type_unslug = element.type.replace(
        /(_|^)([^_]?)/g,
        function (_, prep, letter) {
          return (prep && " ") + letter.toUpperCase();
        },
      ),
      label = type_unslug;

    switch (element.type) {
      case "attribute":
      case "attribute_filter":
        if (element.number_of_attributes === "multiple") {
          var layout =
            element.property_list_options &&
            element.property_list_options.layout
              ? element.property_list_options.layout
              : "row";
          label =
            "Attributes: <span>" +
            view.sanitize(layout.charAt(0).toUpperCase() + layout.slice(1)) +
            " layout</span>";
        } else if (element.attribute_name) {
          if (
            element.attribute_name == "_custom" &&
            element.custom_attribute_name
          ) {
            label = element.custom_attribute_name + " (custom)";
          } else {
            label = element.attribute_name;
            if (typeof window.wcpt_attributes == "object") {
              $.each(window.wcpt_attributes, function (key, val) {
                if (val.attribute_name == element.attribute_name) {
                  label = val.attribute_label;
                }
              });
            }
          }

          label =
            "Attribute: <span>" +
            view.sanitize(label.charAt(0).toUpperCase() + label.substr(1)) +
            "</span>";
        }
        break;

      case "custom_field":
      case "custom_field_filter":
        if (element.field_name) {
          label =
            "Custom field: <span>" +
            view.sanitize(element.field_name) +
            "</span>";
        }
        break;

      case "taxonomy":
      case "taxonomy_filter":
        if (element.taxonomy) {
          label =
            "Taxonomy: <span>" +
            view.sanitize(
              element.taxonomy.substr(0, 6) +
                (element.taxonomy.length > 6 ? "..." : ""),
            ) +
            "</span>";
        }
        break;

      case "product_link":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="link" src="' +
          wcpt_icons +
          'link.svg">';
        label = image_icon + "Product link";
        break;

      case "checkbox":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="check-square" src="' +
          wcpt_icons +
          'check-square.svg">';
        label = image_icon + "Checkbox";
        break;

      case "title":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="box" src="' +
          wcpt_icons +
          'box.svg">';
        label = image_icon + "Title";
        break;

      case "dimensions":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="package" src="' +
          wcpt_icons +
          'package.svg">';
        label = image_icon + "Dimensions";
        break;

      case "height":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="package" src="' +
          wcpt_icons +
          'package.svg">';
        label = image_icon + "Height";
        break;

      case "width":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="package" src="' +
          wcpt_icons +
          'package.svg">';
        label = image_icon + "Width";
        break;

      case "length":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="package" src="' +
          wcpt_icons +
          'package.svg">';
        label = image_icon + "Length";
        break;

      case "weight":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="package" src="' +
          wcpt_icons +
          'package.svg">';
        label = image_icon + "Weight";
        break;

      case "property_list":
      case "multi_property_grid":
        var layout = element.layout ? element.layout : "row";
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="grid" src="' +
          wcpt_icons +
          'grid.svg">';
        label =
          image_icon +
          "Multi property: <span>" +
          view.sanitize(layout.charAt(0).toUpperCase() + layout.slice(1)) +
          " layout</span>";
        break;

      case "price":
      case "on_sale":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="dollar-sign" src="' +
          wcpt_icons +
          'dollar-sign.svg">';
        label = image_icon + (element.type == "on_sale" ? "On sale" : "Price");
        break;

      case "text":
      case "text__col":
        var icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="file-text" src="' +
          wcpt_icons +
          'file-text.svg">';
        if (element.text) {
          label = view.sanitize(element.text);
          if (label.length > 20) {
            label = label.substring(0, 20) + "...";
          }
          label = icon + 'Text: <span>"' + label + '"</span>';
        } else {
          label = icon + "Text: <span>*Empty*</span>";
        }
        break;

      case "short_description":
      case "excerpt":
      case "content":
        var icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="file-text" src="' +
          wcpt_icons +
          'file-text.svg">';
        var label;
        if (element.type == "content") {
          label = "Content";
        } else {
          label = "Short description";
        }

        if (element.limit_by) {
          if (element.limit_by == "words") {
            label +=
              ": <span>" + view.sanitize(element.limit) + " words</span>";
          } else if (element.limit_by == "lines") {
            if (element.line_clamp) {
              if (element.line_clamp > 1) {
                label +=
                  ": <span>" +
                  view.sanitize(element.line_clamp) +
                  " lines</span>";
              } else {
                label +=
                  ": <span>" +
                  view.sanitize(element.line_clamp) +
                  " line</span>";
              }
            }
          }
        }
        label = icon + label;
        break;

      case "button":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="mouse-pointer" src="' +
          wcpt_icons +
          'mouse-pointer.svg"> Button';

        if (element.link) {
          const link_label = element.link.split("_").join(" ");
          label += ": <span> " + link_label + "</span>";
        }

        break;

      case "cart_button":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="shopping-cart" src="' +
          wcpt_icons +
          'shopping-cart.svg"> Cart Button';

        if (element.link) {
          const link_label = element.link.split("_").join(" ");
          label += ": <span> " + link_label + "</span>";
        }

        break;

      case "link_button":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="link" src="' +
          wcpt_icons +
          'link.svg"> Link Button';

        if (element.link) {
          const link_label = element.link.split("_").join(" ");
          label += ": <span> " + link_label + "</span>";
        }

        break;

      case "download_button":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="download" src="' +
          wcpt_icons +
          'download.svg"> Download';

        if (element.link) {
          const link_label = element.link.split("_").join(" ");
          label += ": <span> " + link_label + "</span>";
        }

        break;

      case "date":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="calendar" src="' +
          wcpt_icons +
          'calendar.svg"> Date';

        if (element.date_source) {
          const cf_limit = 10;
          if (element.date_source == "publish_date") {
            label += ": <span>publish date</span>";
          } else if (element.date_source == "wordpress_custom_field") {
            var inner = "";
            if (element.custom_field_name) {
              inner =
                " (" +
                view.sanitize(
                  view.truncate(element.custom_field_name, cf_limit),
                ) +
                ")";
            }
            label += ": <span>custom field" + inner + "</span>";
          } else if (element.date_source == "acf_custom_field") {
            var inner = "";
            if (element.acf_field_name) {
              inner =
                " (" +
                view.sanitize(view.truncate(element.acf_field_name, cf_limit)) +
                ")";
            }
            label += ": <span>custom field" + inner + "</span>";
          }
        }

        break;

      case "html":
      case "html__col":
        var icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="code" src="' +
          wcpt_icons +
          'code.svg">';
        if (element.html) {
          label = view.sanitize(element.html);
          if (label.length > 20) {
            label = label.substring(0, 20) + "...";
          }
          label = icon + 'HTML: <span>"' + label + '"</span>';
        } else {
          label = icon + "HTML: <span>*Empty*</span>";
        }

        break;

      case "shortcode":
        if (element.shortcode) {
          var shortcode = element.shortcode;
          if (shortcode.length > 15) {
            shortcode = shortcode.substring(0, 15) + "...";
          }

          label = "Shortcode: <span>" + view.sanitize(shortcode) + "</span>";
        }
        break;

      case "sku":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="hash" src="' +
          wcpt_icons +
          'hash.svg">';
        label = image_icon + "SKU";
        break;

      case "gtin":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="hash" src="' +
          wcpt_icons +
          'hash.svg">';
        label = image_icon + "GTIN";
        break;

      case "author":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="user" src="' +
          wcpt_icons +
          'user.svg">';
        label = image_icon + "Author";
        break;

      case "position_number":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="hash" src="' +
          wcpt_icons +
          'hash.svg">';
        label = image_icon + "Position Number";
        break;

      case "cart_quantity":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="shopping-cart" src="' +
          wcpt_icons +
          'shopping-cart.svg">';
        label = image_icon + "Cart quantity";
        break;

      case "audio_player":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="music" src="' +
          wcpt_icons +
          'music.svg">';
        label = image_icon + "Audio player";
        break;

      case "video_player":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="video" src="' +
          wcpt_icons +
          'video.svg">';
        label = image_icon + "Video player";
        break;

      case "cart_form":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="shopping-cart" src="' +
          wcpt_icons +
          'shopping-cart.svg">';
        label = image_icon + "Cart Form";
        break;

      case "favorite":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="heart" src="' +
          wcpt_icons +
          'heart.svg">';
        label = image_icon + "Favorite";
        break;

      case "view_switcher":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="eye" src="' +
          wcpt_icons +
          'eye.svg">';
        label = image_icon + "View Switcher";
        break;

      case "media_image":
      case "media_image__col":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="image" src="' +
          wcpt_icons +
          'image.svg"> ' +
          "Media Image";
        var url = element.use_external_source
          ? element.external_source
          : element.url;
        if (url) {
          var imageName = url.split("/").pop();
          if (imageName.length > 10) {
            imageName = imageName.substring(0, 10) + "...";
          }
          label += ': <span>"' + imageName + '"</span>';
        }
        break;

      case "product_image":
      case "gallery":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="image" src="' +
          wcpt_icons +
          'image.svg"> ' +
          element.type.split("_").join(" ").charAt(0).toUpperCase() +
          element.type.split("_").join(" ").slice(1);
        break;

      case "filter_modal":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="filter" src="' +
          wcpt_icons +
          'filter.svg"> ' +
          "Filter modal";
        break;

      case "sort_modal":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="arrows-up-down" src="' +
          wcpt_icons +
          'arrows-up-down.svg"> ' +
          "Sort modal";
        break;

      case "line_separator":
        label = "← Line separator →";
        break;

      case "stick_separator":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="vertical-separator" src="' +
          wcpt_icons +
          'vertical-separator.svg"> ' +
          "Stick separator";
        break;

      case "space":
      case "space__col":
        label = "← Space →";
        break;

      case "product_id":
        label = "Product ID";
        break;

      case "sort_by":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="arrows-up-down" src="' +
          wcpt_icons +
          'arrows-up-down.svg"> Sort by';
        break;

      case "sorting":
      case "sorting_icons":
        if (element.orderby) {
          label =
            '<img class="wcpt-be-label-icon" data-wcpt-icon="arrows-up-down" src="' +
            wcpt_icons +
            'arrows-up-down.svg"> Sort by: <span>';

          if (
            element.orderby == "meta_value_num" ||
            element.orderby == "meta_value"
          ) {
            label += " CF - " + element.meta_key;
          } else if (element.orderby == "id") {
            label += " Product ID";
          } else if (element.orderby == "sku") {
            label += " SKU as text";
          } else if (element.orderby == "sku_num") {
            label += " SKU as integer";
          } else if (
            -1 !== $.inArray(element.orderby, ["attribute", "attribute_num"]) &&
            element.orderby_attribute
          ) {
            var attribute_label = element.orderby_attribute;
            $.each(wcpt_attributes, function (index, attribute) {
              if (
                "pa_" + attribute.attribute_name ==
                element.orderby_attribute
              ) {
                attribute_label = attribute.attribute_label;
                return false;
              }
            });

            label += " Attribute - " + view.sanitize(attribute_label);
          } else {
            label +=
              element.orderby[0].toUpperCase() + element.orderby.substring(1);
          }

          label += "</span>";
        }
        break;

      case "search":
        if (
          element.target &&
          Array.isArray(element.target) &&
          element.target.length
        ) {
          if (element.target.length == 1) {
            var field = element.target[0];
            if (
              field == "attribute" &&
              element.attributes &&
              element.attributes.length
            ) {
              field += ": " + element.attributes.join(", ");
            }

            if (
              field == "custom_field" &&
              element.custom_fields &&
              element.custom_fields.length
            ) {
              field += ": " + element.custom_fields.join(", ");
            }

            if (field == "sku") {
              field = "SKU";
            }
          } else {
            var mixed_fields = element.target.join(", ");
            if (mixed_fields.length > 45) {
              mixed_fields = mixed_fields.substring(0, 45) + "...";
            }
            var field =
              "mixed " + element.target.length + " (" + mixed_fields + ")";
          }
          field = view.sanitize(field);

          if (field.length > 20) {
            field = field.substring(0, 20) + "...";
          }

          label =
            '<img class="wcpt-be-label-icon" data-wcpt-icon="search" src="' +
            wcpt_icons +
            'search.svg"> Search: <span>' +
            field +
            "</span>";
        }

        break;

      case "remove":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="x-circle" src="' +
          wcpt_icons +
          'x-circle.svg"> Remove';
        break;

      case "availability":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="check-circle" src="' +
          wcpt_icons +
          'check-circle.svg"> Availability';
        break;

      case "tooltip":
      case "tooltip__nav":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="help-circle" src="' +
          wcpt_icons +
          'help-circle.svg"> Tooltip';
        break;

      case "quantity":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="layers" src="' +
          wcpt_icons +
          'layers.svg"> Quantity';
        break;

      case "result_count":
      case "results_per_page":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="layers" src="' +
          wcpt_icons +
          'layers.svg"> ' +
          element.type.split("_").join(" ").charAt(0).toUpperCase() +
          element.type.split("_").join(" ").slice(1);
        break;

      case "total":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="plus-circle" src="' +
          wcpt_icons +
          'plus-circle.svg"> Total';
        break;

      case "rating":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="star" src="' +
          wcpt_icons +
          'star.svg"> Rating';
        break;

      case "apply_reset":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="mouse-pointer" src="' +
          wcpt_icons +
          'mouse-pointer.svg"> Apply / Reset';
        break;

      case "clear_filters":
        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="rotate-ccw" src="' +
          wcpt_icons +
          'rotate-ccw.svg"> Clear filters';
        break;

      case "date":
        if (element.type == "date") {
          label = "Date";
        } else if (element.type == "date_picker_filter") {
          label = "Date Picker Filter";
        }

        if (element.date_source) {
          if (element.date_source == "publish_date") {
            label += ": <span>publish date</span>";
          } else if (element.date_source == "wordpress_custom_field") {
            var inner = "";
            if (element.custom_field_name) {
              inner = " (" + view.truncate(element.custom_field_name, 15) + ")";
            }
            label += ": <span>custom field" + inner + "</span>";
          } else if (element.date_source == "acf_custom_field") {
            var inner = "";
            if (element.acf_field_name) {
              inner = " (" + view.truncate(element.acf_field_name, 15) + ")";
            }
            label += ": <span>acf" + inner + "</span>";
          }
        }

        break;

      case "download_csv":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="download" src="' +
          wcpt_icons +
          'download.svg">';
        label = image_icon + "Download CSV";
        break;

      case "regular_price__on_sale":
        label = "Regular price";
        break;

      case "icon":
      case "icon__col":
        if (element.icon_source && element.icon_source == "custom") {
          label = "Icon: <span>Custom SVG</span>";
        } else if (element.name) {
          label =
            'Icon: <img class="wcpt-icon-rep" src="' +
            wcpt_icons +
            element.name +
            '.svg">';
        } else {
          label = "Icon: <span>None</span>";
        }
        break;

      case "dot":
      case "dot__col":
        label = "Dot '⋅'";
        break;

      case "compare":
        image_icon =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="columns" src="' +
          wcpt_icons +
          'columns.svg">';
        label = image_icon + "Compare";
        break;

      case "select_variation":
        label = "Select variation";

        // radio single
        if (
          typeof element.display_type == "undefined" ||
          element.display_type == "radio_single"
        ) {
          if (element.variation_name) {
            var name = view.sanitize(element.variation_name);
            label = "Select variation: <span>" + name + "</span>";
          } else {
            label = "Select variation: <span>*Single variation*</span>";
          }

          // radio multiple
        } else if (element.display_type == "radio_multiple") {
          label = "Select variation: <span>*Radio buttons*</span>";

          // dropdown
        } else if (element.display_type == "dropdown") {
          label = "Select variation: <span>*Dropdown*</span>";
        }

        label =
          '<img class="wcpt-be-label-icon" data-wcpt-icon="chevron-down-circle" src="' +
          wcpt_icons +
          'chevron-down-circle.svg"> ' +
          label;

        break;
    }

    if (
      $.inArray(element.type, [
        "category",
        "attribute",
        "taxonomy",
        "tags",
        "brand",
      ]) > -1
    ) {
      label =
        '<img class="wcpt-be-label-icon" data-wcpt-icon="folder" src="' +
        wcpt_icons +
        'folder.svg"> ' +
        label;
    }

    if (element.type.endsWith("_filter")) {
      label =
        '<img class="wcpt-be-label-icon" data-wcpt-icon="filter" src="' +
        wcpt_icons +
        'filter.svg"> ' +
        label;
    }

    if (
      element.type.split("__").length == 2 &&
      -1 ==
        $.inArray(element.type, [
          "space__col",
          "text__col",
          "html__col",
          "media_image__col",
          "icon__col",
          "dot__col",
          "tooltip__nav",
        ])
    ) {
      var string = element.type.split("__")[0];
      label =
        string.charAt(0).toUpperCase() + string.slice(1).replace("_", " ");
    }

    if (element._pro_required && !window.wcpt_pro) {
      label = label + " (Pro required)";
    }

    return view.sanitize_preserve_span_and_image(label);
    // return label;
  }),
    (view.mark_elm = function (row_index, elm_index) {
      var $be = this.parent.$elm,
        $row = $be.children(".wcpt-block-editor-row").eq(row_index),
        $target;

      if ($row.length) {
        var $elm = $row.children(".wcpt-element-block").eq(elm_index);

        if ($elm.length) {
          $target = $elm;
        } else {
          $target = $row;
        }
      }

      $target.addClass("wcpt-be-mark");
      setTimeout(function () {
        $target.removeClass("wcpt-be-mark");
      }, 500);
    }));

  view.sanitize = function (str, preserve_span_and_image) {
    if (preserve_span_and_image) {
      // span
      str = str
        .replace(/<span>/g, "{span_open}")
        .replace(/<\/span>/g, "{span_close}");

      // image
      var regex = /<img[^<]+?>/gim,
        image_matches = str.match(regex),
        img_placeholders = [];

      if (image_matches) {
        for (var i = 0; i < image_matches.length; i++) {
          img_placeholders.push(image_matches[i]);
          str = str.replace(image_matches[i], "#img-placeholder-" + i + "#");
        }
      }
    }

    str = str.replace(/</g, "&lt;").replace(/>/g, "&gt;");

    if (preserve_span_and_image) {
      // span
      str = str
        .replace(/{span_open}/g, "<span>")
        .replace(/{span_close}/g, "</span>");

      // image
      if (image_matches) {
        for (var i = 0; i < image_matches.length; i++) {
          str = str.replace("#img-placeholder-" + i + "#", image_matches[i]);
        }
      }
    }

    return str;
  };

  view.sanitize_preserve_span_and_image = function (str) {
    return view.sanitize(str, true);
  };

  view.truncate = function (str, limit) {
    if (!limit) {
      limit = 20;
    }

    if (str.length > limit) {
      str = str.substring(0, limit) + "...";
    }

    return str;
  };
})(jQuery, WCPT_Block_Editor.View);
