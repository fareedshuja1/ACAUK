<?php



	
	/**
	 * Google Maps shortcode
	 */
	add_shortcode( 'gglmap', 'smt_gglmap_handler' );
	function smt_gglmap_handler( $atts, $content = null ){
		
		$atts = wp_parse_args( (array) $atts, array(
			'address' => '',
			'zoom' => '16',
			'type' => 'HYBRID'
		) );
		
		return
		'<div class="smt_googlemap"
			data-address="' . $atts[ 'address' ] . '"
			data-zoom="' . $atts[ 'zoom' ] . '"
			data-type="' . $atts[ 'type' ] . '"
		></div>';
	}
	
	
	
	
	
	
	/**
	 * ToolTips shortcode
	 */
	add_shortcode( 'tooltip', 'smt_tooltip_handler' );
	function smt_tooltip_handler( $atts, $content = '' ){
		
		$atts = wp_parse_args( (array) $atts, array(
			'hint' => ''
		) );
		
		return
		'<span class="tooltip" title="' . $atts[ 'hint' ] .'">' . $content . '</span>';
	}
	