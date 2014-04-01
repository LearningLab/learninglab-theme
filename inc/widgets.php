<?php

function ll_widgets() {

	// reuse one site instance
	$site = new TimberSite();

	// unregister and replace these widgets
	$largo = array(
		'largo_about_widget'			=> 'LL_About_Widget',
		//'largo_donate_widget'			=> '/inc/widgets/largo-donate.php',
		//'largo_facebook_widget'			=> '/inc/widgets/largo-facebook.php',
		//'largo_follow_widget' 			=> '/inc/widgets/largo-follow.php',
		//'largo_footer_featured_widget'	=> '/inc/widgets/largo-footer-featured.php',
		//'largo_image_widget'			=> '/inc/widgets/largo-image-widget.php',
		//'largo_INN_RSS_widget'			=> '/inc/widgets/largo-inn-rss.php',
		//'largo_recent_comments_widget'	=> '/inc/widgets/largo-recent-comments.php',
		//'largo_recent_posts_widget'		=> '/inc/widgets/largo-recent-posts.php',
		//'largo_sidebar_featured_widget'	=> '/inc/widgets/largo-sidebar-featured.php',
		//'largo_taxonomy_list_widget'	=> '/inc/widgets/largo-taxonomy-list.php',
		//'largo_twitter_widget'			=> '/inc/widgets/largo-twitter.php'
	);

	class LL_About_Widget extends largo_about_widget {

		function __construct() {
			$widget_ops = array(
				'classname' 	=> 'largo-about ll-about',
				'description'	=> __('Show the site description from your theme options page', 'largo')
			);
			parent::__construct( 'll_about_widget', __('About Site', 'largo'), $widget_ops);
		}

		function widget($args, $instance) {
			$templates = array('about-widget.twig', 'widget.twig');
			
			// use args for context
			$args['instance'] = $instance;
			$args['site'] = $site;

			Timber::render($templates, $args);
		}
	}

	foreach ($largo as $key => $value) {
		unregister_widget($key);
		register_widget($value);
	}

}

add_action( 'widgets_init', 'll_widgets', 15 );
