<?php

class Element {
	// Element layout
	private $layout;
	// Element attributes
	private $attributes;

	/**
    * Initialization of current element 
    * @return void
    */
	public function __construct( Array $configoretion ) {
		// Initialization of the layout array
		$this->layout = array(
			"before" => "",
			"element" => "",
			"after" => ""
			);
		// Reset attributes
		$this->attributes = array();

		// Cycle layout
		foreach($this->layout as $key => $value ) {
			// Check if has configoretion
			if ( isset($configoretion[$key]) ) {
				// Update layout value
				$this->layout[$key] = $configoretion[$key];
			}
		}

		// Check if element has attributes
		if ( isset($configoretion["attributes"]) ) {
			// Update attributes global array
			$this->attributes = $configoretion["attributes"];
		}

	}

	/**
    * Convert element to html
    * @return String of the element
    */
	public function html() {
		// Set a local variabl for the layout
		$layout = $this->layout;
		// Initialization of the output
		$output = $layout["before"] . $layout["element"];
		// Cycle element attributes
		foreach( $this->attributes as $attribute => $value ) {
			$output .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
		}
		return $output . $layout["after"];
	}

}

?>