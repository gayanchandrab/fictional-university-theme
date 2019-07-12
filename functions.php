<?php

function fictionaluniversity_styles() {
    wp_enqueue_style( 'googlefonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'fictionaluniversity', get_stylesheet_uri(), ['googlefonts', 'fontawesome'], microtime() );
}

add_action( 'wp_enqueue_scripts' , 'fictionaluniversity_styles' );

function fictionaluniversity_scripts() {
    wp_enqueue_script( 'fictionaluniversity-js', get_template_directory_uri() . '/js/scripts-bundled.js', NULL, microtime(), true);
}

add_action( 'wp_enqueue_scripts' , 'fictionaluniversity_scripts' );

function fictionaluniversity_features(){
    register_nav_menu( 'headerMenuLocation' , 'Header Menu Location' );
    register_nav_menu( 'footerMenuLocationOne' , 'Footer Menu Location One' );
    register_nav_menu( 'footerMenuLocationTwo' , 'Footer Menu Location Two' );
    add_theme_support( 'title-tag' );
}

add_action( 'after_setup_theme', 'fictionaluniversity_features' );

function fictionaluniversity_adjust_queries( $query ){
    if( !is_admin() && is_post_type_archive( 'event' ) && $query->is_main_query() ){
        $today = date( 'Ymd' );
        $query->set( 'meta_key', 'event_date');
        $query->set( 'orderby', 'meta_value_num');
        $query->set( 'order', 'ASC');
        $query->set( 'meta_query', array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              )
            )
        );
    }
}

add_action('pre_get_posts', 'fictionaluniversity_adjust_queries');


