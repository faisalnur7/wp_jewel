( function( $ ) {
    function clamp( value, min, max ) {
        if ( value < min ) {
            return min;
        }

        if ( max > 0 && value > max ) {
            return max;
        }

        return value;
    }

    function sanitizeQty( value ) {
        var qty = parseInt( value, 10 );
        if ( isNaN( qty ) || qty < 0 ) {
            return 0;
        }
        return qty;
    }

    function showNotice( $container, message, type ) {
        if ( ! message || ! $container.length ) {
            return;
        }
        type = type || 'notice';
        $container.html( '<div class="ccvt-notice ccvt-notice-' + type + '">' + message + '</div>' );
    }

    function clearNotice( $container ) {
        if ( ! $container.length ) {
            return;
        }
        $container.empty();
    }

    function validateQuantityInput( $input ) {
        var qty = sanitizeQty( $input.val() );
        var minQty = parseInt( $input.attr( 'min' ), 10 );
        var step = parseInt( $input.data( 'step' ), 10 );
        var maxQty = parseInt( $input.data( 'max-qty' ), 10 );
        var stockQty = parseInt( $input.data( 'stock-qty' ), 10 );

        if ( isNaN( minQty ) ) {
            minQty = 0;
        }

        if ( isNaN( step ) || step < 1 ) {
            step = 1;
        }

        qty = clamp( qty, minQty, maxQty );

        if ( qty > 0 && step > 1 ) {
            qty = Math.max( minQty, Math.floor( qty / step ) * step );
        }

        if ( maxQty > 0 && qty > maxQty ) {
            qty = maxQty;
            showNotice( $input.closest( 'form' ).find( '.ccvt-notices' ), 'Quantity reduced to available purchase maximum.', 'error' );
        }

        if ( stockQty > 0 && qty > stockQty ) {
            qty = stockQty;
            showNotice( $input.closest( 'form' ).find( '.ccvt-notices' ), 'Quantity reduced to available stock.', 'error' );
        }

        $input.val( qty );
        return qty;
    }

    function updateButtonCount( $form ) {
        var total = 0;
        var variationTotal = 0;
        var groupedTotal = 0;
        var groupedPriceTotal = 0;

        $form.find( '.ccvt-variation-qty, input[name^="quantity["]' ).each( function() {
            var qty = sanitizeQty( $( this ).val() );
            total += qty;

            if ( $( this ).hasClass( 'ccvt-variation-qty' ) ) {
                variationTotal += qty;
            } else {
                groupedTotal += qty;
                var unitPrice = parseFloat( $( this ).data( 'price' ) );
                if ( ! isNaN( unitPrice ) ) {
                    groupedPriceTotal += unitPrice * qty;
                }
            }
        } );

        $form.find( '.ccvt-total-items' ).text( total );
        $form.find( '.ccvt-button-count' ).text( '(' + total + ')' );
        if ( $form.find( '.ccvt-total-price' ).length ) {
            var currencySymbol = $form.find( '.ccvt-total-price' ).data( 'currency-symbol' ) || '$';
            $form.find( '.ccvt-total-price' ).text( currencySymbol + groupedPriceTotal.toFixed( 2 ) );
        }

        $form.find( '.ccvt-add-to-cart' ).each( function() {
            var $button = $( this );
            $button.prop( 'disabled', total === 0 );
        } );

        // Keep the regular button state in sync for native grouped product inputs.
        if ( $form.hasClass( 'grouped_form' ) ) {
            $form.find( '.single_add_to_cart_button' ).prop( 'disabled', groupedTotal === 0 );
        } else if ( $form.hasClass( 'ccvt-form' ) ) {
            $form.find( '.single_add_to_cart_button' ).prop( 'disabled', total === 0 );
        } else {
            $form.find( '.single_add_to_cart_button' ).prop( 'disabled', total === 0 && variationTotal === 0 && groupedTotal === 0 );
        }

        $form.find( '.single_add_to_cart_button' ).each( function() {
            var $button = $( this );
            var hasVariationQty = $form.find( '.ccvt-variation-qty' ).toArray()
                .some( function( el ) {
                    return sanitizeQty( $( el ).val() ) > 0;
                } );

            if ( $form.find( '.ccvt-grouped-variation-table' ).length ) {
                $button.prop( 'disabled', ! hasVariationQty );
            } else if ( $form.hasClass( 'ccvt-form' ) ) {
                $button.prop( 'disabled', total === 0 );
            } else {
                var hasRegularQty = $form.find( 'input[name^="quantity["]' ).toArray()
                    .some( function( el ) {
                        return sanitizeQty( $( el ).val() ) > 0;
                    } );
                $button.prop( 'disabled', ! hasRegularQty );
            }
        } );
    }

    function collectVariationQuantities( $form ) {
        var quantities = {};
        var total = 0;

        $form.find( '.ccvt-variation-qty' ).each( function() {
            var $input = $( this );
            var qty = sanitizeQty( $input.val() );
            var variationId = $input.data( 'variation-id' );

            if ( qty > 0 && variationId ) {
                quantities[ variationId ] = qty;
                total += qty;
            }
        } );

        return {
            quantities: quantities,
            total: total,
        };
    }

    function collectGroupedQuantities( $form ) {
        var quantities = {};

        $form.find( 'input[name^="quantity["]' ).each( function() {
            var $input = $( this );
            var name = $input.attr( 'name' );
            var value = sanitizeQty( $input.val() );
            var match = name.match( /^quantity\[(\d+)\]$/ );

            if ( match && value > 0 ) {
                quantities[ match[1] ] = value;
            }
        } );

        return quantities;
    }

    function postAjax( data, $form, $button ) {
        var $notices = $form.find( '.ccvt-notices' );
        if ( $button && $button.length ) {
            $button.prop( 'disabled', true ).addClass( 'ccvt-loading' );
        }

        $.ajax( {
            url: ccvt_params.ajax_url,
            method: 'POST',
            dataType: 'json',
            data: data,
        } ).done( function( response ) {
            if ( response.success ) {
                showNotice( $notices, response.data.message || ccvt_params.strings.added_to_cart, 'success' );
                $form.find( '.ccvt-variation-qty' ).val( 0 );
                updateButtonCount( $form );

                if ( response.data.fragments ) {
                    $.each( response.data.fragments, function( selector, html ) {
                        $( selector ).replaceWith( html );
                    } );
                }

                $( document.body ).trigger( 'wc_fragment_refresh' );
            } else {
                showNotice( $notices, response.data.message || ccvt_params.strings.ajax_error, 'error' );
            }
        } ).fail( function() {
            showNotice( $notices, ccvt_params.strings.ajax_error, 'error' );
        } ).always( function() {
            if ( $button && $button.length ) {
                $button.prop( 'disabled', false ).removeClass( 'ccvt-loading' );
            }
            updateButtonCount( $form );
        } );
    }

    $( document ).ready( function() {
        $( document ).on( 'click', '.ccvt-qty-decrement', function( e ) {
            e.preventDefault();
            var $input = $( this ).siblings( '.ccvt-variation-qty, .ccvt-grouped-qty-input' );
            var minQty = parseInt( $input.attr( 'min' ), 10 );
            var step = parseInt( $input.data( 'step' ), 10 );

            if ( isNaN( minQty ) ) {
                minQty = 0;
            }

            if ( isNaN( step ) || step < 1 ) {
                step = 1;
            }

            var qty = sanitizeQty( $input.val() );
            $input.val( Math.max( minQty, qty - step ) ).trigger( 'change' );
        } );

        $( document ).on( 'click', '.ccvt-qty-increment', function( e ) {
            e.preventDefault();
            var $input = $( this ).siblings( '.ccvt-variation-qty, .ccvt-grouped-qty-input' );
            var minQty = parseInt( $input.attr( 'min' ), 10 );
            var step = parseInt( $input.data( 'step' ), 10 );
            var maxQty = parseInt( $input.data( 'max-qty' ), 10 );

            if ( isNaN( minQty ) ) {
                minQty = 0;
            }

            if ( isNaN( step ) || step < 1 ) {
                step = 1;
            }

            if ( isNaN( maxQty ) ) {
                maxQty = 0;
            }

            var qty = sanitizeQty( $input.val() );
            $input.val( clamp( qty + step, minQty, maxQty ) ).trigger( 'change' );
        } );

        $( document ).on( 'input change', '.ccvt-variation-qty', function() {
            var $input = $( this );
            var $form = $input.closest( 'form' );
            var qty = validateQuantityInput( $input );
            updateButtonCount( $form );

            if ( qty > 0 && $input.data( 'variation-id' ) ) {
                var varId = $input.data( 'variation-id' );
                var $select = $form.find( 'select[name="variation_id"]' );
                if ( $select.length ) {
                    $select.val( varId );
                }
            }
        } );

        $( document ).on( 'input change', '.ccvt-grouped-qty-input', function() {
            var $input = $( this );
            var $form = $input.closest( 'form' );
            validateQuantityInput( $input );
            updateButtonCount( $form );
        } );

        $( document ).on( 'submit', '.ccvt-form, .grouped_form', function( e ) {
            var $form = $( this );
            var $notices = $form.find( '.ccvt-notices' );
            var $button = $form.find( '.ccvt-add-to-cart, .single_add_to_cart_button' ).first();
            var variation = collectVariationQuantities( $form );
            var groupedQuantities = collectGroupedQuantities( $form );

            var total = variation.total;
            var hasRegularQuantities = Object.keys( groupedQuantities ).length > 0;

            if ( variation.total === 0 && ! hasRegularQuantities ) {
                e.preventDefault();
                clearNotice( $notices );
                showNotice( $notices, ccvt_params.strings.no_items, 'error' );
                return;
            }

            e.preventDefault();
            clearNotice( $notices );

            var data = {
                action: 'ccvt_add_to_cart',
                security: ccvt_params.nonce,
                product_id: $form.find( 'input[name="product_id"], input[name="add-to-cart"]' ).val(),
                quantities: variation.quantities,
            };

            if ( hasRegularQuantities ) {
                data.grouped_child_quantities = groupedQuantities;
            }

            postAjax( data, $form, $button );
        } );

        $( '.ccvt-variation-table' ).closest( 'form' ).each( function() {
            updateButtonCount( $( this ) );
        } );
    } );
} )( jQuery );
