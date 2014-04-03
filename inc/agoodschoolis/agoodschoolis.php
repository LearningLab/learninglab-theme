<?php

class LL_Good_Schools {


    function __construct() {
        // set a base dir
        $this->DIR = THEME_URL . "/inc/agoodschoolis";

        // enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'add_scripts'));

        // admin
        add_action( 'admin_menu', array(&$this, 'admin_menu'));
        add_action( 'admin_init', array(&$this, 'admin_init'));

    }

    /***
    Enqueue js/agoodschoolis.js and dependencies into site header
    ***/
    function add_scripts() {
        // use hosted soundcloud
        wp_enqueue_script('soundcloud-widgets', 'https://w.soundcloud.com/player/api.js');
        wp_enqueue_script('soundcloud', 'http://connect.soundcloud.com/sdk.js');
        wp_enqueue_script('agoodschoolis', $this->DIR . '/js/agoodschoolis.js',
            array('soundcloud', 'soundcloud-widgets', 'jquery'));
    }

    /***
    Render an input field using input.twig template.

    Context:
        name: field name, should match option ID
        value: initial value, fetched before rendering
        type: input type, defaults to `text`
    ***/
    function render_field($args) {
        $args['value'] = get_option($args['name'], '');
        Timber::render('input.twig', $args);
    }

    /***
    Creates our settings section and fields, then registers settings.

    Every call to `add_settings_field` needs to pass an array of $args
    as its last argument. This gets used to render an input template.
    ***/
    function admin_init() {
        add_settings_section( 'soundcloud', '',
            array(&$this, 'settings_section'), 'soundcloud');
                
        add_settings_field('soundcloud_client_id', 'SoundCloud Client ID',
            array(&$this, 'render_field'), 'soundcloud', 'soundcloud', array(
                'name' => 'soundcloud_client_id'));

        add_settings_field('goodschools_playlist_id', 'A Good School Is Playlist ID',
            array(&$this, 'render_field'), 'soundcloud', 'soundcloud', array(
                'name' => 'goodschools_playlist_id'));

        register_setting('soundcloud', 'soundcloud_client_id');
        register_setting('soundcloud', 'goodschools_playlist_id');
        
    }

    /***
    Callback to create an options page
    ***/
    function admin_menu() {
        add_options_page('SoundCloud', 'SoundCloud', 'manage_options', 
                        'soundcloud', array(&$this, 'render_options_page'));
    }

    /***
    Render an options page, including the form.

        template: options_page.twig

    Context:

        title: Page title
        settings_section: call the `settings_fields` function with this argument
        settings_sections: call the `do_settings_sections` function with this argument

    ***/
    function render_options_page() {
        $context = array(
            'title' => 'SoundCloud Options',
            'settings_section' => 'soundcloud',
            'settings_field' => 'soundcloud'
        );

        Timber::render('options_page.twig', $context);
    }

    // no op function, because WordPress
    function settings_section() {}

}

new LL_Good_Schools();