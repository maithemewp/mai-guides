<?php

new Mai_Guides_Display;

class Mai_Guides_Display {

	function __construct() {
		$this->hooks();
	}

	function hooks() {

		add_action( 'wp_enqueue_scripts',   array( $this, 'register_styles' ) );
		add_filter( 'theme_page_templates', array( $this, 'add_post_template' ) );
		add_filter( 'template_include',     array( $this, 'load_post_template' ) );
		// add_action( 'genesis_setup',        array( $this, 'register_sidebar' ) );
		// add_action( 'genesis_before',       array( $this, 'replace_sidebar' ) );
		add_shortcode( 'guides',            array( $this, 'register_shortcode' ) );
	}

	// Register CSS files.1
	function register_styles() {
		wp_register_style( 'mai-guides', MAI_GUIDES_PLUGIN_URL . 'assets/css/mai-guides.css', array(), MAI_GUIDES_VERSION );
	}

	/**
	 * Add page templates.
	 * Override all but sections template by copying files from /mai-guides/templates/{filename}.php
	 * and putting in /{child-theme-name}/templates/{filename}.php
	 *
	 * @since   0.1.0
	 * @param   array  $page_templates  The existing page templates.
	 * @return  array  $page_templates  The modified page templates.
	 */
	function add_post_template( $page_templates ) {
		$page_templates['guide.php']  = __( 'Guide', 'mai-guides' );
		return $page_templates;
	}

	/**
	 * Modify page based on selected post template.
	 *
	 * @since   0.1.0
	 * @param   string  $template  The path to the template being included.
	 * @return  string  $template  The modified template path to be included.
	 */
	function load_post_template( $template ) {
		/**
		 * Bail if not a single post.
		 * We don't need post templates here anyway.
		 */
		if ( ! is_singular( 'post' ) ) {
			return $template;
		}
		// Get current template.
		$template_name = get_post_meta( get_the_ID(), '_wp_page_template', true );
		// Bail if not a template from our plugin.
		if ( ! 'guide.php' !== basename( $template_name ) ) {
			return $template;
		}
		// Get the child theme template path.
		$_template = get_stylesheet_directory() . '/templates/' . $template_name;
		// If the template exists in the child theme.
		if ( file_exists( $_template ) ) {
			// Use child theme template.
			$template = $_template;
		} else {
			// Use our plugin template.
			$plugin_path = MAI_GUIDES_PLUGIN_DIR . 'templates/';
			if ( file_exists( $plugin_path . $template_name ) ) {
				$template = $plugin_path . $template_name;
			}
		}
		return $template;
	}

	// function register_sidebar() {
	// 	// Register widget area.
	// 	genesis_register_sidebar( array(
	// 		'id'          => 'guides',
	// 		'name'        => __( 'Guides', 'mai-guides' ),
	// 		'description' => __( 'This is the widget that appears on guides and posts that are in guides.', 'mai-guides' ),
	// 	) );
	// }

	// function replace_sidebar() {

	// 	// Bail if not a post or a guide.
	// 	if ( ! is_singular( array( 'post', 'mai_guide' ) ) ) {
	// 		return;
	// 	}

	// 	// Bail if a post that's not using the guide.php template.
	// 	if ( is_singular( 'post' ) && ! is_page_template( 'templates/guide.php' ) ) {
	// 		return;
	// 	}

	// 	// Remove default sidebar
	// 	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

	// 	// Add our new sidebar
	// 	add_action( 'genesis_sidebar', function() {
	// 		dynamic_sidebar( 'guides' );
	// 	});
	// }

	function register_shortcode( $atts ) {
		return;
	}
}
