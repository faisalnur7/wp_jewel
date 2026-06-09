<?php
/**
 * CA Framework - Render Elements Class
 *
 * Handles rendering of various elements within the CA Framework.
 *
 * @package CA_Framework
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CA_Framework_Render_Elements' ) ) {
    
    /**
     * Render Elements Class
     */
    class CA_Framework_Render_Elements {

        /**
         * Render offer buttons HTML.
         *
         * @param array $buttons Button configurations.
         * @return string
         */
        public static function render_buttons( $buttons = array(), $target_plugin = array() ) {

            if ( empty( $buttons ) && empty( $target_plugin['slug'] ) ) {
                return '';
            }

            if( empty($buttons) || !is_array($buttons) ) {
                $buttons = array();
            }

            $html = '<div class="ca-fw-offer-buttons">';


            if( !empty($target_plugin) && is_array($target_plugin) && !empty($target_plugin['slug']) && !empty($target_plugin['path']) ) {

                 $status = CA_Framework_Required_Plugin::get_plugin_status( $target_plugin['path'] );

                if( 'installed' === $status ) {
                    $html .= '<button class="ca-fw-btn ca-fw-btn-activate ca-fw-btn-success" data-action="activate" data-path="' . esc_attr( $target_plugin['path'] ) . '"><span class="dashicons dashicons-yes"></span>Activate Plugin</button>';
                } elseif ( 'not_installed' === $status ) {
                    $html .= '<button class="ca-fw-btn ca-fw-btn-install ca-fw-btn-primary" data-action="install" data-slug="' . esc_attr( $target_plugin['slug'] ) . '"><span class="dashicons dashicons-download"></span>Install Plugin</button>';
                }else{
                    $html .= '<button class="ca-fw-btn ca-fw-btn-success" onclick="return false;"><span class="dashicons dashicons-yes-alt"></span> Actived</button>';
                }
                
                array_unshift($buttons, array(
                    'text'   => 'Learn More',
                    'url'    => 'https://wordpress.org/plugins/' . esc_attr( $target_plugin['slug'] ),
                    'class'  => 'ca-fw-btn-primary',
                    'target' => '_blank',
                    'icon'   => 'dashicons-external',
                ));
                
            }
            
            foreach ( $buttons as $button ) {
                $btn = wp_parse_args( $button, array(
                    'text'   => '',
                    'url'    => '#',
                    'class'  => 'ca-fw-btn-primary',
                    'target' => '_blank',
                    'icon'   => '',
                ) );

                $icon_html = '';
                if ( ! empty( $btn['icon'] ) ) {
                    $icon_html = '<span class="dashicons ' . esc_attr( $btn['icon'] ) . '"></span> ';
                }

                $html .= sprintf(
                    '<a href="%s" class="ca-fw-btn %s" target="%s">%s%s</a>',
                    esc_url( $btn['url'] ),
                    esc_attr( $btn['class'] ),
                    esc_attr( $btn['target'] ),
                    $icon_html,
                    esc_html( $btn['text'] )
                );
            }
            $html .= '</div>';

            return $html;
        }

        /**
         * Render countdown HTML if enabled and end_date is set.
         *
         * @param array $config Offer or popup configuration array.
         * @return string
         */
        public static function render_countdown( $config = array() ) {
            if ( empty( $config['show_countdown'] ) || empty( $config['end_date'] ) ) {
                return '';
            }

            $end_timestamp = strtotime( $config['end_date'] );
            if ( ! $end_timestamp ) {
                return '';
            }

            $end_iso = gmdate( 'Y-m-d\TH:i:s', $end_timestamp );

            return '<div class="ca-fw-countdown" data-end-date="' . esc_attr( $end_iso ) . '">
                <div class="ca-fw-countdown-item">
                    <span class="ca-fw-countdown-number" data-days>00</span>
                    <span class="ca-fw-countdown-label">' . esc_html__( 'Days', 'flavor-jelee' ) . '</span>
                </div>
                <div class="ca-fw-countdown-sep">:</div>
                <div class="ca-fw-countdown-item">
                    <span class="ca-fw-countdown-number" data-hours>00</span>
                    <span class="ca-fw-countdown-label">' . esc_html__( 'Hours', 'flavor-jelee' ) . '</span>
                </div>
                <div class="ca-fw-countdown-sep">:</div>
                <div class="ca-fw-countdown-item">
                    <span class="ca-fw-countdown-number" data-minutes>00</span>
                    <span class="ca-fw-countdown-label">' . esc_html__( 'Min', 'flavor-jelee' ) . '</span>
                </div>
                <div class="ca-fw-countdown-sep">:</div>
                <div class="ca-fw-countdown-item">
                    <span class="ca-fw-countdown-number" data-seconds>00</span>
                    <span class="ca-fw-countdown-label">' . esc_html__( 'Sec', 'flavor-jelee' ) . '</span>
                </div>
            </div>';
        }
    }
}