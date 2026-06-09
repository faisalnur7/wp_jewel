<?php
namespace WQPMB\Admin;

use WQPMB\Core\Base;
use WQPMB\Admin\Premium_Placeholder as Placeholder;
use WQPMB\Admin\Adm_Inc\Notice_Framework;

class Page_Loader extends Base
{
    public $slug = WQPMB_MENU_SLUG;
    protected $parent_slug = 'woocommerce';
    public $option_key;
    public $data;
    public $is_pro = true;
    public $notice_framework;

    //for freemius version
    public $is_premium = false;

    /**
     * Right slash already set
     *
     * @var [type]
     */
    public $html_folder_dir;
    public $topbar_file_dir;


    public function __construct()
    {
        $this->is_premium = wqpmb_is_premium();
        /**
         * No need to call construct
         * actually I assign again option_key and data
         * 
         * @since 1.1.8.2
         */
        // parent::__construct();
        $this->option_key = \WQPMB_Button::$option['option'];
        
        $this->data = get_option( $this->option_key);

        $this->html_folder_dir = $this->base_dir . '/admin/html/';
        $this->topbar_file_dir = $this->base_dir . '/admin/html/topbar.php';

        
        add_action( 'admin_init', [ $this, 'init_notice_framework' ] );

        //Initialize Premium Placeholder
        $placeholder = new Placeholder();   
        $placeholder->run();

    }

    public function run()
    {
        //Add menu
        add_action( 'admin_menu', [$this, 'admin_menu'] );
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );

        if( class_exists( '\WQPMB\Admin\Appseros\Src\Client' ) ) {
            $client = new \WQPMB\Admin\Appseros\Src\Client( 'b39316df-e8df-44aa-be77-277dac809411', 'Quantity Plus Minus Button', WQPMB_MAIN_FILE );
            $client->insights()->init();
        }
    }

    /**
     * Initialize Notice Framework offers.
     * Hooked to admin_init to ensure WP functions like wp_rand() are available.
     *
     * @return void
     */
    public function init_notice_framework()
    {
        //Initialize Notice Framework
        $this->notice_framework = new Notice_Framework();
        
        $this->notice_framework->show_recommended_plugins();
        //apatoto bad,tobe chalu korbo porer update e
        // $this->notice_framework->show_all_premium_discount_offer();

        //etate chalu korle age check kore nite hobe. er por update e chalu korbo
        // $this->notice_framework->show_random_campaigns_offers();
        // if ( $this->is_premium ) {
        //     $this->notice_framework->offer_about_wqpmb_only_free_version();
        // } else {
        //     $this->notice_framework->offer_about_wqpmb_premium();
        // }
    }




    public function admin_menu()
    {
        $icon = $this->assets_url . 'images/menu-icon.png';
        $capability = apply_filters( 'wqpmb_menu_capability', 'manage_woocommerce' );
        add_menu_page(WQPMB_NAME, WQPMB_MENU_NAME, $capability, $this->slug, [$this,'page_html'], $icon, 56 );
        add_submenu_page($this->parent_slug, WQPMB_NAME, WQPMB_MENU_NAME, $capability, $this->slug, [$this,'page_html']);
    }
    public function page_html()
    {
        $page_file = $this->html_folder_dir . 'main-page.php';
        if(is_file($page_file)){
            include $this->topbar_file_dir;
            include $page_file;
        }
    }
    public function admin_enqueue_scripts( $hook_suffix )
    {
        global $current_screen;

        $s_id = isset( $current_screen->id ) ? $current_screen->id : '';
        if( strpos( $s_id, $this->plugin_prefix ) !== false ){

            wp_enqueue_style( 'wp-color-picker' );
            wp_register_script( $this->plugin_prefix . '-admin-script', $this->base_url .'assets/js/admin-script.js', array( 'wp-color-picker' ), false, true );
            wp_enqueue_script( $this->plugin_prefix . '-admin-script' );

            $ajax_url = admin_url( 'admin-ajax.php' );
            $WQPMB_ADMIN_DATA = array( 
                'ajax_url'       => $ajax_url,
                'site_url'       => site_url(),
                'cart_url'       => wc_get_cart_url(),
                );
            wp_localize_script( $this->plugin_prefix . '-admin-script', 'WQPMB_ADMIN_DATA', $WQPMB_ADMIN_DATA );
            
            $this->live_chat_script();
            add_filter('admin_footer_text',[$this, 'admin_footer_text']);
            
            wp_register_style( $this->plugin_prefix . '-icon-font', $this->base_url . 'assets/fontello/css/wqpmb-icon.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-font' );

            
            wp_register_style( $this->plugin_prefix . '-icon-animation', $this->base_url . 'assets/fontello/css/animation.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-animation' );




            wp_register_style( $this->plugin_prefix . '-admin', $this->base_url . 'assets/css/admin-style.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-admin' );


            wp_register_style( $this->plugin_prefix . '-new-admin', $this->base_url . 'assets/css/new-admin.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-new-admin' );

        }

    }

    /**
     * Adding live chat script in admin footer
     *
     * @return void
     */
    protected function live_chat_script()
    {
        /**
         * how to disable live chat
         * add_filter('wpt_live_chat_bool','__return_false');
         */
        $live_chat_bool = apply_filters( 'wpt_live_chat_bool', true );
        if( ! $live_chat_bool ) return;
        ?>
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/628f5d4f7b967b1179915ad7/1g4009033';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Tawk.to Script-->
        <?php
    }
    public function admin_footer_text($text)
    {
        $rev_link = 'https://wordpress.org/support/plugin/wc-quantity-plus-minus-button/reviews/#new-post';
        $text = sprintf(
            /* translators: 1: link to the plugin review page 2: start icon */
			__( 'Thank you for using Plus Minus Button. <a href="%1$s" target="_blank">%2$sPlease review us</a>.', 'wc-quantity-plus-minus-button' ),
			$rev_link,
            '<i class="wqpmb_icon-star-filled"></i><i class="wqpmb_icon-star-filled"></i><i class="wqpmb_icon-star-filled"></i><i class="wqpmb_icon-star-filled"></i><i class="wqpmb_icon-star-filled"></i>'
		);
        return '<span id="footer-thankyou" class="wqpmb-footer-thankyou">' . $text . '</span>';
    }

    /**
     * Adding tawk.to Live Chat support rendering
     * here
     *
     * @return void
     */
    public function tawkto_code()
    {
        global $current_screen;
        $s_id = isset( $current_screen->id ) ? $current_screen->id : '';

        if( strpos( $s_id, $this->plugin_prefix) == false ) return;

        //If disbale option found, we will remove live support
        $off = $this->data['extra']['disable_live_support'] ?? 'no_disable';
        if($off == '1') return;
        ?>
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/628f5d4f7b967b1179915ad7/1g4009033';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Tawk.to Script-->      
        <?php
       
    }
}
