<?php
namespace WQPMB\Admin;
use WQPMB\Core\Base;

class Premium_Placeholder extends Base
{
    public $is_premium;

    public function __construct()
    {
        $this->is_premium = wqpmb_is_premium();
    }

    public function run()
    {
        
        //Actually if premium enabled, then it will not called/Execute
        if( $this->is_premium ) return;
        //Add action hook for premium placeholder
        // add_action( 'wqpmb_bottom_panel', array( $this, 'premium_form_placeholder' ), 10, 2 );
    }

    public function premium_form_placeholder( $our_data, $datas )
    {
        ?>
        <!-- Premium Features Placeholder -->
        <div class="wqpmb-premium-placeholder-wrapper">
            <div class="wqpmb-premium-header">
                <h3>
                    <span class="wqpmb_icon-spin6 animate-spin"  style="font-size: 13px;"></span>
                    <?php echo esc_html__( 'Premium Features', 'wc-quantity-plus-minus-button' ); ?>
                    <span class="wqpmb_icon-spin6 animate-spin"  style="font-size: 13px;"></span>
                </h3>
                <p><?php echo esc_html__( 'Unlock powerful features to enhance your quantity buttons!', 'wc-quantity-plus-minus-button' ); ?></p>
            </div>
            
            <div class="wqpmb-premium-features-grid">
                <div class="wqpmb-premium-feature-card">
                    <span class="dashicons dashicons-art"></span>
                    <h4><?php echo esc_html__( 'Custom Button Icons', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Choose from multiple icon styles or use custom text/icons for your buttons.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>
                
                <div class="wqpmb-premium-feature-card">
                    <span class="dashicons dashicons-performance"></span>
                    <h4><?php echo esc_html__( 'Button Animations', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Add eye-catching animations like pulse, bounce, scale, and rotate effects.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>
                
                <div class="wqpmb-premium-feature-card">
                    <span class="dashicons dashicons-move"></span>
                    <h4><?php echo esc_html__( 'Button Position Control', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Control button positioning with vertical, reversed, and custom layouts.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>
                
                <div class="wqpmb-premium-feature-card">
                    <span class="dashicons dashicons-admin-appearance"></span>
                    <h4><?php echo esc_html__( 'Advanced Styling', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Add box shadows, custom transitions, and advanced CSS effects.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>
                
                <div class="wqpmb-premium-feature-card">
                    <span class="dashicons dashicons-admin-tools"></span>
                    <h4><?php echo esc_html__( 'Priority Support', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Get dedicated priority support for all your questions and issues.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>
                
                <div class="wqpmb-premium-feature-card">
                    <span class="dashicons dashicons-update"></span>
                    <h4><?php echo esc_html__( 'Regular Updates', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Receive regular updates with new features and improvements.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>
                <div class="wqpmb-premium-feature-card">
                    <span class="wqpmb_icon-spin6 animate-spin"></span>
                    <h4><?php echo esc_html__( 'Comming Template', 'wc-quantity-plus-minus-button' ); ?></h4>
                    <p><?php echo esc_html__( 'Create custom button templates with unique styles and layouts.', 'wc-quantity-plus-minus-button' ); ?></p>
                </div>


            </div>
            
            <div class="wqpmb-premium-cta">
                <a href="https://codeastrology.com/downloads/" class="wqpmb-premium-button">
                    <span class="wqpmb_icon-basket-1"></span>
                    <?php echo esc_html__( 'Upgrade to Premium', 'wc-quantity-plus-minus-button' ); ?>
                </a>
                <p class="wqpmb-money-back"><?php echo esc_html__( '30-Day Money Back Guarantee', 'wc-quantity-plus-minus-button' ); ?></p>
            </div>
        </div>

        
        <style>
        .wqpmb-premium-placeholder-wrapper {
            background: linear-gradient(135deg, #68004f 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 30px;
            margin: 20px 0;
            color: #fff;
        }
        
        .wqpmb-premium-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .wqpmb-premium-header h3 {
            color: #fff;
            font-size: 28px;
            margin: 0 0 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .wqpmb-premium-header .dashicons {
            font-size: 32px;
            width: 32px;
            height: 32px;
            color: #9C27B0;
        }
        
        .wqpmb-premium-header p {
            font-size: 16px;
            margin: 0;
            opacity: 0.95;
        }
        
        .wqpmb-premium-features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .wqpmb-premium-feature-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgb(255 255 255 / 7%);
            transition: transform 0.3s ease, background 0.3s ease;
            cursor: pointer;
            text-align: center;
        }
        
        .wqpmb-premium-feature-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .wqpmb-premium-feature-card .dashicons {
            font-size: 36px;
            width: 36px;
            height: 36px;
            margin-bottom: 10px;
            color: #4CAF50;
        }
        .wqpmb-premium-feature-card:hover .dashicons {
            color: #FFEB3B;
        }
        
        .wqpmb-premium-feature-card h4 {
            color: #fff;
            font-size: 16px;
            margin: 10px 0;
        }
        
        .wqpmb-premium-feature-card p {
            color: rgb(255 255 255 / 41%);
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }
        
        .wqpmb-premium-cta {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgb(255 255 255 / 8%);
        }
        
        .wqpmb-premium-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #4CAF50;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .wqpmb-premium-button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: #4CAF50;
            text-decoration: none;
        }
        
        .wqpmb-premium-button .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }
        
        .wqpmb-money-back {
            margin-top: 15px;
            font-size: 13px;
            opacity: 0.9;
        }
        
        .premium-placeholder-row td {
            padding: 0 !important;
        }
        </style>
        <?php 
    }
}