# CCVT Implementation Plan

1. Keep the current CCVT variation table intact.
   - Preserve the quantity controls, add-to-cart flow, and existing WooCommerce variation data payload.

2. Sync right-side variation clicks to the left gallery.
   - On row click, resolve the variation by variation ID.
   - Update the main WooCommerce product image to the clicked variation image.

3. Replace the gallery thumbnails with variation images.
   - Build a horizontal thumbnail strip from the unique variation images already present in `data-product_variations`.
   - Make the strip clickable and keep it in sync with the selected row.

4. Keep the interaction safe.
   - Ignore clicks on `+`, `-`, and quantity inputs so those controls keep working normally.
   - Fall back gracefully when a variation has no image.

5. Verify locally.
   - Lint the PHP and JS changes.
   - Confirm the row click path and gallery sync logic are wired through the same variation data.
