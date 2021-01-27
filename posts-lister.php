<?php
/**
 * @package Posts Lister
 */
/*
Plugin Name: Posts Lister Plugin
Plugin URI: https://127.0.0.1/
Description: A test plugin for internship at Codewing Solutions
Version: 4.1.7
Author: Atul Khatri
Author URI: https://atulkhatri.com.np/
License: GPLv2 or later
Text Domain: posts-lister
*/

function posts_lister_activate()
{
    //activation
   
}

function posts_lister_deactivate()
{
    //deactivation
}

function posts_lister_enqueue_scripts()
{
    wp_enqueue_style('mypluginstyle','https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_style('mypluginstyle1', plugins_url('/style/style.css', __FILE__));
    wp_enqueue_script('mypluginscript', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js');
    wp_enqueue_script('mypluginscript1', plugins_url('/script/script.js', __FILE__));
}

function custom_excerpt_length() {
    return 15;
}

function return_posts_list($atts)
{   
    $content = '';
    $atts = shortcode_atts(array('num_of_posts'=>'15','layout'=>'grid', 'order_by'=>'date','order' => 'DESC'), $atts, 'posts_lister');

    //in asc or desc order
    $order = esc_attr($atts['order']);

    //order by this attribute
    $orderby = esc_attr($atts['order_by']);

    //number of posts to retrieve
    $num_of_posts = esc_attr($atts['num_of_posts']);

    //layout type
    //add list-view class if the layout isnt specified as grid
    $layout_type = strcmp(esc_attr($atts['layout']), 'grid')?'list-view':'';
   

    $args = array('post_type'=>'post','post_status' => 'publish','order'=>$order, 'orderby'=>$orderby);
    $query = new WP_Query($args);
    if($query->have_posts()) {
        $post_count = 0;
        while($query->have_posts() and $post_count < $num_of_posts) {
            //get relevant data from each post
            $query->the_post();
            $title = get_the_title();
            $excerpt = get_the_excerpt();
            $author = get_the_author();
            $published = get_the_date();
            $post_id = get_the_id();
            //placeholder image url
            $img_url = plugins_url("/assets/cat.jpg", __FILE__);

            $content .= "
                <div class = 'col-12 col-md col-lg-4'>
                    <div class = 'card'>
                    <img class = 'card-img-top' src = {$img_url} alt = 'Card image cap'>
                    <div class = 'card-body'>
                    <h5 class = 'card-title'>{$title}</h5>
                    <h6 class= 'card-subtitle'>by {$author}, on {$published} </h5>
                    <p class = 'card-text'>{$excerpt}</p>
                    </div>
                    </div>
                </div>
            ";
            //increment after each retrieved post
            $post_count++;
            
        }
    }
    $query->wp_reset_postdata();
    $output = "
    <div class = 'container grid-container {$layout_type}'>
    <div class = 'row'>
    {$content}
    </div>
    </div>";
    return $output;
}

//register activation and deactivation hooks
register_activation_hook(__FILE__, 'posts_lister_activate');
register_deactivation_hook(__FILE__, 'posts_lister_deactivate');

//register shortcode for grid
add_shortcode('posts_lister', 'return_posts_list' );

//register custom scripts and styles
add_action('wp_enqueue_scripts', 'posts_lister_enqueue_scripts');

//limit excerpt length to 20 words
add_filter('excerpt_length', 'custom_excerpt_length', 999);


