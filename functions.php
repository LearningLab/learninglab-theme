<?php

define('THEME_URL', get_stylesheet_directory_uri());
define('COMPONENTS', THEME_URL . '/bower_components');

// all our includes
$includes = array(
    '/inc/agoodschoolis/agoodschoolis.php',
    '/inc/widgets.php',
);

// include all the includes
foreach ( $includes as $include ) {
    require_once( get_stylesheet_directory() . $include );
}

add_theme_support('html5', array('search-form')); 

// twig and timber filters
add_filter('timber_context', 'add_to_context');
function add_to_context($context) {
    /* this is where you can add your own data to Timber's context object */

    // always get the main sidebar
    // this may be overridden in views
    $context['sidebars'] = get_largo_sidebars();
    $context['menus'] = get_largo_menus();

    return $context;
}

add_filter('get_twig', 'add_to_twig');
function add_to_twig($twig) {
    /* this is where you can add your own fuctions to twig */
    $twig->addExtension(new Twig_Extension_StringLoader());

    return $twig;
}


function load_scripts() {
    wp_enqueue_script('jquery');
}


/***
Return an array of Timber sidebars for all Largo sidebars
***/
function get_largo_sidebars() {
    $ids = array('sidebar-main', 'sidebar-single', 'footer-1', 'footer-2', 'footer-3');
    $sidebars = array();

    foreach ($ids as $sidebar) {
        $sidebars[$sidebar] = Timber::get_widgets($sidebar);
    }

    return $sidebars;
}

/***
Get all of Largo's menus, same as we do with sidebars
***/
function get_largo_menus() {
    // copied from Largo/inc/nav-menus.php
    $menus = array(
        'global-nav'            => __( 'Global Navigation', 'largo' ),
        'navbar-categories'     => __( 'Navbar Categories List', 'largo' ),
        'navbar-supplemental'   => __( 'Navbar Supplemental Links', 'largo' ),
        'dont-miss'             => __( 'Don\'t Miss', 'largo' ),
        'footer'                => __( 'Footer Navigation', 'largo' ),
        'footer-bottom'         => __( 'Footer Bottom', 'largo' )
    );

    foreach ($menus as $id => $name) {
        $menus[$id] = new TimberMenu($id);
    }

    return $menus;
}

/***
Override the default search form with a proper template
***/
add_filter('get_search_form', 'll_search_form');
function ll_search_form($form) {
    $context = array('site' => new TimberSite());
    $form = Timber::compile('searchform.twig', $context);
    return $form;
}

/***
Remove caption inline width

    $caption_width = apply_filters( 'img_caption_shortcode_width', $caption_width, $atts, $content );

***/
add_filter('img_caption_shortcode_width', 'no_caption_width');
function no_caption_width() {
    return 0;
}

/***
Turn off Largo frontend pieces (some may be turned back on later)

Largo checks that functions are undefined, so defining a no-op function
here will effectively disable that action. We can also remove actions.
***/

// enqueues both js and css
function largo_enqueue_js() {}

// outputs the site header, which is what templates are for
function largo_header() {}
