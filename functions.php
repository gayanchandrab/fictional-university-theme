<?php

function fictionaluniversity_styles() {
    wp_enqueue_style( 'googlefonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'fictionaluniversity', get_stylesheet_uri(), ['googlefonts', 'fontawesome'], microtime() );
}

add_action( 'wp_enqueue_scripts' , 'fictionaluniversity_styles' );

function fictionaluniversity_scripts() {
    wp_enqueue_script( 'googlemaps-js', '//maps.googleapis.com/maps/api/js?key=api-key-here', NULL, NULL, true);
    wp_enqueue_script( 'fictionaluniversity-js', get_template_directory_uri() . '/js/scripts-bundled.js', NULL, '1.0', true);
}

add_action( 'wp_enqueue_scripts' , 'fictionaluniversity_scripts' );

function fictionaluniversity_features(){
    register_nav_menu( 'headerMenuLocation' , 'Header Menu Location' );
    register_nav_menu( 'footerMenuLocationOne' , 'Footer Menu Location One' );
    register_nav_menu( 'footerMenuLocationTwo' , 'Footer Menu Location Two' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'professorLandscape', 400, 260, true);
    add_image_size( 'professorPortrait', 480, 650, true);
    add_image_size( 'pageBanner', 1500, 350, true);
}

add_action( 'after_setup_theme', 'fictionaluniversity_features' );

function fictionaluniversity_adjust_queries( $query ){

    if( !is_admin() && is_post_type_archive( 'campus' ) && $query->is_main_query() ){
         $query->set( 'posts_per_page', -1);
    }
    if( !is_admin() && is_post_type_archive( 'program' ) && $query->is_main_query() ){
        $query->set( 'orderby', 'title');
        $query->set( 'order', 'ASC');
        $query->set( 'posts_per_page', -1);
    }
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

function fictionaluniversity_post_types(){

    // Event post type
    register_post_type('event', array(
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'excerpt' ),
        'rewrite' => array( 'slug'=> 'events' ),
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));

    // Program post type
    register_post_type('program', array(
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor' ),
        'rewrite' => array( 'slug'=> 'programs' ),
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

    // Professor post type
    register_post_type( 'professor', array(
        'public' => true,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    // Campus post type
    register_post_type('campus', array(
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'excerpt' ),
        'rewrite' => array( 'slug'=> 'campuses' ),
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses'
        ),
        'menu_icon' => 'dashicons-location-alt'
    ));

}

add_action( 'init', 'fictionaluniversity_post_types' );


function pageBanner( $args = null ){

    if(!$args[ 'title' ]){
        $args[ 'title' ] = get_the_title();
    }

    if(!$args[ 'subtitle' ]){
        $args[ 'subtitle' ] = get_field( 'page_banner_subtitle' );
    }

    if(get_field( 'page_banner_background_image' )){
        $args[ 'photo' ] = get_field( 'page_banner_background_image' )[ 'sizes' ][ 'pageBanner' ];
    } else{
        $args[ 'photo' ]  = get_theme_file_uri( 'images/ocean.jpg' );
    }   

?>
    <div class="page-banner">
    <div class="page-banner__bg-image"
    style="background-image: url('<?php echo $args[ 'photo' ]; ?>');"></div>
    <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
    <div class="page-banner__intro">
    <p><?php echo $args['subtitle']; ?></p>
    </div>
    </div>
    </div>
<?php 
}

function universityMapKey( $api ){
	
	$api['key'] = 'api-key-here';
	
	return $api;
	
}

add_filter('acf/fields/google_map/api', 'universityMapKey');