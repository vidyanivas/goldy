<?php

abstract class Pt_Query_Interface {

	protected $atts;

	function __construct( $atts ) {
		$this->atts = $atts;
	}
	
	protected function get_att( $att_name, $default = null ) {
		if ( array_key_exists( $att_name, $this->atts ) && ! in_array( $this->atts[ $att_name ], [ '', null ], true ) ) {
			return $this->atts[ $att_name ];
		}

		if ( $default !== null ) {
			return $default;
		}

		return '';
	}

	abstract function parse_query_args();
}