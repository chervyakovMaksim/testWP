<?php
/* Template Name: Home Page */

$url = get_template_directory_uri();

get_header();
?>
<?php if ( is_active_sidebar( 'real-estate-sidebar' ) ) : ?>
    <div id="sidebar">
        <?php dynamic_sidebar( 'real-estate-sidebar' ); ?>
    </div>
<?php endif; ?>


<?php get_footer(); ?>