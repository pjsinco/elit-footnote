<?php

/**
 * Plugin Name: Elit Popup
 * Plugin URI: https://github.com/pjsinco/elit-popup
 * Description: Display tooltip pop-ups
 * Version: 0.1.0
 * Author: Patrick Sinco
 * License: GPL2
 */

if ( !defined( 'WPINC' ) ) {
  die;
}

class Elit_Popup
{

  private $footnotes = array();
  private $foo;

  public function __construct() {
    add_action( 'wp_enqueue_scripts' , array( $this, 'elit_popup_enqueue_scripts' ) );
    add_action( 'init' , array( $this, 'elit_popup_init' ) );
  }

  public function elit_popup_init() {

    if ( shortcode_exists('popup') ) return;

    add_shortcode( 'popup', array( $this, 'elit_popup_shortcode' ) );
    add_filter( 'the_content',  array( $this, 'elit_popup_content' ), 12 );
  }

  public function elit_popup_content( $content ) {
    global $id;
    $footnotes  = '<div class="footnotes">';
    $footnotes .= '<ol>';
    foreach ($this->footnotes[$id] as $footnote) {
      $footnotes .= $footnote;
    }
    $footnotes .= '</ol>';
    $footnotes .= '</div>';
    
    return $content . $footnotes;
  }


  public function elit_popup_shortcode( $atts, $content )
  {

    wp_enqueue_style('elit-popup-styles');
    wp_enqueue_script('elit-popup-vendor-scripts');
    wp_enqueue_script('elit-popup-scripts');

    $shortcode_atts = shortcode_atts( array(
      'name' => '',
      'thing' => '',
      'other-thing' => '',
      'another-thing' => '',
    ), $atts, 'popup' );

    $atts = $this->elit_popup_format_atts( $shortcode_atts );

    global $id;

    $count = count($this->footnotes[$id]);

    $item  = "<li class='footnote' id='popup:$count'>";

    if ( $shortcode_atts['name'] ) {
      $item .= '<h5>' . $shortcode_atts['name'] . '</h5>';
    }
    if ( $shortcode_atts['thing'] ) {
      $item .= '<p><strong>Thing:</strong> ' . $shortcode_atts['thing'] . '</p>';
    }
    if ( $shortcode_atts['other-thing'] ) {
      $item .= '<p><strong>Other Thing:</strong> ' . $shortcode_atts['other-thing'] . '</p>';
    }
    if ( $shortcode_atts['another-thing'] ) {
      $item .= '<p><strong>Another Thing:</strong> ' . $shortcode_atts['another-thing'] . '</p>';
    }

    $item .= "<a href='#fnref:$count' title='Return to article'> ↩</a></li>";

    if ( ! isset( $this->footnotes[$id] ) ) {
      $this->footnotes[$id] = array();
    }

    array_push($this->footnotes[$id], $item);
    return "<sup id='fnref:$count'><a href='#popup:$count' rel='footnote'></a></sup>";
  }

  /**
   * Change a hyphen to an underscore in the keys to an array.
   *
   * @param array $atts The shortcode attributes
   * @return array $atts The shortcode attributes with replaced keys
   */
  public function elit_popup_format_atts( $atts ) {
    
    return array_combine(
      array_map( function( $key ) use ( $atts ) { 
        return str_replace( '-', '_', $key );
      }, array_keys( $atts ) ), 
      array_values( $atts )
    );
  }

  public function elit_popup_enqueue_scripts() {
  
    $styles_path = 'public/styles/elit-popup.css';
    $scripts_path = 'public/scripts/elit-popup.min.js';
    $vendor_scripts_path = 'vendor/scripts/bigfoot.min.js';

    wp_register_style(
      'elit-popup-styles',
      plugins_url( $styles_path, __FILE__ ),
      array(),
      filemtime( plugin_dir_path(__FILE__) . '/' . $styles_path )
    );
  
    wp_register_script(
      'elit-popup-vendor-scripts',
      plugins_url( $vendor_scripts_path, __FILE__ ),
      array( 'jquery' ),
      filemtime( plugin_dir_path(__FILE__) . '/' . $vendor_scripts_path )
    );
  
    wp_register_script(
      'elit-popup-scripts',
      plugins_url( $scripts_path, __FILE__ ),
      array( 'elit-popup-vendor-scripts' ),
      filemtime( plugin_dir_path(__FILE__) . '/' . $scripts_path ),
      true
    );
  
  }
}

$elit_popup = new Elit_Popup;


