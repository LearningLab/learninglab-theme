<?php

function ll_widgets() {

    // reuse one site instance
    $site = new TimberSite();

    // unregister and replace these widgets
    $largo = array(
        'largo_about_widget'                => 'LL_About_Widget',
        null                                => 'LL_RSS_Widget',
        'largo_follow_widget'               => 'LL_Follow_Widget',
        //'largo_donate_widget'             => '/inc/widgets/largo-donate.php',
        //'largo_facebook_widget'           => '/inc/widgets/largo-facebook.php',
        //'largo_footer_featured_widget'    => '/inc/widgets/largo-footer-featured.php',
        //'largo_image_widget'              => '/inc/widgets/largo-image-widget.php',
        //'largo_INN_RSS_widget'            => '/inc/widgets/largo-inn-rss.php',
        //'largo_recent_comments_widget'    => '/inc/widgets/largo-recent-comments.php',
        //'largo_recent_posts_widget'       => '/inc/widgets/largo-recent-posts.php',
        //'largo_sidebar_featured_widget'   => '/inc/widgets/largo-sidebar-featured.php',
        //'largo_taxonomy_list_widget'      => '/inc/widgets/largo-taxonomy-list.php',
        //'largo_twitter_widget'            => '/inc/widgets/largo-twitter.php'
    );

    class LL_About_Widget extends largo_about_widget {

        function __construct() {
            $options = array(
                'classname'     => 'largo-about ll-about',
                'description'   => __('Show the site description from your theme options page', 'largo')
            );
            parent::__construct( 'll_about_widget', __('About Site', 'largo'), $options);
        }

        function widget($args, $instance) {
            $templates = array('widgets/about-widget.twig', 'widgets/widget.twig');
            
            // use args for context
            $args['instance'] = $instance;
            $args['site'] = $site;

            Timber::render($templates, $args);
        }
    }

    class LL_RSS_Widget extends WP_Widget_RSS {

        function __construct() {
            $options = array('classname' => 'll-rss rss-widget');
            parent::__construct('ll_rss_widget', __('RSS', 'largo'), $options);
        }

        function widget($args, $instance) {
            $templates = array('widgets/rss-widget.twig', 'widgets/widget.twig');

            $args['instance'] = $instance;
            $args['site'] = $site;

            if (isset($instance['url'])) {
                // if we have a url, fetch the feed
                $args['feed'] = fetch_feed($instance['url']);
            }

            Timber::render($templates, $args);
        }
    }

    class LL_Follow_Widget extends largo_follow_widget {

        function widget($args, $instance) {
            $templates = array('widgets/follow-widget.twig', 'widgets/widget.twig');

            $args['instance'] = $instance;
            $args['site'] = $site;

            $args['feed_link'] = of_get_option('rss_link', get_feed_link());
            $args['twitter_link'] = of_get_option('twitter_link');
            $args['facebook_link'] = of_get_option('facebook_link');
            $args['linkedin_link'] = of_get_option('linkedin_link');

            Timber::render($templates, $args);
        }
    }

    foreach ($largo as $key => $value) {
        unregister_widget($key);
        register_widget($value);
    }

}

add_action( 'widgets_init', 'll_widgets', 15 );
