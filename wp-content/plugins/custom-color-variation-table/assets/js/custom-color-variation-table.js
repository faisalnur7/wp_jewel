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

    function getProductVariations( $form ) {
        var variations = $form.data( 'product_variations' );

        if ( typeof variations === 'string' ) {
            try {
                variations = JSON.parse( variations );
            } catch ( e ) {
                variations = [];
            }
        }

        if ( ! Array.isArray( variations ) ) {
            variations = [];
        }

        return variations;
    }

    function getQuantityTiers( $form ) {
        var tiers = $form.attr( 'data-ccvt-quantity-tiers' ) || '[]';

        try {
            tiers = JSON.parse( tiers );
        } catch ( e ) {
            tiers = [];
        }

        return Array.isArray( tiers ) ? tiers : [];
    }

    function getTierPrice( $form, quantity, basePrice ) {
        var tiers = getQuantityTiers( $form );
        var qty = Math.max( 1, sanitizeQty( quantity ) );
        var price = null;

        tiers.some( function( tier ) {
            var min = parseInt( tier.min, 10 );
            var max = tier.max === null || tier.max === '' ? null : parseInt( tier.max, 10 );

            if ( ! isNaN( min ) && qty >= min && ( max === null || ( ! isNaN( max ) && qty <= max ) ) ) {
                price = parseFloat( tier.price );
                return true;
            }

            return false;
        } );

        return price === null || isNaN( price ) ? basePrice : price;
    }

    function formatPrice( price ) {
        var settings = ccvt_params.price || {};
        var decimals = parseInt( settings.decimals, 10 );
        var value;

        if ( isNaN( decimals ) ) {
            decimals = 2;
        }

        value = Number( price ).toFixed( decimals );
        var parts = value.split( '.' );
        parts[ 0 ] = parts[ 0 ].replace( /\B(?=(\d{3})+(?!\d))/g, settings.thousand || ',' );
        value = parts.join( settings.decimal || '.' );

        if ( settings.position === 'right' ) {
            return value + ( settings.symbol || '' );
        }

        if ( settings.position === 'left_space' ) {
            return ( settings.symbol || '' ) + ' ' + value;
        }

        if ( settings.position === 'right_space' ) {
            return value + ' ' + ( settings.symbol || '' );
        }

        return ( settings.symbol || '' ) + value;
    }

    function updateVariationPrice( $input, quantity ) {
        var $form = $input.closest( 'form' );
        var tiers = getQuantityTiers( $form );
        if ( ! tiers.length ) {
            return;
        }

        var basePrice = parseFloat( $input.data( 'base-price' ) );

        if ( isNaN( basePrice ) ) {
            basePrice = parseFloat( $input.data( 'price' ) );
            $input.data( 'base-price', basePrice );
        }

        var price = getTierPrice( $form, quantity, basePrice );
        $input.data( 'price', price );
        $input.closest( '.ccvt-variation-row' ).find( '.ccvt-variation-price-value' ).text( formatPrice( price ) );

        $form.find( '.ccvt-price-tier' ).removeClass( 'ccvt-price-tier-active' );
        var qty = Math.max( 1, sanitizeQty( quantity ) );
        tiers.some( function( tier, index ) {
            var min = parseInt( tier.min, 10 );
            var max = tier.max === null || tier.max === '' ? null : parseInt( tier.max, 10 );

            if ( ! isNaN( min ) && qty >= min && ( max === null || ( ! isNaN( max ) && qty <= max ) ) ) {
                $form.find( '.ccvt-price-tier' ).eq( index ).addClass( 'ccvt-price-tier-active' );
                return true;
            }

            return false;
        } );
    }

    function getVariationImageData( variation ) {
        if ( ! variation || ! variation.image || ! variation.image.src ) {
            return null;
        }

        return {
            variationId: variation.variation_id || variation.id || '',
            imageId: variation.image.id || variation.image_id || '',
            src: variation.image.src,
            fullSrc: variation.image.full_src || variation.image.src,
            thumbSrc: variation.image.gallery_thumbnail_src || variation.image.thumb_src || variation.image.src,
            galleryThumbnailSrc: variation.image.gallery_thumbnail_src || variation.image.src,
            alt: variation.image.alt || '',
            title: variation.image.title || '',
            caption: variation.image.caption || '',
            srcset: variation.image.srcset || '',
            sizes: variation.image.sizes || '',
            srcW: variation.image.src_w || '',
            srcH: variation.image.src_h || '',
            fullSrcW: variation.image.full_src_w || variation.image.src_w || '',
            fullSrcH: variation.image.full_src_h || variation.image.src_h || ''
        };
    }

    function getUniqueVariationImages( $form ) {
        var variations = getProductVariations( $form );
        var seen = {};
        var images = [];

        variations.forEach( function( variation ) {
            var image = getVariationImageData( variation );
            var key = image ? ( image.imageId || image.galleryThumbnailSrc || image.fullSrc || image.src ) : '';

            if ( ! image || ! key || seen[ key ] ) {
                return;
            }

            seen[ key ] = true;
            images.push( image );
        } );

        return images;
    }

    function getProductGallery( $form ) {
        return $form.closest( '.product' ).find( '.woocommerce-product-gallery' ).first();
    }

    function getGalleryImageKey( image ) {
        if ( ! image ) {
            return '';
        }

        return image.attr( 'data-large_image' ) || image.attr( 'data-src' ) || image.attr( 'src' ) || '';
    }

    function getGalleryImageWrap( $gallery ) {
        return $gallery.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' ).first();
    }

    function getGalleryImage( $gallery ) {
        return getGalleryImageWrap( $gallery ).find( 'img.wp-post-image, img' ).first();
    }

    function getGalleryLink( $gallery ) {
        return getGalleryImageWrap( $gallery ).find( 'a' ).first();
    }

    function applyGalleryImageData( $gallery, image ) {
        var $productImage = getGalleryImage( $gallery );
        var $productWrap = getGalleryImageWrap( $gallery );
        var $productLink = getGalleryLink( $gallery );

        if ( ! $productImage.length || ! image ) {
            return;
        }

        $productImage.attr( 'src', image.src );
        $productImage.attr( 'data-src', image.fullSrc );
        $productImage.attr( 'data-large_image', image.fullSrc );
        if ( image.fullSrcW ) {
            $productImage.attr( 'data-large_image_width', image.fullSrcW );
        } else {
            $productImage.removeAttr( 'data-large_image_width' );
        }

        if ( image.fullSrcH ) {
            $productImage.attr( 'data-large_image_height', image.fullSrcH );
        } else {
            $productImage.removeAttr( 'data-large_image_height' );
        }

        if ( image.srcset ) {
            $productImage.attr( 'srcset', image.srcset );
        } else {
            $productImage.removeAttr( 'srcset' );
        }

        if ( image.sizes ) {
            $productImage.attr( 'sizes', image.sizes );
        } else {
            $productImage.removeAttr( 'sizes' );
        }

        if ( image.title ) {
            $productImage.attr( 'title', image.title );
        } else {
            $productImage.removeAttr( 'title' );
        }

        if ( image.caption ) {
            $productImage.attr( 'data-caption', image.caption );
        } else {
            $productImage.removeAttr( 'data-caption' );
        }

        if ( image.alt ) {
            $productImage.attr( 'alt', image.alt );
        } else {
            $productImage.removeAttr( 'alt' );
        }

        if ( image.thumbSrc ) {
            $productWrap.attr( 'data-thumb', image.thumbSrc );
        } else {
            $productWrap.removeAttr( 'data-thumb' );
        }

        if ( $productLink.length ) {
            $productLink.attr( 'href', image.fullSrc );
        }
    }

    function getCurrentGalleryImageKey( $gallery ) {
        var $image = getGalleryImage( $gallery );
        return getGalleryImageKey( $image );
    }

    function syncGalleryThumbsToImage( $form, image, options ) {
        var $thumbs = $form.find( '.ccvt-gallery-thumbs' );
        var activeKey = image ? ( image.fullSrc || image.galleryThumbnailSrc || image.src ) : '';
        var activeThumbKey = image ? ( image.galleryThumbnailSrc || image.thumbSrc || image.src ) : '';
        var swiper = getGallerySwiperInstance( $form );

        $thumbs.find( '.ccvt-gallery-thumb' ).removeClass( 'is-active' );

        if ( activeKey ) {
            var $activeThumb = $thumbs.find( '.ccvt-gallery-thumb' ).filter( function() {
                var $thumb = $( this );
                var galleryKey = String( $thumb.data( 'gallery-key' ) || '' );
                var thumbKey = String( $thumb.data( 'thumb-key' ) || '' );

                return galleryKey === String( activeKey ) || thumbKey === String( activeThumbKey ) || galleryKey === String( activeThumbKey );
            } ).addClass( 'is-active' ).first();

            if ( swiper && $activeThumb.length ) {
                var activeIndex = parseInt( $activeThumb.data( 'index' ), 10 );
                if ( ! isNaN( activeIndex ) ) {
                    slideGallerySwiperToIndex( swiper, activeIndex );
                }
            }
        }
    }

    function getGallerySwiperViewport( $form ) {
        return getGalleryScope( $form ).find( '.ccvt-gallery-thumbs-viewport.swiper' ).first();
    }

    function getGallerySwiperTrack( $form ) {
        return getGalleryScope( $form ).find( '.ccvt-gallery-thumbs' ).first();
    }

    function getGallerySwiperInstance( $form ) {
        return getGalleryScope( $form ).data( 'ccvtGallerySwiper' ) || null;
    }

    function getGalleryScope( $form ) {
        return $form.closest( '.product' ).first();
    }

    function slideGallerySwiperToIndex( swiper, index ) {
        if ( ! swiper || isNaN( index ) ) {
            return;
        }

        if ( typeof swiper.slideToLoop === 'function' ) {
            swiper.slideToLoop( index, 0, false );
        } else {
            swiper.slideTo( index, 0, false );
        }
    }

    function destroyGallerySwiper( $form ) {
        var swiper = getGallerySwiperInstance( $form );
        var $scope = getGalleryScope( $form );

        if ( swiper && typeof swiper.destroy === 'function' ) {
            swiper.destroy( true, true );
        }

        $scope.removeData( 'ccvtGallerySwiper' );
    }

    function initGallerySwiper( $form ) {
        var $viewport = getGallerySwiperViewport( $form );
        var $scope = getGalleryScope( $form );
        var $prevButton = $scope.find( '.ccvt-gallery-button-prev' ).first();
        var $nextButton = $scope.find( '.ccvt-gallery-button-next' ).first();
        var SwiperConstructor = window.Swiper;
        var swiper;

        if ( ! $viewport.length || typeof SwiperConstructor !== 'function' ) {
            return null;
        }

        destroyGallerySwiper( $form );

        swiper = new SwiperConstructor( $viewport.get( 0 ), {
            slidesPerView: 2,
            slidesPerGroup: 1,
            spaceBetween: 8,
            loop: true,
            loopPreventsSliding: false,
            watchOverflow: false,
            speed: 400,
            centeredSlides: false,
            roundLengths: true,
            freeMode: {
                enabled: true,
                sticky: true,
            },
            observer: true,
            observeParents: true,
            breakpoints: {
                768: {
                    slidesPerView: 4,
                    spaceBetween: 12,
                },
            },
        } );

        $prevButton.off( 'click.ccvtSwiperNavigation' ).on( 'click.ccvtSwiperNavigation', function( e ) {
            e.preventDefault();
            if ( ! swiper.destroyed ) {
                swiper.slidePrev( 400 );
            }
        } );

        $nextButton.off( 'click.ccvtSwiperNavigation' ).on( 'click.ccvtSwiperNavigation', function( e ) {
            e.preventDefault();
            if ( ! swiper.destroyed ) {
                swiper.slideNext( 400 );
            }
        } );

        $scope.data( 'ccvtGallerySwiper', swiper );

        return swiper;
    }

    function ensureActiveThumbVisible( $form ) {
        var swiper = getGallerySwiperInstance( $form );
        var $activeThumb = getGallerySwiperTrack( $form ).find( '.ccvt-gallery-thumb.is-active' ).first();
        var activeIndex = parseInt( $activeThumb.data( 'index' ), 10 );

        if ( swiper && ! isNaN( activeIndex ) ) {
            slideGallerySwiperToIndex( swiper, activeIndex );
        }
    }

    function selectVariationImage( $form, variation ) {
        var $gallery = getProductGallery( $form );
        var variationId = variation && variation.variation_id ? String( variation.variation_id ) : '';
        var image = getVariationImageData( variation );

        if ( ! $gallery.length || ! image ) {
            return;
        }

        if ( typeof $form.wc_variations_image_update === 'function' ) {
            $form.wc_variations_image_update( variation );
        } else {
            applyGalleryImageData( $gallery, image );

            window.setTimeout( function() {
                $( window ).trigger( 'resize' );
                $gallery.trigger( 'woocommerce_gallery_init_zoom' );
            }, 20 );
        }

        $form.find( '.ccvt-variation-row' ).removeClass( 'is-active' );
        $form.find( '.ccvt-variation-row' ).filter( function() {
            return String( $( this ).find( '.ccvt-variation-qty' ).data( 'variation-id' ) ) === variationId;
        } ).addClass( 'is-active' );

        syncGalleryThumbsToImage( $form, image );
        ensureActiveThumbVisible( $form );
    }

    function buildVariationGallery( $form ) {
        var $gallery = getProductGallery( $form );
        var $scope = getGalleryScope( $form );
        var images = getUniqueVariationImages( $form );
        var existingStrip = $scope.find( '.ccvt-gallery-thumbs' );
        var existingViewport = getGallerySwiperViewport( $form );
        var existingWrap = $scope.find( '.ccvt-gallery-thumbs-wrap' );
        var currentKey = getCurrentGalleryImageKey( $gallery );

        if ( ! $gallery.length || ! images.length ) {
            return;
        }

        images.sort( function( left, right ) {
            var leftKey = String( left.imageId || left.galleryThumbnailSrc || left.fullSrc || left.src || '' );
            var rightKey = String( right.imageId || right.galleryThumbnailSrc || right.fullSrc || right.src || '' );

            if ( currentKey && ( leftKey === currentKey || left.fullSrc === currentKey || left.src === currentKey ) ) {
                return -1;
            }

            if ( currentKey && ( rightKey === currentKey || right.fullSrc === currentKey || right.src === currentKey ) ) {
                return 1;
            }

            return 0;
        } );

        destroyGallerySwiper( $form );

        if ( ! existingWrap.length ) {
            existingWrap = $( '<div class="ccvt-gallery-thumbs-wrap"></div>' );
            existingViewport = $( '<div class="ccvt-gallery-thumbs-viewport swiper"></div>' );
            existingStrip = $( '<div class="ccvt-gallery-thumbs swiper-wrapper" aria-label="Variation images"></div>' );
            existingViewport.append( existingStrip );
            existingWrap.append(
                $( '<button type="button" class="ccvt-gallery-button ccvt-gallery-button-prev swiper-button-prev" aria-label="Previous variation image"></button>' ),
                existingViewport,
                $( '<button type="button" class="ccvt-gallery-button ccvt-gallery-button-next swiper-button-next" aria-label="Next variation image"></button>' )
            );
            $gallery.after( existingWrap );
        } else {
            existingStrip.empty();
            if ( ! existingWrap.parent().length ) {
                $gallery.after( existingWrap );
            }
            if ( ! existingViewport.length ) {
                existingViewport = existingWrap.find( '.ccvt-gallery-thumbs-viewport.swiper' ).first();
            }
            if ( ! existingStrip.length ) {
                existingStrip = existingWrap.find( '.ccvt-gallery-thumbs' ).first();
            }
            if ( ! existingViewport.length ) {
                existingViewport = $( '<div class="ccvt-gallery-thumbs-viewport swiper"></div>' );
                existingWrap.append( existingViewport );
            }
            if ( ! existingStrip.length ) {
                existingStrip = $( '<div class="ccvt-gallery-thumbs swiper-wrapper" aria-label="Variation images"></div>' );
                existingViewport.append( existingStrip );
            }
            if ( ! existingWrap.find( '.ccvt-gallery-button-prev' ).length ) {
                existingWrap.prepend( $( '<button type="button" class="ccvt-gallery-button ccvt-gallery-button-prev swiper-button-prev" aria-label="Previous variation image"></button>' ) );
            }
            if ( ! existingWrap.find( '.ccvt-gallery-button-next' ).length ) {
                existingWrap.append( $( '<button type="button" class="ccvt-gallery-button ccvt-gallery-button-next swiper-button-next" aria-label="Next variation image"></button>' ) );
            }
        }

        images.forEach( function( image ) {
            var thumb = $( '<button type="button" class="ccvt-gallery-thumb swiper-slide" />' );
            var thumbImage = $( '<img />' );
            var label = image.alt || image.title || image.caption || image.variationId || '';
            var thumbIndex = existingStrip.find( '.ccvt-gallery-thumb' ).length;

            thumb.attr( 'data-variation-id', image.variationId );
            thumb.attr( 'data-index', thumbIndex );
            thumb.attr( 'data-gallery-key', image.fullSrc || image.src );
            thumb.attr( 'data-thumb-key', image.galleryThumbnailSrc || image.thumbSrc || image.src );
            thumb.attr( 'aria-label', label ? label : 'Variation image' );

            thumbImage.attr( 'src', image.fullSrc || image.src || image.thumbSrc );
            if ( image.srcset ) {
                thumbImage.attr( 'srcset', image.srcset );
                thumbImage.attr( 'sizes', '(max-width: 767px) 145px, 170px' );
            }
            thumbImage.attr( 'alt', label );
            thumbImage.attr( 'loading', 'lazy' );
            thumbImage.attr( 'decoding', 'async' );

            thumb.append( thumbImage );
            existingStrip.append( thumb );
        } );

        existingStrip.off( 'click.ccvtGallery' ).on( 'click.ccvtGallery', '.ccvt-gallery-thumb', function( e ) {
            e.preventDefault();

            var $button = $( this );
            var variationId = String( $button.data( 'variation-id' ) );
            var variations = getProductVariations( $form );
            var matchedVariation = null;

            variations.some( function( variation ) {
                if ( String( variation.variation_id || variation.id || '' ) === variationId ) {
                    matchedVariation = variation;
                    return true;
                }

                return false;
            } );

            if ( matchedVariation ) {
                selectVariationImage( $form, matchedVariation );
            }
        } );

        initGallerySwiper( $form );
        syncGalleryThumbsToImage( $form, null );

        if ( currentKey ) {
            existingStrip.find( '.ccvt-gallery-thumb' ).filter( function() {
                var $thumb = $( this );
                var galleryKey = String( $thumb.data( 'gallery-key' ) || '' );
                var thumbKey = String( $thumb.data( 'thumb-key' ) || '' );

                return galleryKey === String( currentKey ) || thumbKey === String( currentKey );
            } ).addClass( 'is-active' );
        }

        ensureActiveThumbVisible( $form );
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
        var variationPriceTotal = 0;

        $form.find( '.ccvt-variation-qty, input[name^="quantity["]' ).each( function() {
            var qty = sanitizeQty( $( this ).val() );
            total += qty;

            if ( $( this ).hasClass( 'ccvt-variation-qty' ) ) {
                variationTotal += qty;
                var variationUnitPrice = parseFloat( $( this ).data( 'price' ) );
                if ( ! isNaN( variationUnitPrice ) ) {
                    variationPriceTotal += variationUnitPrice * qty;
                }
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

        if ( $form.hasClass( 'ccvt-form' ) && $form.find( '.ccvt-button-count' ).length ) {
            var variableCurrencySymbol = $form.find( '.ccvt-total-price' ).data( 'currency-symbol' ) || '$';
            $form.find( '.ccvt-button-count' ).text( '(' + total + ' items - ' + variableCurrencySymbol + variationPriceTotal.toFixed( 2 ) + ')' );
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
            updateVariationPrice( $input, qty );
            updateButtonCount( $form );

            if ( qty > 0 && $input.data( 'variation-id' ) ) {
                var varId = $input.data( 'variation-id' );
                var $select = $form.find( 'select[name="variation_id"]' );
                if ( $select.length ) {
                    $select.val( varId );
                }
            }
        } );

        $( document ).on( 'click', '.ccvt-variation-row', function( e ) {
            if ( $( e.target ).closest( 'button, input, select, textarea, a, label' ).length ) {
                return;
            }

            var $row = $( this );
            var $form = $row.closest( 'form' );
            var variationId = String( $row.find( '.ccvt-variation-qty' ).data( 'variation-id' ) || '' );

            if ( ! variationId ) {
                return;
            }

            var variations = getProductVariations( $form );
            var matchedVariation = null;

            variations.some( function( variation ) {
                if ( String( variation.variation_id || variation.id || '' ) === variationId ) {
                    matchedVariation = variation;
                    return true;
                }

                return false;
            } );

            if ( matchedVariation ) {
                selectVariationImage( $form, matchedVariation );
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

        $( '.ccvt-form' ).each( function() {
            var $form = $( this );
            buildVariationGallery( $form );
            $form.find( '.ccvt-variation-qty' ).each( function() {
                updateVariationPrice( $( this ), 0 );
            } );
            updateButtonCount( $form );
        } );

        $( window ).off( 'resize.ccvtGallery' ).on( 'resize.ccvtGallery' , function() {
            $( '.ccvt-form' ).each( function() {
                var swiper = getGallerySwiperInstance( $( this ) );

                if ( swiper && typeof swiper.update === 'function' ) {
                    swiper.update();
                }
            } );
        } );

        $( '.ccvt-variation-table' ).closest( 'form' ).each( function() {
            updateButtonCount( $( this ) );
        } );
    } );
} )( jQuery );
