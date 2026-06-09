(function ($, ctrl) {
  ctrl.get_parent = function (elm) {
    return $(elm).closest(".wcpt-block-editor").data("wcpt_block_editor");
  };

  /** True if any inner row in a column has at least one element. */
  ctrl.column_structure_has_any_elements = function (colRows) {
    if (!colRows || !colRows.length) {
      return false;
    }
    for (var i = 0; i < colRows.length; i++) {
      if (colRows[i] && colRows[i].length) {
        return true;
      }
    }
    return false;
  };

  /**
   * Row is edited with nested column DOM (two columns) when layout is 2-col,
   * or 1-col but column 2 still has elements (second column stays visible until empty).
   */
  ctrl.row_uses_nested_columns_in_editor = function (row) {
    if (!row) {
      return false;
    }
    var colCount = parseInt(row.column_count || 1, 10);
    if (colCount === 2) {
      return true;
    }
    if (colCount !== 1) {
      return false;
    }
    var els = row.columns && row.columns.elements;
    var col2 = els && (els[2] || els["2"]);
    return !!(els && ctrl.column_structure_has_any_elements(col2));
  };

  /**
   * Mirror column 1 into row.elements (storefront / legacy flat list). Column 2 stays in columns only.
   * Must run for both 1- and 2-column layouts whenever columns.elements exists, so row.elements does
   * not go stale while editing nested columns (e.g. after deletes in 2-col mode).
   */
  ctrl.sync_row_elements_from_column_one = function (row) {
    if (!row || !row.columns || !row.columns.elements) {
      return;
    }
    var flat = [];
    (row.columns.elements[1] || []).forEach(function (rowEls) {
      $.each(rowEls, function (_, el) {
        if (el) {
          flat.push(el);
        }
      });
    });
    row.elements = flat;
  };

  ctrl.add_element = function (element, row_index, elm_index) {
    var model = WCPT_Block_Editor.Ctrl.get_parent(this).model;
    model.add_element(element, row_index, elm_index);
    model.parent.$elm
      .children(".wcpt-block-editor-row")
      .eq(row_index)
      .children(".wcpt-element-block")
      .eq(elm_index)
      .addClass("editing");
  };

  ctrl.remove_element = function (element, row_index, elm_index) {
    var model = WCPT_Block_Editor.Ctrl.get_parent(this).model;
    model.remove_element(element, row_index, elm_index);
  };

  ctrl.add_row = function (e) {
    e.preventDefault();

    var model = WCPT_Block_Editor.Ctrl.get_parent(this).model;
    model.add_row();
  };

  (ctrl.edit_row = function (e) {
    var $this = $(this),
      $row = $(e.target).closest(".wcpt-block-editor-row"),
      row = $row.data("wcpt-data"),
      row_index = $row.index(),
      parent = WCPT_Block_Editor.Ctrl.get_parent(this);

    // row is not editable
    if (!parent.config.edit_row) {
      return;
    }

    var $lightbox = parent.view.lightbox({
      partial: parent.config.edit_row,
      attr: {
        "data-row-index": row_index,
        "wcpt-initial-data": "cell_row",
      },
      $row: $row,
      duplicate_remove: true,
    });

    // transfer data to block from lightbox
    dominator_ui.init($lightbox, row);

    $lightbox.on("change", function () {
      var updated = $lightbox.data("wcpt-data");

      // column_count is the only flag we care about
      var prev_col_count = parseInt(row.column_count || 1, 10),
        new_col_count = parseInt(updated.column_count || 1, 10);

      // if switching from 1 column to 2, initialize columns structure from existing elements
      if (prev_col_count !== 2 && new_col_count === 2) {
        if (!updated.columns) {
          updated.columns = { alignment: "justify", width: "auto", elements: {} };
        }
        if (!updated.columns.elements) {
          updated.columns.elements = {};
        }
        if (!updated.columns.elements[1] && Array.isArray(updated.elements)) {
          // put all existing elements into first column, first row
          updated.columns.elements[1] = [updated.elements.map(function (el) {
            return el;
          })];
        }
        if (!updated.columns.elements[2]) {
          updated.columns.elements[2] = [];
        }
      }

      // switching from 2 to 1 column: merge nested data from the model row, then flatten col1 to elements.
      if (prev_col_count === 2 && new_col_count !== 2) {
        var sourceRow = parent.model.get_data()[row_index] || row;
        updated.columns = $.extend(true, {}, sourceRow.columns, updated.columns);
        updated.columns.elements = updated.columns.elements || {};
        var mergedEls = updated.columns.elements;
        var origEls = sourceRow.columns && sourceRow.columns.elements;
        if (origEls) {
          ["1", "2"].forEach(function (k) {
            var updCol = mergedEls[k] || mergedEls[parseInt(k, 10)];
            if (!ctrl.column_structure_has_any_elements(updCol)) {
              var origCol = origEls[k] || origEls[parseInt(k, 10)];
              if (ctrl.column_structure_has_any_elements(origCol)) {
                mergedEls[k] = (origCol || []).map(function (innerRow) {
                  return (innerRow || []).map(function (el) {
                    return el ? $.extend(true, {}, el) : el;
                  });
                });
              }
            }
          });
        }
        ctrl.sync_row_elements_from_column_one(updated);
      }

      parent.model.update_row(updated, row_index);
    });

    var $tray = $(
      "> .wcpt-block-editor-lightbox-content > .wcpt-block-editor-lightbox-tray",
      $lightbox
    );
    // remove
    $("> .wcpt-block-editor-lightbox-remove", $tray).on("click", function () {
      $lightbox.trigger("destroy");
      parent.model.remove_row(row_index);
    });

    // duplicate
    $("> .wcpt-block-editor-lightbox-duplicate", $tray).on(
      "click",
      function () {
        parent.model.duplicate_row(row_index);
        $lightbox.trigger("destroy");
      }
    );
  }),
    (ctrl.delete_row = function (e) {
      var $this = $(this),
        $row = $(e.target).closest(".wcpt-block-editor-row"),
        $sibling_rows = $row.siblings(".wcpt-block-editor-row"),
        row = $row.data("wcpt-data"),
        row_index = $row.index(),
        parent = WCPT_Block_Editor.Ctrl.get_parent(this);

      // row is not editable
      if (!parent.config.delete_row) {
        return;
      }

      if (!$sibling_rows.length) {
        parent.model.reset_row(row_index);
      } else {
        parent.model.remove_row(row_index);
      }
    }),
    (ctrl.sort_update = function (e) {
      // for row or elements
      var $editor = $(e.target).closest(".wcpt-block-editor"),
        new_data = [],
        model = WCPT_Block_Editor.Ctrl.get_parent($editor).model;

      // iterate rows
      $editor.children(".wcpt-block-editor-row").each(function () {
        var $row = $(this),
          id = $row.attr("data-id"),
          new_row = model.get_row(id);

        if (!new_row) {
          return;
        }

        var colCount = parseInt(new_row.column_count || 1, 10);
        var $columnsRoot = $row.children(".wcpt-block-editor-columns");

        if ($columnsRoot.length) {
          if (!new_row.columns) {
            new_row.columns = {};
          }
          new_row.columns.elements = { 1: [], 2: [] };

          [1, 2].forEach(function (colIndex) {
            var $col = $columnsRoot.find(
              '.wcpt-block-editor-column[data-column="' + colIndex + '"]'
            );
            if (!$col.length) return;

            $col.children(".wcpt-block-editor-column-row").each(function () {
              var $colRow = $(this);
              var rowEls = [];

              $colRow.children(".wcpt-element-block").each(function () {
                var $_element = $(this),
                  element = $_element.data("wcpt-data");

                if (element) {
                  rowEls.push($.extend(true, {}, element));
                }
              });

              new_row.columns.elements[colIndex].push(rowEls);
            });
          });

          ctrl.sync_row_elements_from_column_one(new_row);
        } else {
          new_row.elements = [];
          $row.children(".wcpt-element-block").each(function () {
            var $_element = $(this),
              element = $_element.data("wcpt-data");

            if (element) {
              new_row.elements.push($.extend({}, element));
            }
          });

          if (parseInt(new_row.column_count || 1, 10) === 1) {
            if (!new_row.columns) {
              new_row.columns = {};
            }
            new_row.columns.elements = new_row.columns.elements || {};
            new_row.columns.elements[1] = [
              new_row.elements.map(function (el) {
                return el;
              }),
            ];
            new_row.columns.elements[2] = [[]];
          }
        }

        new_data.push(new_row);
      });

      model.set_data(new_data);
    });

  (ctrl.edit_element = function (e) {
    var $this = $(this),
      $row = $(e.target).closest(".wcpt-block-editor-row"),
      row_index = $row.index(),
      $element = $(e.target).closest(".wcpt-element-block"),
      element = $element.data("wcpt-data"),
      elm_index = $element.length
        ? $element.index()
        : $row.find(".wcpt-element-block").length,
      $colRow = $element.closest(".wcpt-block-editor-column-row"),
      column = parseInt($colRow.attr("data-column"), 10),
      column_row = parseInt($colRow.attr("data-column-row"), 10),
      col_elm_index = $colRow.length
        ? $colRow.children(".wcpt-element-block").index($element)
        : -1,
      parent = WCPT_Block_Editor.Ctrl.get_parent(this),
      $lightbox = parent.view.lightbox({
        partial: element.type,
        attr: {
          "data-row-index": row_index,
          "data-elm-index": elm_index,
          "data-partial": element.type,
          "wcpt-initial-data": "element_" + element.type,
          "data-column": isNaN(column) ? "" : column,
          "data-column-row": isNaN(column_row) ? "" : column_row,
          "data-col-elm-index": isNaN(col_elm_index) ? "" : col_elm_index,
        },
        $element: $element,
        duplicate_remove: true,
      });

    // transfer data to block from lightbox
    dominator_ui.init($lightbox, $.extend(true, {}, element));

    $lightbox.on("change", function () {
      var updatedElm = $.extend(true, {}, $lightbox.data("wcpt-data")),
        model = parent.model,
        data = model.get_data(),
        row = data[row_index],
        colCount = row ? parseInt(row.column_count || 1, 10) : 1,
        column = parseInt($lightbox.attr("data-column"), 10),
        column_row = parseInt($lightbox.attr("data-column-row"), 10),
        col_elm_index = parseInt($lightbox.attr("data-col-elm-index"), 10);

      if (
        row &&
        ctrl.row_uses_nested_columns_in_editor(row) &&
        !isNaN(column) &&
        !isNaN(column_row) &&
        !isNaN(col_elm_index)
      ) {
        // update nested column structure; ensure it exists and includes any legacy elements
        row.columns = row.columns || {};
        row.columns.elements = row.columns.elements || {};

        if (
          !row.columns.elements[1] &&
          Array.isArray(row.elements) &&
          row.elements.length
        ) {
          row.columns.elements[1] = [row.elements.map(function (el) { return el; })];
        }

        if (!row.columns.elements[2]) {
          row.columns.elements[2] = [];
        }

        if (!row.columns.elements[column]) {
          row.columns.elements[column] = [];
        }
        while (row.columns.elements[column].length <= column_row) {
          row.columns.elements[column].push([]);
        }
        row.columns.elements[column][column_row][col_elm_index] = updatedElm;

        ctrl.sync_row_elements_from_column_one(row);
        model.set_data(data);
      } else {
        // legacy single-column behaviour
        parent.model.update_element(updatedElm, row_index, elm_index);
        parent.view.mark_elm(row_index, elm_index);
      }
    });

    // auto focus on Text, HTML element input
    $lightbox.find('[wcpt-model-key="text"], [wcpt-model-key="html"]').focus();

    var $tray = $(
      "> .wcpt-block-editor-lightbox-content > .wcpt-block-editor-lightbox-tray",
      $lightbox
    );
    // remove
    $("> .wcpt-block-editor-lightbox-remove", $tray).on("click", function () {
      $lightbox.trigger("destroy");

      var model = parent.model,
        data = model.get_data(),
        row = data[row_index],
        colCount = row ? parseInt(row.column_count || 1, 10) : 1,
        column = parseInt($lightbox.attr("data-column"), 10),
        column_row = parseInt($lightbox.attr("data-column-row"), 10),
        col_elm_index = parseInt($lightbox.attr("data-col-elm-index"), 10);

      if (
        row &&
        ctrl.row_uses_nested_columns_in_editor(row) &&
        !isNaN(column) &&
        !isNaN(column_row) &&
        !isNaN(col_elm_index) &&
        row.columns &&
        row.columns.elements &&
        row.columns.elements[column] &&
        row.columns.elements[column][column_row]
      ) {
        row.columns.elements[column][column_row].splice(col_elm_index, 1);
        ctrl.sync_row_elements_from_column_one(row);
        model.set_data(data);
      } else {
        // legacy single-column behaviour
        parent.model.remove_element(row_index, elm_index);
      }
    });

    // duplicate
    $("> .wcpt-block-editor-lightbox-duplicate", $tray).on(
      "click",
      function () {
        var model = parent.model,
          data = model.get_data(),
          row = data[row_index],
          colCount = row ? parseInt(row.column_count || 1, 10) : 1,
          column = parseInt($lightbox.attr("data-column"), 10),
          column_row = parseInt($lightbox.attr("data-column-row"), 10),
          col_elm_index = parseInt($lightbox.attr("data-col-elm-index"), 10);

        if (
          row &&
          ctrl.row_uses_nested_columns_in_editor(row) &&
          !isNaN(column) &&
          !isNaN(column_row) &&
          !isNaN(col_elm_index) &&
          row.columns &&
          row.columns.elements &&
          row.columns.elements[column] &&
          row.columns.elements[column][column_row]
        ) {
          var original = row.columns.elements[column][column_row][col_elm_index];
          var clone = $.extend(true, {}, original);
          clone.id = Date.now();
          row.columns.elements[column][column_row].splice(col_elm_index + 1, 0, clone);
          ctrl.sync_row_elements_from_column_one(row);
          model.set_data(data);
        } else {
          // legacy single-column behaviour
          parent.model.duplicate_element(row_index, elm_index);
        }

        $lightbox.trigger("destroy");
      }
    );
  }),
    (ctrl.add_element = function (e) {
      e.preventDefault();

      var $addButton = $(e.target).closest(".wcpt-block-editor-add-element"),
        $row = $addButton.closest(".wcpt-block-editor-row"),
        row_index = $row.index(),
        $element = $(e.target).closest(".wcpt-element-block"),
        elm_index = $element.length
          ? $element.index()
          : $row.children(".wcpt-element-block").length,
        column = parseInt($addButton.attr("data-column"), 10),
        column_row = parseInt($addButton.attr("data-column-row"), 10),
        parent = WCPT_Block_Editor.Ctrl.get_parent(this),
        $lightbox = parent.view.lightbox({
          partial: parent.config.add_element_partial,
          attr: {
            "data-row-index": row_index,
            "data-elm-index": elm_index,
            "data-column": isNaN(column) ? "" : column,
            "data-column-row": isNaN(column_row) ? "" : column_row,
          },
          duplicate_remove: false,
        });

      // add element
      $lightbox.on(
        "click",
        ".wcpt-block-editor-element-type:not(.wcpt-disabled)",
        function (e2) {
          var $target = $(e2.target),
            type = $target.attr("data-elm"),
            row_index = parseInt($lightbox.attr("data-row-index"), 10),
            elm_index = parseInt($lightbox.attr("data-elm-index"), 10),
            column = parseInt($lightbox.attr("data-column"), 10),
            column_row = parseInt($lightbox.attr("data-column-row"), 10),
            model = parent.model,
            data = model.get_data(),
            row = data[row_index];

          var element = {
            id: Date.now(),
            type: type,
            style: {},
          };

          var colCount = row ? parseInt(row.column_count || 1, 10) : 1;

          if (
            row &&
            ctrl.row_uses_nested_columns_in_editor(row) &&
            !isNaN(column) &&
            !isNaN(column_row)
          ) {
            row.columns = row.columns || {};
            row.columns.elements = row.columns.elements || {};

            if (
              !row.columns.elements[1] &&
              Array.isArray(row.elements) &&
              row.elements.length
            ) {
              row.columns.elements[1] = [row.elements.map(function (el) { return el; })];
            }

            if (!row.columns.elements[2]) {
              row.columns.elements[2] = [];
            }

            if (!row.columns.elements[column]) {
              row.columns.elements[column] = [];
            }
            while (row.columns.elements[column].length <= column_row) {
              row.columns.elements[column].push([]);
            }
            row.columns.elements[column][column_row].push(element);

            ctrl.sync_row_elements_from_column_one(row);
            model.set_data(data);
          } else {
            // legacy single-column behaviour
            model.add_element(
              element,
              row_index,
              isNaN(elm_index) ? undefined : elm_index
            );
          }

          // close this lightbox
          $lightbox.trigger("destroy");

          // auto-open settings of the new element
          var $editorRows = parent.$elm.children(".wcpt-block-editor-row"),
            colCount = row ? parseInt(row.column_count || 1, 10) : 1;

          if (
            row &&
            ctrl.row_uses_nested_columns_in_editor(row) &&
            !isNaN(column) &&
            !isNaN(column_row)
          ) {
            var $targetColRow = $editorRows
              .eq(row_index)
              .find(
                '.wcpt-block-editor-column-row[data-column="' +
                  column +
                  '"][data-column-row="' +
                  column_row +
                  '"]'
              );

            $targetColRow.find(".wcpt-element-block").last().click();
          } else {
            // legacy single-column mode: open the newly inserted element
            $editorRows
              .eq(row_index)
              .children(".wcpt-element-block")
              .eq(elm_index)
              .click();
          }
        }
      );
    });

  ctrl.add_column_row = function (e) {
    e.preventDefault();

    var $button = $(e.target).closest(".wcpt-block-editor-add-column-row"),
      $row = $button.closest(".wcpt-block-editor-row"),
      row_index = $row.index(),
      column = parseInt($button.attr("data-column"), 10) || 1,
      parent = WCPT_Block_Editor.Ctrl.get_parent(this),
      model = parent.model,
      data = model.get_data(),
      row = data[row_index];

    if (!row) {
      return;
    }

    if (!ctrl.row_uses_nested_columns_in_editor(row)) {
      return;
    }

    row.columns = row.columns || {};
    row.columns.elements = row.columns.elements || { 1: [], 2: [] };
    if (!row.columns.elements[column]) {
      row.columns.elements[column] = [];
    }

    row.columns.elements[column].push([]);
    ctrl.sync_row_elements_from_column_one(row);
    model.set_data(data);
  };

  ctrl.delete_column_row = function (e) {
    e.preventDefault();

    var $button = $(e.target).closest(".wcpt-block-editor-delete-column-row"),
      $row = $button.closest(".wcpt-block-editor-row"),
      $colRow = $button.closest(".wcpt-block-editor-column-row"),
      row_index = $row.index(),
      column = parseInt($colRow.attr("data-column"), 10) || 1,
      column_row = parseInt($colRow.attr("data-column-row"), 10) || 0,
      parent = WCPT_Block_Editor.Ctrl.get_parent(this),
      model = parent.model,
      data = model.get_data(),
      row = data[row_index];

    if (!row) {
      return;
    }

    if (!ctrl.row_uses_nested_columns_in_editor(row)) {
      return;
    }

    row.columns = row.columns || {};
    row.columns.elements = row.columns.elements || { 1: [], 2: [] };
    if (!row.columns.elements[column]) {
      return;
    }

    row.columns.elements[column].splice(column_row, 1);

    if (row.columns.elements[column].length === 0) {
      row.columns.elements[column].push([]);
    }

    ctrl.sync_row_elements_from_column_one(row);
    model.set_data(data);
  };
})(jQuery, WCPT_Block_Editor.Ctrl);
