<?php
namespace WQPMB\Admin\Adm_Inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Notice_Framework {
    public $framework;
    private $offer_prefix = 'wqpmb-offer-s122';

    public function __construct()
    {
        require_once WQPMB_BASE_DIR . '/framework/framework.php';
        $this->framework = \CA_Framework::init( 'wc-quantity-plus-minus-button', WQPMB_MAIN_FILE );
    }

    public function run()
    {
        
    }

    public function show_recommended_plugins(){
        $plugins = $this->framework->recommended_plugins( $this->get_recommended_plugins( true ), 'wqpmb-recommend-plugins' );
        $plugins->show_on_hook( 'wqpmb_plugin_recommend_here' );
        
    }

    public function show_all_premium_discount_offer(){
        $this->framework->create_offer( $this->get_offer_args(
            array(
                'id'            => $this->offer_prefix . '-inside-only-free',
                'pages'         => ['wqpmb'],
                'template'      => 'starter',
                'title'         => 'CodeAstrology PLUGINS',
                'description'   => 'Grab your exclusive discount now! for WooCommerce Addons. Claim your discount!',
                'reshow_after'  => 25,
                'image_url'     => '',
                'reshow_unit'   => 'hours',
                'dismiss'       => false,
                'randomize'     => 100,
                'buttons'       => array(
                    array(
                        'text'  => 'Get 50% OFF',
                        'url'   => 'https://codeastrology.com/products/',
                        'class' => 'ca-fw-btn-primary',
                        'icon'  => 'dashicons-cart',
                    ),
                    array(
                        'text'  => 'Get Free',
                        'url'   => 'https://profiles.wordpress.org/codersaiful/#content-plugins',
                        'class' => 'ca-fw-btn-primary',
                        'icon'  => 'dashicons-visibility',
                    ),
                    array(
                        'text'  => 'Live Demo',
                        'url'   => 'https://wpprincipal.xyz/',
                        'class' => 'ca-fw-btn-primary',
                        'icon'  => 'dashicons-visibility',
                    ),
                ),
            )
        ) )
        ->show()
        ->show_on_hook( 'wqpmb_plugin_recommend_here', 2 );
        
    }

    public function show_random_campaigns_offers(){
        
        $choice = rand( 1, 3 );

        // 1. Product Table Campaign
        if ( $choice === 1 ) {
            // offer outside plugin pages for product table plugin
            $this->framework->create_offer( $this->get_offer_args(
                array(
                    'id'            => $this->offer_prefix . '-wpt',
                    'title'         => 'WooProductTable - Product Table for WooCommerce',
                    'pages'         => array(),
                    'pages_exclude' => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'randomize'     => 100,
                    'highlight_text' => '',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/product-table-logo.png',
                    'target_plugin' => array(
                        'slug' => 'woo-product-table',
                        'path' => 'woo-product-table/woo-product-table.php',
                    ),
                    'description'   => '<p>🛒 Display your WooCommerce products in a clean, searchable table that helps customers find and order faster. Perfect for bulk orders and large catalogs. 🚀 Upgrade to Pro for more features and full control.</p>',
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=wpt',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )
            ->show();
            // offer inside plugin pages for product table plugin
            $this->framework->create_offer( $this->get_offer_args(
                array(
                    'id'            => $this->offer_prefix . 'wpt',
                    'title'         => 'WooProductTable - Product Table for WooCommerce',
                    // 'pages_exclude' => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'pages'         => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'template'      => 'flash',
                    'randomize'     => 5,
                    'target_plugin' => array(
                        'slug' => 'woo-product-table',
                        'path' => 'woo-product-table/woo-product-table.php',
                    ),
                    'highlight_text' => '',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/product-table-logo.png',
                    'description'   => '<p>🛒 Display your WooCommerce products in a clean, searchable table that helps customers find and order faster. Perfect for bulk orders and large catalogs. 🚀 Upgrade to Pro for more features and full control.</p>',
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=wpt',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )
            ->show();

            //  Popup inside for product table plugin 
            $this->framework->create_popup( $this->get_popup_args(
                array(
                    'id'            => $this->offer_prefix . 'wpt-popup',
                    'title'         => 'WooProductTable - Product Table for WooCommerce',
                    'description'   => '<p></p>
                            <ul>
                                <li>✅ Display products in a clean, smart table layout</li>
                                <li>✅ Help customers find & compare products instantly</li>
                                <li>✅ Fully responsive - works perfectly on all devices</li>
                                <li>✅ Increase sales with faster and easier shopping</li>
                            </ul>
                            <p>✅ Ideal for wholesale, bulk orders, and large product catalogs.</p>
                            <p>✅ <strong>Go Pro</strong> to unlock advanced features, more control, and priority support.</p>',
                    'reshow_after'  => 25,
                    'reshow_unit'   => 'hours',
                    'start_date'    => '2026-04-01',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/product-table-logo.png',
                    'randomize'     => 5,
                    'target_plugin' => array(
                        'slug' => 'woo-product-table',
                        'path' => 'woo-product-table/woo-product-table.php',
                    ),
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=wpt',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )->show();

        }

        // 2. Min/Max Quantity Campaign
        if ( $choice === 2 ) {
            // offer outside plugin pages for min max quantity plugin
            $this->framework->create_offer( $this->get_offer_args(
                array(
                    'id'            => $this->offer_prefix . 'wcmmq',
                    'title'         => 'Min Max Control - Min Max Quantity & Step Control',
                    'pages'         => array(),
                    'pages_exclude' => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'randomize'     => 5,
                    'highlight_text' => '',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/min-max-logo.png',
                    'target_plugin' => array(
                        'slug' => 'woo-min-max-quantity-step-control-single',
                        'path' => 'woo-min-max-quantity-step-control-single/wcmmq.php',
                    ),
                    'description'   => '<p>🛒 Set minimum and maximum quantity limits for your WooCommerce products with our easy-to-use plugin. Perfect for managing inventory and preventing over-ordering. 🚀 Upgrade to Pro for more features and full control.</p>',
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=wcmmq',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )
            ->show();
            // offer inside plugin pages for min max quantity plugin
            $this->framework->create_offer( $this->get_offer_args(
                array(
                    'id'            => $this->offer_prefix . 'wcmmq',
                    'title'         => 'Min Max Control - Min Max Quantity & Step Control',
                    'pages'         => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'template'      => 'flash',
                    'randomize'     => 5,
                    'highlight_text' => '',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/min-max-logo.png',
                    'target_plugin' => array(
                        'slug' => 'woo-min-max-quantity-step-control-single',
                        'path' => 'woo-min-max-quantity-step-control-single/wcmmq.php',
                    ),
                    'description'   => '<p>🛒 Set minimum and maximum quantity limits for your WooCommerce products with our easy-to-use plugin. Perfect for managing inventory and preventing over-ordering. 🚀 Upgrade to Pro for more features and full control.</p>',
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=wcmmq',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )
            ->show();

            // Popup inside for min max quantity plugin
            $this->framework->create_popup( $this->get_popup_args(
                array(
                    'id'            => $this->offer_prefix . 'wcmmq-popup',
                    'title'         => 'Min Max Control - Min Max Quantity & Step Control',
                    'description'   => '<p></p>
                            <ul>
                                <li>✅ Set minimum & maximum quantity limits with ease</li>
                                <li>✅ Sell in fixed steps (packs, bundles, bulk quantities)</li>
                                <li>✅ Apply rules per product, category, or entire store</li>
                                <li>✅ Prevent invalid orders & improve checkout experience</li>
                            </ul>
                            <p>✅ Perfect for wholesale, bulk selling, and better stock control.</p>
                            <p>✅ <strong>Unlock Pro</strong> for advanced rules, cart conditions, and full flexibility.</p>',
                    'reshow_after'  => 25,
                    'reshow_unit'   => 'hours',
                    'start_date'    => '2026-04-01',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/min-max-logo.png',
                    'randomize'     => 5,
                    'target_plugin' => array(
                        'slug' => 'woo-min-max-quantity-step-control-single',
                        'path' => 'woo-min-max-quantity-step-control-single/wcmmq.php',
                    ),
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=wcmmq',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )->show();

        }

        // 3. Sync Master Sheet Campaign
        if ( $choice === 3 ) {
            // offer outside plugin pages for sync master sheet plugin
            $this->framework->create_offer( $this->get_offer_args(
                array(
                    'id'            => $this->offer_prefix . 'pssg',
                    'title'         => 'Sync Master Sheet - Product Sync with Google Sheet',
                    'pages'         => array(),
                    'pages_exclude' => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'randomize'     => 5,
                    'highlight_text' => '',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/sync-master.png',
                    'description'   => '<p>🔄 Sync your WooCommerce products with Google Sheets effortlessly. Keep your inventory up-to-date and streamline your workflow. 🚀 Upgrade to Pro for advanced features and seamless integration.</p>',
                    'target_plugin' => array(
                        'slug' => 'product-sync-master-sheet',
                        'path' => 'product-sync-master-sheet/product-sync-master-sheet.php',
                    ),
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=pssg',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )
            ->show();

            // offer inside plugin pages for sync master sheet plugin
            $this->framework->create_offer( $this->get_offer_args(
                array(
                    'id'            => $this->offer_prefix . 'pssg',
                    'title'         => 'Sync Master Sheet - Product Sync with Google Sheet',
                    'pages'         => array( 'wqpmb-settings', 'plugins', 'tools' ),
                    'template'      => 'flash',
                    'randomize'     => 5,
                    'highlight_text' => '',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/sync-master.png',
                    'description'   => '<p>🔄 Sync your WooCommerce products with Google Sheets effortlessly. Keep your inventory up-to-date and streamline your workflow. 🚀 Upgrade to Pro for advanced features and seamless integration.</p>',
                    'target_plugin' => array(
                        'slug' => 'product-sync-master-sheet',
                        'path' => 'product-sync-master-sheet/product-sync-master-sheet.php',
                    ),
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=pssg',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )
            ->show();

            // // Popup inside for sync master sheet plugin
            $this->framework->create_popup( $this->get_popup_args(
                array(
                    'id'            => $this->offer_prefix . 'pssg-popup',
                    'title'         => 'Sync Master Sheet - Product Sync with Google Sheet',
                    'reshow_after'  => 25,
                    'reshow_unit'   => 'hours',
                    'start_date'    => '2026-04-01',
                    'image_url'     => WQPMB_BASE_URL . 'assets/images/sync-master.png',
                    'randomize'     => 5,
                    'target_plugin' => array(
                        'slug' => 'product-sync-master-sheet',
                        'path' => 'product-sync-master-sheet/product-sync-master-sheet.php',
                    ),
                    'description'   => '<p></p>
                            <ul>
                                <li>✅ Sync products in real-time between your store & Google Sheets</li>
                                <li>✅ Edit prices, stock, SKU & more — without logging into WordPress</li>
                                <li>✅ Bulk edit thousands of products in seconds</li>
                                <li>✅ Automate updates & reduce manual work</li>
                            </ul>
                            <p>✅ Perfect for store owners who want faster, smarter product management.</p>
                            <p>✅ Upgrade to Pro for advanced sync, automation, and full control.</p>',
                    'buttons'       => array(
                        array(
                            'text'  => 'Live Demo',
                            'url'   => 'https://wpprincipal.xyz/?demo=pssg',
                            'class' => 'ca-fw-btn-outline',
                            'icon'  => 'dashicons-visibility',
                        ),
                    ),
                )
            ) )->show();

        }
        
    }

    private function get_popup_args( $new_args = array() )
    {
        $default_args = $this->get_offer_args(
            array(
                'id'            => $this->offer_prefix . 'popup-april-2026',
                // 'badge_text'    => 'FLAT 50% OFF',
                'description'   => '<p>Upgrade to the Pro version and get:</p>
                        <ul>
                            <li>✅ Access to all premium features</li>
                            <li>✅ Unlimited templates, Access to new features</li>
                            <li>✅ Priority support</li>
                            <li>✅ Advanced customization</li>
                            <li>✅ Regular updates</li>
                        </ul>
                        <p>30-day money-back guarantee!</p>',
                'pages'         => array( 'wqpmb-settings', 'plugins', 'tools' ),
                'template'      => 'flash',
                'reshow_after'  => 5,
                'reshow_unit'   => 'hours',
                'randomize'     => 20,
                'image_url'     => WQPMB_BASE_URL . 'assets/images/logo.png',
                'buttons'       => array(
                    array(
                        'text'  => 'Upgrade Now - 50% OFF',
                        'url'   => 'https://codeastrology.com/product/wc-quantity-plus-minus-button-pro/',
                        'class' => 'ca-fw-btn-primary',
                        'icon'  => 'dashicons-cart',
                    ),
                    array(
                        'text'  => 'live Demo',
                        'url'   => 'https://wpprincipal.xyz/?demo=wqpmb',
                        'class' => 'ca-fw-btn-outline',
                        'icon'  => 'dashicons-visibility',
                    ),
                ),
            )
        );

        return wp_parse_args( $new_args, $default_args );
    }

    private function get_offer_args( $new_args = array() )
    {
        $default_args = array(
            'title'          => '🎉 Special discount for Plus Minus Button',
            'description'    => 'Get your special discount now! <b>Limited time offer</b> just for you. Maximize your savings with this exclusive deal. Claim your discount!',
            'highlight_text' => 'FLAT 50% OFF',
            // 'badge_text'     => 'FLASH SALE',
            'template'       => 'developer',
            'start_date'     => '2026-04-01',
            'dismiss_type'   => 'temporary',
            'end_date'       => '2026-04-30',
            'reshow_unit'    => 'days',
            'reshow_after'   => 5,
            'image_url'      => WQPMB_BASE_URL . 'assets/images/logo.png',
            'buttons'        => $this->get_wqpmb_purchase_buttons(),
        );

        return wp_parse_args( $new_args, $default_args );
    }

    private function get_wqpmb_purchase_buttons() {
        return array(
            array(
                'text'   => 'Claim Discount',
                'url'    => 'https://codeastrology.com/product/wc-quantity-plus-minus-button-pro/',
                'class'  => 'ca-fw-btn-primary',
                'icon'   => 'dashicons-cart',
                'target' => '_blank',
            ),
            array(
                'text'   => 'View Features',
                'url'    => 'https://codeastrology.com/wc-quantity-plus-minus-button/',
                'class'  => 'ca-fw-btn-secondary',
                'target' => '_blank',
            ),
            array(
                'text'   => 'WordPress.org',
                'url'    => 'https://wordpress.org/plugins/wc-quantity-plus-minus-button/',
                'class'  => 'ca-fw-btn-secondary',
                'target' => '_blank',
            ),
        );
    }

    /**
     * Get recommended plugins list.
     *
     * @param bool $shorted Whether to return a shuffled subset.
     * @return array
     */
    private function get_recommended_plugins( $shorted = false )
    {
        $plugins = array(
            array(
                'slug'        => 'woo-product-table',
                'name'        => 'Product Table for WooCommerce',
                'description' => __( 'Display WooCommerce products in a table layout.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/woo-product-table/assets/icon-256x256.gif',
                'author'      => 'Bizzplugin',
                'path'        => 'woo-product-table/woo-product-table.php',
                'url'         => 'https://wordpress.org/plugins/woo-product-table/',
            ),
            array(
                'slug'        => 'woo-min-max-quantity-step-control-single',
                'name'        => 'Min Max Control - Min Max Quantity & Step Control for WooCommerce',
                'description' => __( 'Control minimum, maximum quantity and step for WooCommerce products.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/woo-min-max-quantity-step-control-single/assets/icon-256x256.png',
                'author'      => 'Bizzplugin',
                'path'        => 'woo-min-max-quantity-step-control-single/wcmmq.php',
                'url'         => 'https://wordpress.org/plugins/woo-min-max-quantity-step-control-single/',
            ),
            array(
                'slug'        => 'product-sync-master-sheet',
                'name'        => 'Sync Master Sheet - Sync with Google Sheet',
                'description' => __( 'Sync your product data with a google sheet.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/product-sync-master-sheet/assets/icon-256x256.gif',
                'author'      => 'Bizzplugin',
                'path'        => 'product-sync-master-sheet/product-sync-master-sheet.php',
                'url'         => 'https://wordpress.org/plugins/product-sync-master-sheet/',
            ),
            array(
                'slug'        => 'ca-quick-view',
                'name'        => 'Bizzview - Quick View for WooCommerce',
                'description' => __( 'Add quick view functionality to your WooCommerce products.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/ca-quick-view/assets/icon-256x256.png?new',
                'author'      => 'Bizzplugin',
                'path'        => 'ca-quick-view/ca-quick-view.php',
                'url'         => 'https://wordpress.org/plugins/ca-quick-view/',
            ),
            array(
                'slug'        => 'bizzswatches',
                'name'        => 'Bizzswatches - Color and Image Swatches',
                'description' => __( 'Add color and image swatches to your WooCommerce products.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/bizzswatches/assets/icon-256x256.png',
                'author'      => 'Bizzplugin',
                'path'        => 'bizzswatches/bizzswatches.php',
                'url'         => 'https://wordpress.org/plugins/bizzswatches/',
            ),
            array(
                'slug'        => 'bizzmudra',
                'name'        => 'Bizzmudra - Multi Currency Switcher',
                'description' => __( 'A multi currency switcher for WooCommerce.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/bizzmudra/assets/icon-256x256.png',
                'author'      => 'Bizzplugin',
                'path'        => 'bizzmudra/bizzmudra.php',
                'url'         => 'https://wordpress.org/plugins/bizzmudra/',
            ),
            array(
                'slug'        => 'sheet-to-wp-table-for-google-sheet',
                'name'        => 'Sheet to Table Live Sync for Google Sheet',
                'description' => __( 'Display Google Sheet data in WordPress tables with live sync.', 'wc-quantity-plus-minus-button' ),
                'icon'        => 'https://ps.w.org/sheet-to-wp-table-for-google-sheet/assets/icon-256x256.png',
                'author'      => 'Bizzplugin',
                'path'        => 'sheet-to-wp-table-for-google-sheet/sheet-to-wp-table-for-google-sheet.php',
                'url'         => 'https://wordpress.org/plugins/sheet-to-wp-table-for-google-sheet/',
            ),
        );

        if ( $shorted ) {
            shuffle( $plugins );
            $plugins = array_slice( $plugins, 0, 6 );
        }

        return $plugins;
    }
}
