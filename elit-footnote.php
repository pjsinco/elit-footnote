<?php

/**
 * Plugin Name: Elit Footnote
 * Plugin URI: https://github.com/pjsinco/elit-footnote
 * Description: Display footnotes
 * Version: 0.1.0
 * Author: Patrick Sinco
 * License: GPL2
 */

if ( !defined( 'WPINC' ) ) {
  die;
}

function elit_footnote_enqueue_scripts() {

  $styles_path = 'public/styles/elit-footnote.css';
  $scripts_path = 'public/scripts/elit-footnote.min.js';

  wp_enqueue_style(
    'elit-footnote-styles',
    plugins_url( $styles_path, __FILE__ ),
    array(),
    filemtime( plugin_dir_path(__FILE__) . '/' . $styles_path )
  );

  wp_enqueue_script(
    'elit-footnote-scripts',
    plugins_url( $scripts_path, __FILE__ ),
    array( 'jquery' ),
    filemtime( plugin_dir_path(__FILE__) . '/' . $scripts_path ),
    true
  );

}
add_action( 'wp_enqueue_scripts' , 'elit_footnote_enqueue_scripts' );
