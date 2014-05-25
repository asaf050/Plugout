<?php

/*
Plugin Name: Plugout
Description: A plugin for creating WordPress options pages quickly.
Version: 0.0.1
Author: Ron Masas <ronmasas@gmail.com>
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	die("Hi there!  I'm just a plugin, not much I can do when called directly.");
}

// Protected attributes array
$protected_attributes = array("before","after","element","description","label");

// List all libraries files
$_libraries = glob( dirname(__FILE__) . '/libraries/class.*.php' );

// Include all libraries
foreach( $_libraries as $lib ) {
	include $lib;
}

/*
 ------------------
 Actions
 ------------------
 */

 add_action( 'add_meta_boxes', 'po_add_meta_box_callback' );
 add_action( 'save_post', 'po_save_meta_box_data_callback' );

/*
 ------------------
 Help Functions
 ------------------
 */

 function get_configoretion() {
 	$_configoretions = array();
 	// List all configoretion files
 	$configoretions = glob( dirname(__FILE__) . '/configoretions/conf.*.php' );
 	// Cycle all configoretions
 	foreach( $configoretions as $configoretion_file ) {
 		$_configoretions[] = extract_configoretion($configoretion_file);
 	}
 	return $_configoretions;
 }

 function extract_configoretion( $configoretion_file ) {
 	// Check if file exists
 	if ( file_exists($configoretion_file) ) {
 		// Include file
 		include $configoretion_file;
 		// Check if configoretion array is set
 		if ( isset($configoretion) && is_array($configoretion) ) {
 			// Return the configoretion array
 			return $configoretion;
 		}
 	}
 	// Invalid configoretion
 	return false;
 }

  /**
  * Generate html code of new element for the options page.
  * @access public
  * @params Array $args
  * @return String  
  */
function po_create_element( $args = array(
									"element" => "input",
									"type" => "text",
									"name" => "sm_test_input",
									"class" => "regular-text"
									),$prefix,$post_id ) {
	global $protected_attributes,$post;
	$post_id = $post->ID;
	$args["name"] = '_' . $prefix . '_' . $args["name"];
 	$args["value"] = sanitize_text_field(get_post_meta($post->ID,$args["name"],true));
	// Create empty var for the end
	$end = '';
	// Init buffer element with label content
	$buffer = '<p><strong>' . $args["label"] . '</strong><p class="description">' . $args["label_description"] . '</p></p>';
	// Switch element
	switch($args["element"]){
		case "editor":
			// Clean wordpress html
			ob_start();
			ob_clean();
			// Echo buffer content
			echo $buffer;
			// Print wordpress editor
			wp_editor(stripslashes( get_post_meta($post->ID,$args["name"],true) ),$args["name"]);
			// Return printed html
			return ob_get_clean();
		break;
		case "textarea":
			// In case element is a textarea
			if (!isset($args["class"])) {
				// Add WordPress class
				$args["class"] = "large-text";
			}
			// Close html tags
			$end = '>' . $args["value"] . '</' . $args["element"] . '>';
			// Unset value attr to avoid generating double strings
			unset($args["value"]);
		break;
		case "select":
			$end = '</select>';
		break;
		default:
			// Select type
			switch($args["type"]){
				// In case of a checkbox type
				case "checkbox":
					$args["value"] = '1';
					// Check if option if not false
					if ( get_post_meta($post_id,$args["name"],true) == '1' ){
						// Set checked
						$args["checked"] = "checked";
						$args["value"] = '0';
					}
				break;
				// In case of text type
				case "text":
					// If user has not defined a class
					if ( !isset($args["class"]) ) {
						// Set WordPress text input class
						$args["class"] = "regular-text";
					}
				break;
			}
			if ($args["type"] != "checkbox") {
			// Close html tabgs
			$end = ' />';
			}
		break;
	}
	if ( $args["type"] != "radio" ) {
		// Start html tags for element
		$buffer .= '<' . $args["element"];
		// Loop element attributes
		foreach($args as $key => $value ) {
			// If not in protected attributes array
			if ( !in_array($key, $protected_attributes) ) {
				// Add attributes
				$buffer .= ' ' . $key . '="' . esc_attr($value) . '"';
			}
		}
	}
	
	
	switch( $args["element"] ){
		case "select":
			$buffer .= '>';
			
			if ( is_array($args["options"]) ) {
				
				$buffer .= '<option selected="selected">' . $args["placeholder"] . '</option>';
				
				foreach( $args["options"] as $option_value => $label ) {
					$selected = ($option_value == $args["value"]) ? 'selected="selected"' : '';
					$buffer .= '<option value="' . $option_value . '" ' . $selected . ' >' . $label . '</option>'; 
				}
			}
		break;
		case "input":
			switch ( $args["type"] ) {
				case 'radio':
				$after = isset($args["after"]) ? $args["after"] : '<br />';
				if ( is_array($args["options"]) ) {
					foreach( $args["options"] as $value => $label ) {
						$checked = ($value == $args["value"]) ? 'checked="checked"' : '';
						$buffer .= '<label style="display:block;padding:0px;margin:0;"><input type="radio" name="' . $args["name"] . '" value="' . $value . '" ' . $checked . ' />' . $label . '</label>' . $after; 
					}
					$end = '';
				}
				break;
				case 'checkbox':
					if (isset($args["text"])){
						$buffer .= '/> <label for="' . $args["id"] . '">' . $args["text"] . '</label>';
					}
				break;
			}
		break;
	}
	
	// Add end string to element buffer
	$buffer .= $end;
	// Return element as string
	return $buffer;
}

/*
 ------------------
 Callbacks
 ------------------
 */

/**
 * Adds a box to the main column
 */
 function po_add_meta_box_callback() {
 	$_configoretions = get_configoretion();
 	foreach( $_configoretions as $configoretion ) {
 		foreach ($configoretion["screens"] as $screen ) {
 			add_meta_box(
				$configoretion["id"],
				$configoretion["name"],
				'po_meta_box_callback',
				$screen
			);
 		} 
 	}
 }

/**
 * Prints the box content.
 * @param WP_Post $post The object for the current post.
 */
function po_meta_box_callback( $post,$metabox ) {
	$_configoretions = get_configoretion();
	foreach( $_configoretions as $configoretion ) {
 		if ( in_array($post->post_type,$configoretion["screens"]) ) {
 			if ($metabox["id"] == $configoretion["id"] ) {
 				// Add an nonce field so we can check for it later.
				wp_nonce_field( $post->ID . '_meta_box', $post->ID . '_meta_box_nonce' );
 				// For each element in configoretion
 				foreach( $configoretion["elements"] as $element_object ) {
 					// Convert to HTML object
 					echo po_create_element( $element_object,$metabox["id"],$post->ID );
 				}
 			}
 		}
 	}
}

function po_save_meta_box_data_callback( $post_id ) {
	global $post;

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST[ $post_id . '_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST[ $post_id . '_meta_box_nonce'], $post_id . '_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	$_configoretions = get_configoretion();
	foreach( $_configoretions as $configoretion ) {
		if ( in_array($post->post_type,$configoretion["screens"]) ) {
			if (!is_array($configoretion["elements"])){
				continue;
			}
			foreach($configoretion["elements"] as $element ) {
				$name = '_' . $configoretion["id"] . '_' . $element["name"];

				if ( $element["type"] == "checkbox" && !isset($_POST[$name]) ) {
					delete_post_meta($post_id,$name);
				}

				if (isset($_POST[$name])) {
					update_post_meta($post_id,$name,$_POST[$name]);
				}
			}
		}
	}
}
