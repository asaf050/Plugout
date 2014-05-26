<?php

$configoretion = array(
		"id" => "second-metabox",
		"screens" => array("page","post"),
		"name" => "Second Testing",
		"elements" => array(
						array(
                            "label" => "A Simple Text Input",
                            "label_description" => "this is the a simple description for this label.",
                            "element" => "input",
                            "type" => "text",
                            "placeholder" => "Enter some text here",
                            "name" => "simple_input_name"
                            ),
                            array(
                            "label" => "A Simple Text Area Element",
                            "label_description" => "this is the a simple description for this label.",
                            "element" => "textarea",
                            "placeholder" => "Enter textarea text here",
                            "name" => "simple_textarea_name",
                            "style" => "height: 100px;"
                            ),
                            array(
                            "label" => "A Simple Color Input Element",
                            "label_description" => "this is the a simple description for this label.",
                            "element" => "input",
                            "type" => "color",
                            "name" => "background_color_input_name",
                            "placeholder" => ""
                            ),
                            array(
                            "label" => "A Simple Checkbox Element",
                            "label_description" => "this is the a simple description for this label.",
                            "element" => "input",
                            "type" => "checkbox",
                            "name" => "checkbox_name",
                            "text" => " Check this box",
                            "id" => "checkbox_name"
                            ),
                            array(
                            "label" => "A Simple Select box Element",
                            "label_description" => "this is the a simple description for this label.",
                            "element" => "select",
                            "name" => "selectbox_name",
                            "options" => array(
                                            "first_option" => "The First Option",
                                            "second_option" => "The Second Option"
                                        ),
                            "placeholder" => "Select one of the options"
                            ),
                            array(
                            "label" => "Select an option",
                            "label_description" => "this is a description for the radio input type.",
                            "element" => "input",
                            "type" => "radio",
                            "name" => "radio_element_name",
                            "options" => array(
                                         "first_value" => "The First Value",
                                         "second_value" => "The Second Value"
                                         )
                            ),
                            array(
                            "label" => "WordPress Editor",
                            "label_description" => "write your content upload images and more.",
                            "element" => "editor",
                            "name" => "editor_content_name",
                            ),
					  )
		);

?>
