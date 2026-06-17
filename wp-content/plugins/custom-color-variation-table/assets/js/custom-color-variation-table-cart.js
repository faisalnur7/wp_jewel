( function( $ ) {
    function sanitizeQty( value ) {
        var qty = parseFloat( value );
        if ( isNaN( qty ) || qty < 0 ) {
            return 0;
        }
        return qty;
    }

    function clamp( value, min, max ) {
        if ( value < min ) {
            return min;
        }

        if ( max > 0 && value > max ) {
            return max;
        }

        return value;
    }

    function getStep( $input ) {
        var step = parseFloat( $input.attr( 'step' ) );

        if ( isNaN( step ) || step < 1 ) {
            step = parseFloat( $input.data( 'step' ) );
        }

        if ( isNaN( step ) || step < 1 ) {
            step = 1;
        }

        return step;
    }

    function getQuantityInput( $button ) {
        var $input = $button.closest( '.quantity, .product-quantity, td.product-quantity, .ccvt-qty-control, .ccvt-variation-quantity' ).find( 'input.qty, input[type="number"], .ccvt-variation-qty' ).first();

        if ( ! $input.length ) {
            $input = $button.siblings( 'input.qty, input[type="number"], .ccvt-variation-qty' ).first();
        }

        return $input;
    }

    function adjustQuantity( $input, direction ) {
        var minQty = parseFloat( $input.attr( 'min' ) );
        var maxQty = parseFloat( $input.attr( 'max' ) );
        var step = getStep( $input );
        var qty = sanitizeQty( $input.val() );

        if ( isNaN( minQty ) ) {
            minQty = 0;
        }

        if ( isNaN( maxQty ) ) {
            maxQty = 0;
        }

        qty = qty + ( direction * step );
        qty = clamp( qty, minQty, maxQty );

        $input.val( qty ).trigger( 'change' );
    }

    document.addEventListener(
        'click',
        function( event ) {
            var $button = $( event.target ).closest( '.woocommerce-cart-form .plus, .woocommerce-cart-form .minus, .woocommerce-cart-form .qty-plus, .woocommerce-cart-form .qty-minus, .woocommerce-cart-form .ccvt-qty-increment, .woocommerce-cart-form .ccvt-qty-decrement' );
            var button = $button.get( 0 );

            if ( ! button ) {
                return;
            }

            $button = $( button );
            var $form = $button.closest( '.woocommerce-cart-form' );
            if ( ! $form.length ) {
                return;
            }

            var $input = getQuantityInput( $button );
            if ( ! $input.length ) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            if ( $button.is( '.minus, .qty-minus, .ccvt-qty-decrement' ) ) {
                adjustQuantity( $input, -1 );
            } else {
                adjustQuantity( $input, 1 );
            }
        },
        true
    );
} )( jQuery );
