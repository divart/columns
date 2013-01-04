<?php
/**
 * Plugin Name: Columns
 * Description: Use a [column] shortcode inside a [column-group] to create magic.
 * Author: Konstantin Kovshenin
 */

class Columns_Plugin {

	public $current_group = 0;
	public $span = array();

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	function init() {
		add_shortcode( 'column', array( $this, 'column' ) );
		add_shortcode( 'column-group', array( $this, 'group' ) );
	}

	function column( $attr, $content ) {
		$attr = shortcode_atts( array(
			'span' => 1,
		), $attr );

		$attr['span'] = absint( $attr['span'] );
		$this->span[ $this->current_group ] += $attr['span'];

		return sprintf( '<div class="column column-number-%d column-span-%d">%s</div>', $this->span[ $this->current_group ], $attr['span'], $content );
	}

	function group( $attr, $content ) {
		$this->current_group++;
		$this->span[ $this->current_group ] = 0;

		// Convent and count columns.
		$content = do_shortcode( $content );

		$count = $this->span[ $this->current_group ];
		$content = str_replace( 'class="column column-number-' . $count, 'class="column column-number-' . $count . ' last', $content );

		return sprintf( '<div class="column-group columns-%d">%s</div>', $count, $content );
	}

	function enqueue_scripts() {
		wp_enqueue_style( 'columns', plugins_url( 'columns.css', __FILE__ ) );
	}
}
new Columns_Plugin;