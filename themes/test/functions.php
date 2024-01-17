<?php

add_theme_support( 'widgets' );


function test_register_sidebar() {
    register_sidebar( array(
        'name'          => 'Real Estate Sidebar',
        'id'            => 'real-estate-sidebar',
        'description'   => 'Sidebar for the real estate filter widget',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'test_register_sidebar' );


// add_theme_support( 'post-thumbnails' );
