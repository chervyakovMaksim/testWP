<?php
/*
Plugin Name: Real Estate Plugin
Description: Разработка плагина для тестового задания
Вывод шорткода -> do_shortcode('[real_estate_filter]');

на синг пейджах уже реализован вывод через шорткод
на главной странице - вывод через виджет


сохранилась проблема с выводом HTML в JSON формате виджета в админке

В целом виджет просто использует разработанный шорткод

Version: 1.0
Author: Maksim Chervyakov
*/
function real_estate_block_render_callback( $attributes, $content ) {
    return real_estate_render_shortcode();
}
function real_estate_register_block() {
    wp_register_script(
        'real-estate-block-editor',
        plugins_url( 'build/index.js', __FILE__ ),
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )
    );
    register_block_type( 'real-estate/filter', array(
        'editor_script' => 'real-estate-block-editor',
        'render_callback' => 'real_estate_block_render_callback',
    ) );
}

add_action( 'init', 'real_estate_register_block' );




function real_estate_enqueue_scripts() {
    wp_register_script('real-estate-common', plugin_dir_url(__FILE__) . 'common.js', array('jquery'), null, true);
    wp_localize_script('real-estate-common', 'realEstateFilter', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('real_estate_filter_nonce')
    ));
    wp_enqueue_script('real-estate-common');
}
add_action('wp_enqueue_scripts', 'real_estate_enqueue_scripts');




function register_district_taxonomy() {
    register_taxonomy( 'district', [ 'real_estate' ], [
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => [
			'name'              => 'Районы',
			'singular_name'     => 'Район',
			'search_items'      => 'Искать районы',
			'all_items'         => 'Все районы',
			'view_item '        => 'Просмотр района',
			'parent_item'       => 'Parent Genre',
			'parent_item_colon' => 'Parent Genre:',
			'edit_item'         => 'Редактировать район',
			'update_item'       => 'Обновить район',
			'add_new_item'      => 'Добавить новый район',
			'new_item_name'     => 'New Genre Name',
			'menu_name'         => 'Район',
			'back_to_items'     => '← Вернуться к районам',
		],
		'description'           => '', // описание таксономии
		'public'                => true,
		// 'publicly_queryable'    => null, // равен аргументу public
		// 'show_in_nav_menus'     => true, // равен аргументу public
		// 'show_ui'               => true, // равен аргументу public
		// 'show_in_menu'          => true, // равен аргументу show_ui
		// 'show_tagcloud'         => true, // равен аргументу show_ui
		// 'show_in_quick_edit'    => null, // равен аргументу show_ui
		'hierarchical'          => true,

		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy
		// '_builtin'              => false,
		//'update_count_callback' => '_update_post_term_count',
	] );
}

function register_real_estate_post_type() {
    register_post_type( 'real_estate', [
		'label'  => null,
		'labels' => [
			'name'               => 'Объекты недвижимости', // основное название для типа записи
			'singular_name'      => 'Объект недвижимости', // название для одной записи этого типа
			'add_new'            => 'Добавить объект недвижимости', // для добавления новой записи
			'add_new_item'       => 'Добавление объекта недвижимости', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование объекта недвижимости', // для редактирования типа записи
			'new_item'           => 'Новый объект недвижимости', // текст новой записи
			'view_item'          => 'Смотреть объект недвижимости', // для просмотра записи этого типа.
			'search_items'       => 'Искать объект недвижимости', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Объекты недвижимости', // название меню
		],
		'description'            => '',
		'public'                 => true,
		// 'publicly_queryable'  => null, // зависит от public
		// 'exclude_from_search' => null, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'           => null, // показывать ли в меню админки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => null,
		'menu_icon'           => null,
		//'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => ['district'],
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}



add_action('init', 'register_district_taxonomy');
add_action('init', 'register_real_estate_post_type');





function render_real_estate_filter_form() {
    $districts = get_terms(array(
        'taxonomy'   => 'district',
        'hide_empty' => false,
    ));

    $form_html = '<form id="real-estate-filter-form">';

    $form_html .= '<label for="district">Район:</label>';
    $form_html .= '<select name="district" id="district">';
    $form_html .= '<option value="">Выберите район</option>';
    foreach ($districts as $district) {
        $form_html .= sprintf('<option value="%s">%s</option>', $district->slug, $district->name);
    }
    $form_html .= '</select><br>';

    $form_html .= '<label for="house_name">Название дома:</label>';
    $form_html .= '<input type="text" name="house_name" id="house_name"><br>';

    $form_html .= '<label for="floors">Количество этажей:</label>';
    $form_html .= '<input type="number" name="floors" id="floors" min="1" max="20"><br>';

    $form_html .= '<label for="building_type">Тип строения:</label>';
    $form_html .= '<select name="building_type" id="building_type">';
    $form_html .= '<option value="">Выберите тип</option>';
    $form_html .= '<option value="панель">Панель</option>';
    $form_html .= '<option value="кирпич">Кирпич</option>';
    $form_html .= '<option value="пеноблок">Пеноблок</option>';
    $form_html .= '</select><br>';


    $form_html .= '<input type="submit" value="Фильтровать">';
    $form_html .= '</form>';
    
    $form_html .= '<div id="real-estate-filter-results"></div>';

    return $form_html;
}
function real_estate_filter_shortcode() {
    return render_real_estate_filter_form();
}
add_shortcode('real_estate_filter', 'real_estate_filter_shortcode');


// function real_estate_render_shortcode() {
//     ob_start(); // Включаем буферизацию вывода
//     echo do_shortcode('[real_estate_filter]');
//     return ob_get_clean(); // Получаем содержимое буфера и очищаем его
// }
function real_estate_render_shortcode() {
    return do_shortcode('[real_estate_filter]');
}


add_action('rest_api_init', function () {
    register_rest_route('real-estate/v1', '/shortcode', array(
        'methods' => 'GET',
        'callback' => 'real_estate_render_shortcode',
        'permission_callback' => '__return_true' // Убедитесь, что REST API доступен для всех пользователей
    ));
});








function real_estate_ajax_filter() {
    check_ajax_referer('real_estate_filter_nonce', 'nonce');

    $args = array(
        'post_type' => 'real_estate',
        'posts_per_page' => 3, // количество выводимых постов на страницу
        'paged' => isset($_POST['page']) ? $_POST['page'] : 1, // номер страницы
        'meta_query' => array(
            'relation' => 'AND',
        )
    );

    // Фильтрация по таксономии district
    if (!empty($_POST['district'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'district',
                'field' => 'slug',
                'terms' => $_POST['district']
            )
        );
    }

    if (!empty($_POST['house_name'])) {
        $args['meta_query'][] = array(
            'key' => 'house_name', 
            'value' => sanitize_text_field($_POST['house_name']),
            'compare' => 'LIKE' 
        );
    }

    
    if (!empty($_POST['floors'])) {
        $args['meta_query'][] = array(
            'key' => 'number_of_floors',
            'value' => intval($_POST['floors']),
            'compare' => '='
        );
    }

    if (!empty($_POST['building_type'])) {
        $args['meta_query'][] = array(
            'key' => 'building_type', 
            'value' => sanitize_text_field($_POST['building_type']),
            'compare' => '='
        );
    }


    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<a style="color: black; text-decoration: none;" href="' . esc_url(get_permalink()) . '">';
            echo '<h3>' . get_the_title() . '</h3>';
            echo 'Название дома -' . get_field('house_name') . '<br>';
            echo 'Координаты местонахождения -' . get_field('location') . '<br>';
            echo 'Количество этажей -' . get_field('number_of_floors') . '<br>';
            echo 'Тип строения -' .  get_field('building_type') . '<br>';
            $district_terms = get_the_terms(get_the_ID(), 'district');
            if ($district_terms && !is_wp_error($district_terms)) {
                $district_names = wp_list_pluck($district_terms, 'name');
                echo 'Район: ' . implode(', ', $district_names) . '<br>';
            }
            echo '<hr></a>';
        }
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            // $current_page = max(1, get_query_var('paged'));
            $current_page = isset($_POST['page']) ? $_POST['page'] : 1;
            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                'current' => $current_page,
                'total' => $total_pages,
            ));
        }

    } else {
        echo 'Объекты недвижимости не найдены';
    }

    wp_die();
}

add_action('wp_ajax_real_estate_filter', 'real_estate_ajax_filter'); 
add_action('wp_ajax_nopriv_real_estate_filter', 'real_estate_ajax_filter'); 
