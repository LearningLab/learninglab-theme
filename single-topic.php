<?php
/**
 * The Template for displaying a topic page
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$context['topic'] = new Topic();
$context['is_single'] = true;

$templates = array('single-' . $post->post_type . '.twig', 'single.twig');

Timber::render($templates, $context);
