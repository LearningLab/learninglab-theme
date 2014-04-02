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
    Enqueue js/agoodschoolis.js into site footer
    ***/
    function add_scripts() {
        // use hosted soundcloud
        wp_enqueue_script('soundcloud-widgets', 'https://w.soundcloud.com/player/api.js');
        wp_enqueue_script('soundcloud', 'http://connect.soundcloud.com/sdk.js');
        wp_enqueue_script('agoodschoolis', $this->DIR . '/js/agoodschoolis.js',
            array('soundcloud', 'soundcloud-widgets', 'jquery'));
    }

    function render_field($args) {
        $args['value'] = get_option($args['name'], '');
        Timber::render('input.twig', $args);
    }

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

    function admin_menu() {
        add_options_page('SoundCloud', 'SoundCloud', 'manage_options', 
                        'soundcloud', array(&$this, 'render_options_page'));
    }

    function render_options_page() {
        $context = array(
            'title' => 'SoundCloud Options',
            'settings_section' => 'soundcloud',
            'settings_field' => 'soundcloud'
        );

        Timber::render('options_page.twig', $context);
    }

    function settings_section() {}

}

new LL_Good_Schools();