<?php
/**
 * Основной функционал темы
 */

 // ========================
// 1. Основные настройки темы
// ========================
add_theme_support('post-thumbnails');

// ========================
// Регистрация иконок
// ========================
function my_theme_customize_register( $wp_customize ) {
    // Добавляем секцию для соцсетей
    $wp_customize->add_section( 'social_media_section', array(
        'title'    => __('Социальные сети', 'my-theme'),
        'priority' => 20,
    ));

    // Настройки для каждой соцсети
    $social_networks = array('instagram', 'whatsapp', 'twitter', 'mail', 'facebook', 'telegram', );
    
    foreach ($social_networks as $network) {
        $wp_customize->add_setting( $network . '_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control( $network . '_url', array(
            'label'    => sprintf( __('%s URL', 'my-theme'), ucfirst($network) ),
            'section'  => 'social_media_section',
            'type'     => 'url',
        ));
    }
}
add_action( 'customize_register', 'my_theme_customize_register' );

function display_social_icons() {
    $social_networks = array(
        'facebook'  => 'facebook',
        'twitter'   => 'twitter',
        'instagram' => 'instagram',
        'telegram'  => 'telegram',
        'whatsapp'  => 'whatsapp',
        'mail'      => 'mail',
        'form'      => 'form' // Новая иконка для формы
    );
    
    echo '<div class="social-icons">';
    foreach ($social_networks as $network => $icon_id) {
        $url = get_theme_mod($network . '_url');
        if ($url || $network === 'form') {
            if ($network === 'form') {
                // Для формы используем вызов openPopup вместо ссылки
                echo '<a href="#" class="social-icon-link" onclick="openPopup(\'\', \'form\'); return false;">';
								echo '<svg data-no-optimize="1" width="100" height="100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="social-icon" aria-label="' . esc_attr($network) . '">';
								echo '<rect x="1.5" y="1.5" width="97" height="97" rx="48.5" stroke="white" stroke-width="3"/>
											<path d="M50.0002 21.5C66.8693 21.5001 80.0276 32.7123 80.0276 45.9639C80.0275 52.3865 76.9788 58.284 71.8899 62.7002L71.3723 63.1484V63.833C71.3723 65.7185 71.373 69.5806 71.2961 73.0459C71.2577 74.7815 71.1999 76.3967 71.1155 77.6074C71.1062 77.7408 71.093 77.8678 71.0833 77.9883C70.9676 77.9302 70.8448 77.8679 70.7161 77.8008C69.5329 77.1843 67.9623 76.274 66.3118 75.2695C62.9888 73.2472 59.506 70.9443 58.3499 70.0283L57.8059 69.5977L57.1262 69.7334C54.8437 70.1867 52.4573 70.4267 50.0002 70.4268C33.1312 70.4268 19.9731 59.2153 19.9729 45.9639C19.9729 32.7123 33.1311 21.5 50.0002 21.5Z" stroke="white" stroke-width="3"/>
											<circle cx="37.62" cy="46.1088" r="3.0909" fill="white"/>
											<circle cx="49.9835" cy="46.1088" r="3.0909" fill="white"/>
											<circle cx="62.3472" cy="46.1088" r="3.0909" fill="white"/>';
								echo '</svg>';
            } else {
                // Обычные соцсети
                echo '<a href="' . esc_url($url) . '" class="social-icon-link" target="_blank" rel="noopener noreferrer">';
								echo '<svg data-no-optimize="1" class="social-icon" aria-label="' . esc_attr($network) . '">';
								echo '<use  href="' . get_template_directory_uri() . '/assets/img/icons.svg#' . esc_attr($icon_id) . '"></use>';
								echo '</svg>';
            }
            echo '</a>';
        }
    }
    echo '</div>';
}


// ========================
// 2. Регистрация меню
// ========================
function silesto_register_nav_menu() {
    register_nav_menu('headermenu', 'Меню в шапке');
}
add_action('after_setup_theme', 'silesto_register_nav_menu');

// ========================
// 3. Загрузка стилей и скриптов
// ========================
function custom_admin_style() {
    wp_enqueue_style('custom-admin-style', get_template_directory_uri() . '/admin-style.css');
}
add_action('admin_enqueue_scripts', 'custom_admin_style', 99);
function add_google_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">
          <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
          <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&family=Rozha+One&family=Sacramento&display=swap" rel="stylesheet">';
}
add_action('wp_head', 'add_google_fonts', 5);

function silesto_scripts_method() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('silesto-style', get_template_directory_uri() . '/assets/css/main.css', array(), filemtime(get_template_directory() . '/assets/css/main.css'));
    wp_enqueue_script(
        'silesto-script', 
        get_template_directory_uri() . '/assets/js/app.js', 
        ['jquery'], 
        filemtime(get_template_directory() . '/assets/js/app.js'), 
        true
    );
    
    wp_localize_script('silesto-script', 'silesto_ajax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('silesto-ajax-nonce')
    ]);
		wp_localize_script('silesto-script', 'paymentSettings', [
				'paymentMethod' => get_theme_mod('payment_method', 'paguello')
		]);
}
add_action('wp_enqueue_scripts', 'silesto_scripts_method');


function add_popup_script_to_footer() {
    // Проверяем, что это главная страница
    if (is_front_page()) {
        $post_id = 6031;
        $on_off = get_field('on_off', $post_id);
        if ($on_off) {
            $banner_image = get_field('banner_image', $post_id);
            $banner_link = get_field('banner_link', $post_id);
            
            if ($banner_image && $banner_link) {
                echo '<script>
                    const popupShowCount = localStorage.getItem("popupShowCount") || 0;
                    const maxShowCount = 3; 
                    if (parseInt(popupShowCount) >= maxShowCount) {
                        console.log(`${popupShowCount} показа`)
                    } else {
                        console.log(`Показ №${popupShowCount}`)
                        document.addEventListener("DOMContentLoaded", function() {
                            setTimeout(function () {
                                openPopup({
                                    img: "' . esc_url($banner_image) . '",
                                    link: "' . esc_url($banner_link) . '"
                                }, "banner");
                            }, 5000);
                        });
                    }
                </script>';
            }
        }
    }
}
add_action('wp_footer', 'add_popup_script_to_footer');

// ========================
// 4. Исправление ошибки загрузки переводов для ihc
// ========================
function fix_ihc_translation_loading() {
    if (function_exists('load_plugin_textdomain')) {
        load_plugin_textdomain('ihc', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
}
add_action('init', 'fix_ihc_translation_loading', 1);

// ========================
// 5. Колонки в админке
// ========================
// Колонка с превью
function add_thumbnail_column($columns) {
    $columns['thumbnail'] = 'Превью';
    return $columns;
}
add_filter('manage_posts_columns', 'add_thumbnail_column');
add_filter('manage_pages_columns', 'add_thumbnail_column');

function fill_thumbnail_column($column, $post_id) {
    if ($column === 'thumbnail') {
        echo get_the_post_thumbnail($post_id, [50, 50]) ?: '—';
    }
}
add_action('manage_posts_custom_column', 'fill_thumbnail_column', 10, 2);
add_action('manage_pages_custom_column', 'fill_thumbnail_column', 10, 2);

// Колонка с уровнем подписки для видео
add_filter('manage_video_posts_columns', 'add_subscription_level_column');
function add_subscription_level_column($columns) {
    $columns['subscription_level'] = 'Уровень подписки';
    return $columns;
}

add_action('manage_video_posts_custom_column', 'fill_subscription_level_column', 10, 2);
function fill_subscription_level_column($column, $post_id) {
    if ($column === 'subscription_level') {
        $level = get_field('subscription_level', $post_id);
        echo $level ?: '—';
    }
}

// Сортировка колонки с уровнем подписки
add_filter('manage_edit-video_sortable_columns', 'make_subscription_level_sortable');
function make_subscription_level_sortable($columns) {
    $columns['subscription_level'] = 'subscription_level';
    return $columns;
}

add_action('pre_get_posts', 'subscription_level_column_orderby');
function subscription_level_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    
    if ($query->get('orderby') === 'subscription_level') {
        $query->set('meta_key', 'subscription_level');
        $query->set('orderby', 'meta_value_num');
    }
}

// Quick Edit для уровня подписки
add_action('quick_edit_custom_box', 'add_subscription_level_to_quick_edit', 10, 2);
function add_subscription_level_to_quick_edit($column_name, $post_type) {
    if ($post_type === 'video' && $column_name === 'subscription_level') {
        ?>
<fieldset class="inline-edit-col-right">
    <div class="inline-edit-col">
        <label>
            <span class="title">Membership ID</span>
            <input type="number" name="subscription_level" value="">
        </label>
    </div>
</fieldset>
<?php
    }
}

add_action('save_post', 'save_subscription_level_from_quick_edit');
function save_subscription_level_from_quick_edit($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['subscription_level'])) {
        update_field('subscription_level', $_POST['subscription_level'], $post_id);
    }
}

add_action('admin_footer', 'subscription_level_quick_edit_js');
function subscription_level_quick_edit_js() {
    global $post_type;
    if ($post_type === 'video') {
        ?>
<script type="text/javascript">
jQuery(function($) {
    var $wp_inline_edit = inlineEditPost.edit;
    inlineEditPost.edit = function(id) {
        $wp_inline_edit.apply(this, arguments);
        var post_id = 0;
        if (typeof(id) == 'object') {
            post_id = parseInt(this.getId(id));
        }
        if (post_id > 0) {
            var $edit_row = $('#edit-' + post_id);
            var $post_row = $('#post-' + post_id);
            var subscription_level = $post_row.find('.column-subscription_level').text().trim();
            $edit_row.find('input[name="subscription_level"]').val(subscription_level === '—' ? '' :
                subscription_level);
        }
    };
});
</script>
<?php
    }
}

// ========================
// 6. Удаление стандартных записей
// ========================
function remove_default_post_type() {
    remove_menu_page('edit.php');
}
add_action('admin_menu', 'remove_default_post_type');

// ========================
// 7. AJAX обработчики
// ========================
// Загрузка видео
// --- НАЧАЛО ФИНАЛЬНОЙ ВЕРСИИ ФУНКЦИИ ---

function load_videos_callback() {
    check_ajax_referer('silesto-ajax-nonce', 'security');

    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'main';
    $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $args = [
        'post_type' => 'video',
        'posts_per_page' => 9,
        'paged' => $page,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => [
            [
                'taxonomy' => 'video-cat',
                'field' => 'slug',
                'terms' => $category
            ]
        ]
    ];
    if (!empty($search_term)) {
        $args['s'] = $search_term;
        $args['posts_per_page'] = -1;
    }
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();

        $is_vip_user = false;
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (in_array('vip_user', (array) $user->roles)) {
                $is_vip_user = true;
            }
        }

        while ($query->have_posts()) : $query->the_post();
            $post_id = get_the_ID();
            $trailer_url = get_field('trailer_url', $post_id);
            $post_link = get_permalink($post_id);
            $video_title = get_the_title();
            $paguello = get_field('paguello', $post_id);
            $stripe = get_field('stripe', $post_id);
            $trailer_tg = get_field('trailer_tg', $post_id);
            $subscription_level = get_field('subscription_level', $post_id);

            // --- ИЗМЕНЕНИЕ ЗДЕСЬ: УСЛОВНОЕ ПОЛУЧЕНИЕ ЦЕНЫ ---
            $price = 0; // Цена по умолчанию
            if ($is_vip_user) {
                // Логика для VIP: получаем цену из уровня UMP
                if ($subscription_level && function_exists('ihc_get_level_by_id')) {
                    $level_data = ihc_get_level_by_id($subscription_level);
                    $price = isset($level_data['price']) ? $level_data['price'] : 0;
                }
            } else {
                // Логика для НЕ-VIP и гостей: получаем цену из поля ACF
                $price = get_field('price', $post_id);
            }
            // --- КОНЕЦ ИЗМЕНЕНИЯ ---


            $user_id = get_current_user_id();
            $has_access = false;
            if (function_exists('ihc_user_has_level')) {
                $has_access = ihc_user_has_level($user_id, $subscription_level);
            }
            ?>
<div class="video-item conteiner">
    <?php if (has_post_thumbnail()) : ?>
    <a href="<?php echo esc_url($post_link); ?>">
        <div class="video-thumbnail">
            <?php the_post_thumbnail('full'); ?>
        </div>
    </a>
    <?php endif; ?>
    <div class="title-container">
        <h3><?php echo esc_html($video_title); ?></h3>
        <a class="button more_btn" href="<?php echo esc_url($post_link); ?>">
            More info
            <svg class="social-icon" viewBox="0 0 15 15" width="15" height="15">
                <use href="<?php echo get_template_directory_uri() . '/assets/img/icons.svg#arrow-right' ?>" x="0"
                    y="0"></use>
            </svg>
        </a>
    </div>
    <div class="video-buttons">
        <?php if ($trailer_tg) : ?>
        <a href="<?php echo esc_url($trailer_tg); ?>" target="_blank" class="button trailer-btn blue-color">Trailer</a>
        <?php elseif ($trailer_url) : ?>
        <a href="#" data-trailer="<?php echo esc_url($trailer_url); ?>"
            class="button trailer-btn blue-color">Trailer</a>
        <?php else : ?>
        <a href="#" class="button grey-color">No Trailer</a>
        <?php endif; ?>

        <?php if ($has_access) : ?>
        <a href="<?php echo esc_url($post_link); ?>" class="button grey-color">WATCH</a>
        <?php else : ?>
        <?php if ( $is_vip_user ) : ?>
        <a href="#" class="button button_buy_with_balance red-color" data-video-id="<?php echo esc_attr($post_id); ?>"
            data-video-price="<?php echo esc_attr($price); ?>" data-post-link="<?php echo esc_url($post_link); ?>">
            Buy for $<?php echo esc_attr($price); ?>
        </a>
        <?php else : ?>
        <div class="buy_btns">
            <a href="#" class="button button_buy red-color" data-name="<?php echo esc_attr($video_title); ?>"
                data-paguello="<?php echo esc_attr($paguello); ?>" data-stripe="<?php echo esc_attr($stripe); ?>"
                data-price="<?php echo esc_attr($price); ?>">
                Buy Now
            </a>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if (!$has_access && !$is_vip_user) : ?>
    <p class="payment-notice">
        Can't pay? Use another
        <a href="#pay-s" class="payment-link" data-name="<?php echo esc_attr($video_title); ?>"
            data-paguello="<?php echo esc_attr($paguello); ?>" data-stripe="<?php echo esc_attr($stripe); ?>">
            payment way by card
        </a>
    </p>
    <?php endif; ?>
</div>
<?php
        endwhile;

        $html = ob_get_clean();

        wp_send_json_success([
            'html' => $html,
            'has_more' => $query->max_num_pages > $page
        ]);
    } else {
        wp_send_json_error('No videos found');
    }

    wp_reset_postdata();
    wp_die();
}

// --- КОНЕЦ ФИНАЛЬНОЙ ВЕРСИИ ФУНКЦИИ ---

add_action('wp_ajax_load_videos', 'load_videos_callback');
add_action('wp_ajax_nopriv_load_videos', 'load_videos_callback');

// Получение платежных ссылок
function get_payment_links_callback() {
    check_ajax_referer('payment_links_nonce', 'security');
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (!$post_id) {
        wp_send_json_error('Invalid post ID');
    }
    
    $links = [
        'paguello' => get_field('paguello', $post_id),
        'stripe' => get_field('stripe', $post_id),
        'paguello_rent' => get_field('paguello_rent', $post_id),
        'stripe_rent' => get_field('stripe_rent', $post_id)
    ];
    
    wp_send_json_success($links);
}
add_action('wp_ajax_get_payment_links', 'get_payment_links_callback');
add_action('wp_ajax_nopriv_get_payment_links', 'get_payment_links_callback');

// ========================
// 8. Шорткоды
// ========================
add_shortcode('my_video_library', function() {
    if (!is_user_logged_in()) {
        return do_shortcode('[ihc-login-form]');
    }

    ob_start();
    ?>
<div class="video-library-header">
    <!-- <h3>My Video Library</h3> -->

    <div class="available-videos-container">

        <?php
            $user_id = get_current_user_id();
            $user_levels = [];
            
            $user_levels_meta = get_user_meta($user_id, 'ihc_user_levels', true);
            if ($user_levels_meta && is_array($user_levels_meta)) {
                $user_levels = array_keys($user_levels_meta);
            }
            
            if (current_user_can('administrator')) {
                $user_levels = range(1, 100);
            }
            
            $all_video_args = [
                'post_type' => 'video',
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key' => 'subscription_level',
                        'value' => $user_levels,
                        'compare' => 'IN'
                    ]
                ],
                'orderby' => 'title',
                'order' => 'ASC'
            ];
            
            $all_videos = new WP_Query($all_video_args);
            
            $accessible_video_ids = [];
            $access_check_template = '[ihc-hide-content ihc_mb_type="show" ihc_mb_who="%d"]TEMP[/ihc-hide-content]';
            
            if ($all_videos->have_posts()) {
                while ($all_videos->have_posts()) {
                    $all_videos->the_post();
                    $video_id = get_the_ID();
                    $subscription_level = get_field('subscription_level', $video_id);
                    
                    $access_check = do_shortcode(sprintf($access_check_template, $subscription_level));
                    if (strpos($access_check, 'TEMP') !== false) {
                        $accessible_video_ids[] = $video_id;
                    }
                }
                wp_reset_postdata();
            }
            
            if (!empty($accessible_video_ids)) {
                $final_video_args = [
                    'post_type' => 'video',
                    'posts_per_page' => -1,
                    'post__in' => $accessible_video_ids,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ];
                
                $final_videos = new WP_Query($final_video_args);
                
                if ($final_videos->have_posts()) {
                    echo '<div class="video-grid">';
                    while ($final_videos->have_posts()) {
                        $final_videos->the_post();
                        ?>
        <div class="video-item_my-account">
            <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium', ['class' => 'video-thumbnail']); ?>
                <?php else : ?>
                <div class="video-placeholder">No thumbnail</div>
                <?php endif; ?>
                <h4><?php the_title(); ?></h4>
            </a>
            <div class="video-meta">
                <a href="<?php the_permalink(); ?>" class="watch-button">Watch</a>
            </div>
        </div>
        <?php
                    }
                    echo '</div>';
                    wp_reset_postdata();
                } else {
                    echo '<p class="no-videos">No videos available for your subscription level.</p>';
                }
            } else {
                echo '<div class="access-message">';
                echo '
                <p>
                    Unfortunately, you don`t have any videos available yet. 
                    <a href="'. get_home_url() .'">Buy</a> or <a href="'. get_permalink( 5232 ) .'">rent</a> something on our website and you will see all the videos available to you here. 
                    In the meantime, you can enjoy the trailers on the main page of the website or in our <a target="_blank" href="https://t.me/+m0Mm_vjGPDY4ZjAy">Telegram channel</a>.
                </p>';
                echo '</div>';
            }
            ?>
    </div>
</div>
<?php
    return ob_get_clean();
});

// ========================
// 8. Отправка формы в Телеграм
// ========================
add_action('ninja_forms_after_submission', 'send_ninja_form_to_telegram', 10, 1);
function send_ninja_form_to_telegram($form_data) {
    $bot_token = '8121488385:AAH_-eaW4o1DMlA1F93pYy85rK0Q_eIGNZQ';
    $chat_id = '-4968274829';
    
    // Получаем данные формы
    $form_fields = $form_data['fields'];
    $message = "Новая заявка:\n";
    
    foreach ($form_fields as $field) {
        $message .= $field['label'] . ": " . $field['value'] . "\n";
    }
    
    // Отправляем в Telegram
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    $args = array(
        'body' => array(
            'chat_id' => $chat_id,
            'text' => $message,
        ),
    );
    
    wp_remote_post($url, $args);
}

// ========================
// 9. Переключение платежных систем
// ========================
add_action('customize_register', function($wp_customize) {
	$wp_customize->add_section('payment_settings', [
			'title' => 'Настройки оплаты',
			'priority' => 160,
	]);
	
	$wp_customize->add_setting('payment_method', [
			'default' => 'paguello',
			'sanitize_callback' => 'sanitize_text_field',
	]);
	
	$wp_customize->add_control('payment_method', [
			'label' => 'Основной метод оплаты',
			'section' => 'payment_settings',
			'type' => 'radio',
			'choices' => [
					'paguello' => 'Paguello (кнопки) + Stripe (ссылка)',
					'stripe' => 'Stripe (кнопки) + Paguello (ссылка)',
			],
	]);
});

// Исключаем SVG-файл из оптимизации LiteSpeed
add_filter( 'litespeed_optimize_js_excludes', function( $excludes ) {
    $excludes[] = '/wp-content/themes/veronica-silesto/assets/img/icons.svg';
    return $excludes;
});

// Исключаем SVG из оптимизации изображений (если он используется как <img>)
add_filter( 'litespeed_optimize_img_excludes', function( $excludes ) {
    $excludes[] = '/wp-content/themes/veronica-silesto/assets/img/icons.svg';
    return $excludes;
});

// Исключаем SVG из оптимизации изображений (если он используется как <img>)
add_filter( 'litespeed_optm_uri_exc', function( $excludes ) {
    $excludes[] = '/wp-content/themes/veronica-silesto/assets/img/icons.svg';
    return $excludes;
});

/**
 * Скрипт для одноразового пакетного изменения ролей пользователей.
 * После использования ОБЯЗАТЕЛЬНО удалить.
 */
// function silesto_batch_change_user_roles_once() {
//     if ( ! current_user_can( 'manage_options' ) || get_option( 'silesto_roles_updated_flag_2025' ) ) {
//         return;
//     }

//     $user_emails_to_change = [
//         'danso12345@gmail.com'
//     ];

//     $new_role = 'vip_user'; 

//     set_time_limit(600); 

//     foreach ( $user_emails_to_change as $email ) {
//         $user = get_user_by( 'email', trim($email) ); 
//         if ( $user ) {
//             $user->set_role( $new_role );
//         }
//     }

//     update_option( 'silesto_roles_updated_flag_2025', true );
// }
// add_action( 'admin_init', 'silesto_batch_change_user_roles_once' );



// --- НАЧАЛО БЛОКА ЛОГИКИ ДЛЯ VIP LOUNGE ---
/**
 * 1. Перенаправляет пользователей с ролью 'vip_user' с главной страницы на 'VIP Lounge'.
 */
function silesto_vip_lounge_redirect() {
    $vip_lounge_page_id = 6368; 
    if ( is_front_page() && ! is_page( $vip_lounge_page_id ) ) {
        if ( is_user_logged_in() && current_user_can( 'vip_user' ) ) {
            wp_redirect( get_permalink( $vip_lounge_page_id ) );
            exit;
        }
    }
}
add_action( 'template_redirect', 'silesto_vip_lounge_redirect' );
/**
 * 2. Защищает страницу 'VIP Lounge' от доступа non-VIP пользователей.
 */
function silesto_protect_vip_lounge_page() {
    $vip_lounge_page_id = 6368; 
    if ( is_page( $vip_lounge_page_id ) ) {
        if ( ! current_user_can( 'vip_user' ) && ! current_user_can( 'manage_options' ) ) {
            wp_redirect( home_url( '/' ) );
            exit;
        }
    }
}
add_action( 'template_redirect', 'silesto_protect_vip_lounge_page' );
// --- КОНЕЦ БЛОКА ЛОГИКИ ДЛЯ VIP LOUNGE ---


// --- НАЧАЛО БЛОКА УПРАВЛЕНИЯ БАЛАНСОМ ПОЛЬЗОВАТЕЛЯ ---

/**
 * Добавляет поле "Баланс" на страницу редактирования профиля,
 * но ТОЛЬКО для пользователей с ролью 'vip_user'.
 *
 * @param WP_User $user Объект пользователя.
 */
function silesto_add_user_balance_field( $user ) {
    // --- ИЗМЕНЕНИЕ ЗДЕСЬ ---
    // Показываем поле, только если:
    // 1. Редактируемый пользователь имеет роль 'vip_user'.
    // 2. Текущий пользователь (вы) - администратор.
    if ( ! user_can( $user, 'vip_user' ) || ! current_user_can( 'manage_options' ) ) {
        return; // Если условия не выполнены, ничего не показываем.
    }
    
    $balance = get_user_meta( $user->ID, 'silesto_user_balance', true );
    $balance = ! empty( $balance ) ? floatval( $balance ) : 0;
    ?>
<h3><?php _e( 'Управление балансом Silesto', 'silesto' ); ?></h3>
<table class="form-table">
    <tr>
        <th><label for="silesto_user_balance"><?php _e( 'Баланс пользователя ($)', 'silesto' ); ?></label></th>
        <td>
            <input type="number" id="silesto_user_balance" name="silesto_user_balance"
                value="<?php echo esc_attr( $balance ); ?>" class="regular-text" step="0.01" />
            <p class="description">
                <?php _e( 'Укажите текущий баланс пользователя. Используйте точку в качестве десятичного разделителя.', 'silesto' ); ?>
            </p>
        </td>
    </tr>
</table>
<?php
}
add_action( 'show_user_profile', 'silesto_add_user_balance_field' );
add_action( 'edit_user_profile', 'silesto_add_user_balance_field' );

/**
 * Сохраняет значение поля "Баланс" при обновлении профиля,
 * ТОЛЬКО для пользователей с ролью 'vip_user'.
 *
 * @param int $user_id ID пользователя.
 */
function silesto_save_user_balance_field( $user_id ) {
    // Проверяем права на редактирование
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    // Получаем объект пользователя, чтобы проверить его роль
    $user = get_user_by( 'id', $user_id );

    // --- ИЗМЕНЕНИЕ ЗДЕСЬ ---
    // Сохраняем данные, только если пользователь является VIP-пользователем.
    if ( user_can( $user, 'vip_user' ) && isset( $_POST['silesto_user_balance'] ) ) {
        $new_balance = floatval( $_POST['silesto_user_balance'] );
        update_user_meta( $user_id, 'silesto_user_balance', $new_balance );
    }
}
add_action( 'personal_options_update', 'silesto_save_user_balance_field' );
add_action( 'edit_user_profile_update', 'silesto_save_user_balance_field' );

// --- КОНЕЦ БЛОКА УПРАВЛЕНИЯ БАЛАНСОМ ПОЛЬЗОВАТЕЛЯ ---

/**
 * Получает и форматирует баланс текущего пользователя.
 * (Исправленная версия: корректно обрабатывает нулевой/неустановленный баланс)
 *
 * @return string|false Отформатированный баланс или false, если не удалось определить пользователя.
 */
function silesto_get_current_user_balance_formatted() {
    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        return false;
    }

    // Получаем баланс. Если он еще не установлен, get_user_meta вернет пустое значение,
    // которое floatval() корректно преобразует в 0.
    $balance = get_user_meta( $user_id, 'silesto_user_balance', true );

    // Форматируем число: 2 знака после запятой, точка в качестве разделителя.
    $formatted_balance = number_format( floatval( $balance ), 2, '.', '' );

    return 'Balance: $' . $formatted_balance;
}

// --- НАЧАЛО БЛОКА: ПОКУПКА ВИДЕО С БАЛАНСА ---
/**
 * AJAX-обработчик для покупки видео с внутреннего баланса.
 * (Версия 2.5: Создание завершенного заказа в UMP для активации уровня).
 */
function silesto_purchase_video_with_balance_handler() {
    // 1. Проверка безопасности
    if ( !check_ajax_referer('silesto-ajax-nonce', 'nonce', false) ) {
        wp_send_json_error(['message' => 'A security error.']);
        return;
    }

    // 2. Проверка, что пользователь является VIP
    if ( !is_user_logged_in() || !current_user_can('vip_user') ) {
        wp_send_json_error(['message' => 'This feature is only available to VIP users.']);
        return;
    }

    // 3. Получаем данные и цену с сервера
    $user_id = get_current_user_id();
    $video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
    if ( !$video_id ) {
        wp_send_json_error(['message' => 'Incorrect information about the video.']);
        return;
    }

    $level_id_to_assign = get_field('subscription_level', $video_id);
    if ( !$level_id_to_assign || !function_exists('ihc_get_level_by_id') ) {
        wp_send_json_error(['message' => 'The cost of the video could not be determined.']);
        return;
    }
    $level_data = ihc_get_level_by_id($level_id_to_assign);
    $video_price = isset($level_data['price']) ? floatval($level_data['price']) : 0;

    if ($video_price <= 0) {
         wp_send_json_error(['message' => 'This video cannot be bought this way.']);
         return;
    }

    // 4. Проверка баланса
    $current_balance = get_user_meta($user_id, 'silesto_user_balance', true);
    $current_balance = !empty($current_balance) ? floatval($current_balance) : 0;

    if ($current_balance < $video_price) {
        $top_up_url = '/'; // Укажите URL страницы пополнения баланса
        wp_send_json_error([
            'message' => 'Insufficient funds on the balance sheet. <a href="' . $top_up_url . '">Deposit</a>'
        ]);
        return;
    }

    // 5. Все проверки пройдены. Списываем средства и даем доступ.
    $new_balance = $current_balance - $video_price;
    update_user_meta($user_id, 'silesto_user_balance', $new_balance);

    // --- ИЗМЕНЕНИЕ ЗДЕСЬ: СОЗДАЕМ И ЗАВЕРШАЕМ ЗАКАЗ В UMP ---
    if (class_exists('\Indeed\Ihc\Db\Orders')) {
        $order_meta = [
            'ihc_payment_type'      => 'internal_balance', // Наш собственный тип платежа
            'details'               => 'Purchase with internal balance',
            'tax_value'             => 0,
            'coupon_used'           => '',
        ];

        // Создаем заказ
        $order = new \Indeed\Ihc\Db\Orders();
        $order_id = $order->save([
            'uid'           => $user_id,
            'lid'           => $level_id_to_assign,
            'amount_type'   => get_option('ihc_currency'),
            'amount_value'  => $video_price, // Записываем реальную стоимость
            'status'        => 'Completed', // Сразу помечаем как завершенный
        ], $order_meta);

        // И после этого активируем уровень, ссылаясь на созданный заказ
        \Indeed\Ihc\UserSubscriptions::makeComplete($user_id, $level_id_to_assign, false, ['order_id' => $order_id]);
    }
    // --- КОНЕЦ ИЗМЕНЕНИЯ ---

    // 6. Отправляем успешный ответ
    wp_send_json_success([
        'message' => 'Access to the video has been successfully opened!',
        'new_balance' => number_format($new_balance, 2, '.', '')
    ]);
}
add_action('wp_ajax_purchase_with_balance', 'silesto_purchase_video_with_balance_handler');

// --- КОНЕЦ БЛОКА ---


// --- НАЧАЛО БЛОКА: ИНТЕГРАЦИЯ С LAVA TOP ---

// 1. Шорткод для отображения формы пополнения баланса
add_shortcode('silesto_top_up_form', function() {
    // Показываем форму только для VIP-пользователей
    if (!is_user_logged_in() || !current_user_can('vip_user')) {
        return '<p>This page is available for VIP users only.</p>';
    }

    // Обработка отправки формы
    if (isset($_POST['silesto_top_up_amount']) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'silesto_top_up_nonce')) {
        $amount = floatval($_POST['silesto_top_up_amount']);
        if ($amount > 0) {
            // Генерируем ссылку на оплату и перенаправляем пользователя
            $payment_url = silesto_lava_create_invoice($amount);
            if ($payment_url) {
                wp_redirect($payment_url);
                exit;
            } else {
                return '<p style="color: red;">Error creating payment link. Please try again.</p>';
            }
        }
    }

    // HTML-код самой формы
    ob_start();
    ?>
<form method="POST" class="silesto-top-up-form">
    <h3>Top Up Balance</h3>
    <p>Enter the amount you wish to add to your balance.</p>
    <div class="form-field">
        <label for="silesto_top_up_amount">Amount ($)</label>
        <input type="number" name="silesto_top_up_amount" min="1" step="0.01" required>
    </div>
    <?php wp_nonce_field('silesto_top_up_nonce'); ?>
    <button type="submit" class="button red-color">Proceed to Payment</button>
</form>
<style>
.silesto-top-up-form {
    max-width: 400px;
}

.silesto-top-up-form .form-field {
    margin-bottom: 15px;
}

.silesto-top-up-form input {
    width: 100%;
    padding: 8px;
}

.silesto-top-up-form button {
    width: 100%;
}
</style>
<?php
    return ob_get_clean();
});

// 2. Функция для создания счета в LAVA
function silesto_lava_create_invoice($amount) {
    $user_id = get_current_user_id();
    if (!$user_id) return false;

    // --- ВАШИ ДАННЫЕ ИЗ LAVA ---
    $secret_key = 'PY55A3LL3CUarFnGFWbmLXdDYvZvmhBcTrbsdd9jzgHfdwO967M0YF39Rexh3JWZ';     // Замените на ваш API-ключ LAVA
    // ---

    $order_id = 'silesto-' . $user_id . '-' . time(); // Уникальный ID заказа

    $request_data = [
        'sum'       => $amount,
        'orderId'   => $order_id,
        'hookUrl'   => home_url('/wp-json/lava/v1/webhook'),
        'customFields' => (string) $user_id, // <--- КРИТИЧЕСКИ ВАЖНО: передаем ID пользователя
        'comment'   => 'Balance top-up for user ' . $user_id,
    ];

    $response = wp_remote_post('https://api.lava.top/business/invoice/create', [
        'headers' => [
            'Authorization' => $secret_key,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ],
        'body'    => json_encode($request_data),
        'method'  => 'POST',
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['data']['url'])) {
        return $body['data']['url'];
    }
    return false;
}

// 3. Создаем обработчик для вебхуков от LAVA (REST API Endpoint)
add_action('rest_api_init', function () {
    register_rest_route('lava/v1', '/webhook', [
        'methods'  => 'POST',
        'callback' => 'silesto_lava_webhook_handler',
        'permission_callback' => '__return_true', // Открываем доступ для LAVA
    ]);
});

// 4. Функция, которая обрабатывает вебхук
function silesto_lava_webhook_handler($request) {
    $headers = $request->get_headers();
    $body = $request->get_body();
    
    // --- ВАШИ ДАННЫЕ ИЗ LAVA ---
    $secret_key_webhook = 'aPfSJas287R7OLuoqfbxIKM2XCTlYUts01yNDOVEqij3YMypU0sjA8bYUUDBhsPO'; // Замените на ваш Секретный ключ (второй)
    // ---

    // Проверяем подлинность запроса
    $auth_header = isset($headers['authorization'][0]) ? $headers['authorization'][0] : '';
    $signature = hash_hmac('sha256', $body, $secret_key_webhook);

    if ($auth_header !== $signature) {
        // Подпись неверна, игнорируем запрос
        return new WP_REST_Response('Signature failed', 401);
    }
    
    $data = json_decode($body, true);

    // Проверяем, что это успешный платеж
    if (isset($data['status']) && $data['status'] === 'success' && !empty($data['custom_fields'])) {
        $user_id = intval($data['custom_fields']); // <--- Получаем ID нашего пользователя
        $amount_credited = floatval($data['amount']); // Сумма, которая была зачислена

        if ($user_id > 0 && $amount_credited > 0) {
            // Получаем текущий баланс
            $current_balance = get_user_meta($user_id, 'silesto_user_balance', true);
            $current_balance = !empty($current_balance) ? floatval($current_balance) : 0;
            
            // Добавляем новую сумму
            $new_balance = $current_balance + $amount_credited;
            
            // Обновляем баланс пользователя
            update_user_meta($user_id, 'silesto_user_balance', $new_balance);
        }
    }

    return new WP_REST_Response('OK', 200);
}

// --- КОНЕЦ БЛОКА: ИНТЕГРАЦИЯ С LAVA TOP ---


// --- НАЧАЛО ВРЕМЕННОГО БЛОКА ДЛЯ ТЕСТА LAVA API ---

/**
 * Временный AJAX-обработчик для тестирования создания счета в LAVA.
 */
function silesto_test_lava_invoice_ajax_handler() {
    // Проверяем, что это администратор и проверяем nonce
    if ( !current_user_can('manage_options') || !check_ajax_referer('silesto_test_nonce', 'nonce', false) ) {
        wp_send_json_error('Security check failed.');
        return;
    }

    // Тестовая сумма
    $test_amount = 1.23;

    // Вызываем нашу основную функцию для создания счета
    $payment_url = silesto_lava_create_invoice($test_amount);

    if ($payment_url) {
        wp_send_json_success(['payment_url' => $payment_url]);
    } else {
        wp_send_json_error(['message' => 'Failed to create invoice. Check your Project ID and API Key in functions.php']);
    }
}
add_action('wp_ajax_test_lava_invoice', 'silesto_test_lava_invoice_ajax_handler');

// --- КОНЕЦ ВРЕМЕННОГО БЛОКА ---