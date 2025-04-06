<?php
/**
 * GrafixPoint Kadence Child Theme functions and definitions
 *
 * @package GrafixPoint Kadence Child
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function grafixpoint_child_enqueue_styles() {
    // Enqueue Google Fonts
    wp_enqueue_style(
        'grafixpoint-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@600;700;800&family=Roboto:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap',
        array() ,
        null
    );
    
    // Enqueue parent theme style
    wp_enqueue_style(
        'kadence-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('kadence')->get('Version')
    );
    
    // Enqueue child theme style
    wp_enqueue_style(
        'grafixpoint-child-style',
        get_stylesheet_uri(),
        array('kadence-style'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue custom JavaScript
    wp_enqueue_script(
        'grafixpoint-child-script',
        get_stylesheet_directory_uri() . '/js/script.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
    
    // Localize script for dark mode
    wp_localize_script(
        'grafixpoint-child-script',
        'grafixpointSettings',
        array(
            'darkModeDefault' => get_theme_mod('dark_mode_default', false),
        )
    );
}
add_action('wp_enqueue_scripts', 'grafixpoint_child_enqueue_styles');

/**
 * Add dark mode body class if enabled
 */
function grafixpoint_body_classes($classes) {
    // Add dark mode class if enabled
    if (get_theme_mod('dark_mode_default', false)) {
        $classes[] = 'dark-mode';
    }
    
    // Add class for AI content categories
    if (is_category()) {
        $category = get_queried_object();
        if ($category) {
            $classes[] = 'category-' . $category->slug;
        }
    }
    
    return $classes;
}
add_filter('body_class', 'grafixpoint_body_classes');

/**
 * Register widget areas
 */
function grafixpoint_widgets_init() {
    register_sidebar(array(
        'name'          => __('Homepage Hero', 'grafixpoint-kadence-child'),
        'id'            => 'homepage-hero',
        'description'   => __('Add widgets here to appear in the homepage hero section.', 'grafixpoint-kadence-child'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('AdSense Header', 'grafixpoint-kadence-child'),
        'id'            => 'adsense-header',
        'description'   => __('Add AdSense code here to appear in the header section.', 'grafixpoint-kadence-child'),
        'before_widget' => '<div id="%1$s" class="widget ad-container ad-container-header %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title screen-reader-text">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('AdSense Content', 'grafixpoint-kadence-child'),
        'id'            => 'adsense-content',
        'description'   => __('Add AdSense code here to appear within content.', 'grafixpoint-kadence-child'),
        'before_widget' => '<div id="%1$s" class="widget ad-container ad-container-content %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title screen-reader-text">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('AdSense Sidebar', 'grafixpoint-kadence-child'),
        'id'            => 'adsense-sidebar',
        'description'   => __('Add AdSense code here to appear in the sidebar.', 'grafixpoint-kadence-child'),
        'before_widget' => '<div id="%1$s" class="widget ad-container ad-container-sidebar %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title screen-reader-text">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('AdSense Footer', 'grafixpoint-kadence-child'),
        'id'            => 'adsense-footer',
        'description'   => __('Add AdSense code here to appear in the footer section.', 'grafixpoint-kadence-child'),
        'before_widget' => '<div id="%1$s" class="widget ad-container ad-container-footer %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title screen-reader-text">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'grafixpoint_widgets_init');

/**
 * Add theme customizer options
 */
function grafixpoint_customize_register($wp_customize) {
    // Add section for theme options
    $wp_customize->add_section('grafixpoint_options', array(
        'title'    => __('GrafixPoint Options', 'grafixpoint-kadence-child'),
        'priority' => 30,
    ));
    
    // Add dark mode default setting
    $wp_customize->add_setting('dark_mode_default', array(
        'default'           => false,
        'sanitize_callback' => 'grafixpoint_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('dark_mode_default', array(
        'label'    => __('Enable Dark Mode by Default', 'grafixpoint-kadence-child'),
        'section'  => 'grafixpoint_options',
        'type'     => 'checkbox',
        'priority' => 10,
    ));
}
add_action('customize_register', 'grafixpoint_customize_register');

/**
 * Sanitize checkbox values
 */
function grafixpoint_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Add dark mode toggle to header
 */
function grafixpoint_dark_mode_toggle() {
    echo '<div class="dark-mode-toggle">';
    echo '<span class="dark-mode-toggle-icon"></span>';
    echo '<span class="dark-mode-toggle-text">' . esc_html__('Toggle Dark Mode', 'grafixpoint-kadence-child') . '</span>';
    echo '</div>';
}
add_action('kadence_after_header', 'grafixpoint_dark_mode_toggle');

/**
 * Add AdSense code to content
 */
function grafixpoint_insert_adsense_in_content($content) {
    if (!is_single() || is_admin()) {
        return $content;
    }
    
    ob_start();
    dynamic_sidebar('adsense-content');
    $ad_code = ob_get_clean();
    
    if (empty($ad_code)) {
        return $content;
    }
    
    // Insert ad after the second paragraph
    $paragraphs = explode('</p>', $content);
    
    if (count($paragraphs) > 3) {
        $content_with_ads = '';
        
        for ($i = 0; $i < count($paragraphs); $i++) {
            $content_with_ads .= $paragraphs[$i] . '</p>';
            
            // Insert ad after the second paragraph
            if ($i === 1) {
                $content_with_ads .= $ad_code;
            }
        }
        
        return $content_with_ads;
    }
    
    return $content;
}
add_filter('the_content', 'grafixpoint_insert_adsense_in_content');
