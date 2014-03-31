<?php

//add_theme_support('post-formats');
//add_theme_support('post-thumbnails');
//add_theme_support('menus');
add_theme_support('html5', array('search-form')); 

// twig and timber filters
add_filter('get_twig', 'add_to_twig');
add_filter('timber_context', 'add_to_context');

// other filters
add_filter('get_search_form', 'll_search_form');

//add_action('wp_enqueue_scripts', 'load_scripts');

define('THEME_URL', get_template_directory_uri());



function add_to_context($context) {
    /* this is where you can add your own data to Timber's context object */
    //$data['menu'] = new TimberMenu();

    // always get the main sidebar
    // this may be overridden in views
    $context['sidebars'] = get_sidebars();

    return $context;
}


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
function get_sidebars() {
    $ids = array('sidebar-main', 'sidebar-single', 'footer-1', 'footer-2', 'footer-3');
    $sidebars = array();

    foreach ($ids as $sidebar) {
        $sidebars[$sidebar] = Timber::get_widgets($sidebar);
    }

    return $sidebars;
}


function ll_search_form($form) {
    $context = array('home_url' => home_url('/'));
    $form = Timber::compile('searchform.twig', $context);
    return $form;
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
