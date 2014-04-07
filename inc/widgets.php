<?php

/***
Callback to register (and unregister) widgets.
Classes are defined inside the callback because they extend Largo widgets,
which are defined late in the load cycle.
***/
function ll_widgets() {

    // unregister and replace these widgets
    $largo = array(
        'largo_about_widget'                => 'LL_About_Widget',
        null                                => 'LL_RSS_Widget',
        'largo_follow_widget'               => 'LL_Follow_Widget',
        'largo_recent_posts_widget'         => 'LL_Recent_Posts_Widget',
        //'largo_donate_widget'             => '/inc/widgets/largo-donate.php',
        //'largo_facebook_widget'           => '/inc/widgets/largo-facebook.php',
        //'largo_footer_featured_widget'    => '/inc/widgets/largo-footer-featured.php',
        //'largo_image_widget'              => '/inc/widgets/largo-image-widget.php',
        //'largo_INN_RSS_widget'            => '/inc/widgets/largo-inn-rss.php',
        //'largo_recent_comments_widget'    => '/inc/widgets/largo-recent-comments.php',
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
            $args['site'] = new TimberSite();
            //$args['logo'] = of_get_option('favicon');

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
            $args['site'] = new TimberSite();

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
            $args['site'] = new TimberSite();

            $args['feed_link'] = of_get_option('rss_link', get_feed_link());
            $args['twitter_link'] = of_get_option('twitter_link');
            $args['facebook_link'] = of_get_option('facebook_link');
            $args['linkedin_link'] = of_get_option('linkedin_link');

            Timber::render($templates, $args);
        }
    }

    class LL_Recent_Posts_Widget extends largo_recent_posts_widget {

        function widget($args, $instance) {
            $templates = array('widgets/recent-posts-widget.twig', 'widgets/widget.twig');

            $args['instance'] = $instance;
            $args['site'] = new TimberSite();
            $args['posts_term'] = of_get_option( 'posts_term_plural', 'Posts' );
            $args['posts'] = $this->get_posts($instance);

            Timber::render($templates, $args);
        }

        /***
        Return a posts query for inclusion in widget template
        ***/
        function get_posts($instance) {
            global $ids; // an array of post IDs already on a page so we can avoid duplicating posts

            $query_args = array (
                'post__not_in'  => get_option( 'sticky_posts' ),
                'showposts'     => $instance['num_posts'],
                'post_status'   => 'publish'
            );
            
            if ( isset( $instance['avoid_duplicates'] ) && $instance['avoid_duplicates'] === 1)
                $query_args['post__not_in'] = $ids;
            
            if ($instance['cat'] != '')
                $query_args['cat'] = $instance['cat'];
            
            if ($instance['tag'] != '')
                $query_args['tag'] = $instance['tag'];
            
            if ($instance['author'] != '')
                $query_args['author'] = $instance['author'];
            
            if ($instance['taxonomy'] != '')
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => $instance['taxonomy'],
                        'field' => 'slug',
                        'terms' => $instance['term']
                    )
                );

            return Timber::get_posts($query_args);

        }
    }

    foreach ($largo as $key => $value) {
        unregister_widget($key);
        register_widget($value);
    }

}

add_action( 'widgets_init', 'll_widgets', 15 );
