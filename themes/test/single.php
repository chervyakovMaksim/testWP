<?php get_header();
?>
<div class="single_wrapper">
<?php
if ( have_posts() ) : 
    while ( have_posts() ) : the_post(); 
    the_title('<h1>', '</h1>'); 
    echo '<div class="post_content">';
    the_content(); 
    echo '</div>';

    echo '<div style="width: 600px; margin: 0px auto;">';
    echo '<p>Название дома: ' . get_field('house_name') . '</p>';
    echo '<p>Координаты: ' . get_field('location') . '</p>';
    echo '<p>Количество этажей: ' . get_field('number_of_floors') . '</p>';
    echo '<p>Тип строения: ' . get_field('building_type') . '</p>';
    echo '</div>';

    echo 'Тут шорткод';

    echo do_shortcode('[real_estate_filter]');

    endwhile; 
else :
    echo '<p>Что-то пошло не так. <a href="/">На главную</a></p>';
endif; 

?>
</div>


<?php get_footer(); ?>