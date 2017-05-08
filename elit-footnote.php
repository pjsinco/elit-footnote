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

class Elit_More
{

  private $footnotes = array();
  private $foo;

  public function __construct() {
    add_action( 'wp_enqueue_scripts' , array( $this, 'elit_more_enqueue_scripts' ) );
    add_action( 'init' , array( $this, 'elit_more_init' ) );
  }

  public function elit_more_init() {

    if ( shortcode_exists('more') ) return;

    add_shortcode( 'more', array( $this, 'elit_more_shortcode' ) );
    add_filter( 'the_content',  array( $this, 'elit_more_content' ), 12 );
  }

  public function elit_more_content( $content ) {
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


  public function elit_more_shortcode( $atts, $content )
  {

    wp_enqueue_style('elit-footnote-styles');
    wp_enqueue_script('elit-footnote-vendor-scripts');
    wp_enqueue_script('elit-footnote-scripts');

    $shortcode_atts = shortcode_atts( array(
      'name' => '',
      'thing' => '',
      'other-thing' => '',
      'another-thing' => '',
    ), $atts, 'more' );

    $atts = $this->elit_more_format_atts( $shortcode_atts );

    global $id;

    $count = count($this->footnotes[$id]);

    $item  = "<li class='footnote' id='more:$count'>";

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
    return "<sup id='fnref:$count'><a href='#more:$count' rel='footnote'></a></sup>";
  }

  /**
   * Change a hyphen to an underscore in the keys to an array.
   *
   * @param array $atts The shortcode attributes
   * @return array $atts The shortcode attributes with replaced keys
   */
  public function elit_more_format_atts( $atts ) {
    
    return array_combine(
      array_map( function( $key ) use ( $atts ) { 
        return str_replace( '-', '_', $key );
      }, array_keys( $atts ) ), 
      array_values( $atts )
    );
  }

  public function elit_more_enqueue_scripts() {
  
    $styles_path = 'public/styles/elit-footnote.css';
    $scripts_path = 'public/scripts/elit-footnote.min.js';
    $vendor_scripts_path = 'vendor/scripts/bigfoot.min.js';

    wp_register_style(
      'elit-footnote-styles',
      plugins_url( $styles_path, __FILE__ ),
      array(),
      filemtime( plugin_dir_path(__FILE__) . '/' . $styles_path )
    );
  
    wp_register_script(
      'elit-footnote-vendor-scripts',
      plugins_url( $vendor_scripts_path, __FILE__ ),
      array( 'jquery' ),
      filemtime( plugin_dir_path(__FILE__) . '/' . $vendor_scripts_path )
    );
  
    wp_register_script(
      'elit-footnote-scripts',
      plugins_url( $scripts_path, __FILE__ ),
      array( 'elit-footnote-vendor-scripts' ),
      filemtime( plugin_dir_path(__FILE__) . '/' . $scripts_path ),
      true
    );
  
  }
}

$elit_more = new Elit_More;


