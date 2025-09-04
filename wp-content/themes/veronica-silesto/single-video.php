<?php
/**
 * Template Name: Single Video
 * Template Post Type: video
 */
get_header();

$video_id = get_the_ID();
$content = get_the_content();
$subscription_level = get_field('subscription_level', $video_id);
$paguello = get_field('paguello', $video_id);
$stripe = get_field('stripe', $video_id);
$paguello_rent = get_field('paguello_rent', $video_id);
$stripe_rent = get_field('stripe_rent', $video_id);
$video_url = get_field('video_url', $video_id);
$trailer_url = get_field('trailer_url', $video_id);
$price = get_field('price', $post_id);
$video_title = get_the_title();

// Проверка доступа через UMP
$has_access = false;

// Проверка через шорткод (более надежный способ)
    $access_check = do_shortcode('[ihc-hide-content ihc_mb_type="show" ihc_mb_who="' . $subscription_level . '" ]TEMP[/ihc-hide-content]');
    $has_access = (strpos($access_check, 'TEMP') !== false);

?>
<style>
.buy_btns {
    display: flex;
    gap: 10px;
    flex-direction: column;
    width: fit-content;
}

section {
    gap: 30px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.video-item {
    align-items: flex-end;
}

.post .video-container:has(iframe) {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    margin: 0 auto;
    background: #000;
}

.post .video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

@media screen and (max-width: 768px) {
    .buy_btns {
        display: flex;
        gap: 10px;
        flex-direction: column;
        width: 100%;
        align-items: flex-end;
    }
}

.seo-content {
    white-space: pre-line;
    text-align: justify;
}
</style>
<main id="primary" class="site-main">
    <?php if ($has_access) : ?>
    <!-- Контент для подписчиков -->
    <div class="post">
        <section class="video-archive">
            <h2><?php echo esc_html($video_title); ?></h2>
            <?php if ($video_url) : ?>
            <div class="video-container">
                <iframe src="<?php echo esc_url($video_url); ?>"
                    allow="autoplay; fullscreen; picture-in-picture; encrypted-media; gyroscope; accelerometer; clipboard-write; screen-wake-lock;"
                    frameborder="0" allowfullscreen style="width: 100%; height: 100%; top: 0; left: 0;"></iframe>
            </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button back_btn red-color">Back</a>
        </section>
    </div>
    <?php else : ?>
    <!-- Контент для неподписанных пользователей -->
    <div class="post">
        <section class="video-archive">
            <h2><?php echo esc_html($video_title); ?></h2>
            <?php if ($trailer_url) : ?>
            <div class="video-container">
                <video controls width="100%" playsinline>
                    <source poster="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>"
                        src="<?php echo esc_url($trailer_url); ?>" type="video/mp4">
                </video>
            </div>
            <?php endif; ?>
            <div class="buy_btns">
                <?php if ($paguello_rent || $stripe_rent) : ?>
                <a href="#" class="button button_rent gray-btn">RENT 3 DAYS</a>
                <?php endif; ?>
                <a style="padding: 8px 60px" data-price="<?php echo esc_attr($price); ?>"
                    data-paguello="<?php echo esc_attr($paguello); ?>" data-stripe="<?php echo esc_attr($stripe); ?>"
                    href="#" class="button button_buy red-color">Buy
                    Now</a>
                <p class="payment-notice">
                    Can't pay? Use another
                    <a href="#pay-s" data-paguello="<?php echo esc_attr($paguello); ?>"
                        data-stripe="<?php echo esc_attr($stripe); ?>" class="payment-link">payment way by card</a>
                </p>
            </div>
            <?php if ($content) : ?>
            <div class="seo-content">
                <?php echo $content; ?>
            </div>
            <?php endif; ?>
        </section>

        <?php
        // Проверяем, есть ли связанные посты
        $related_posts = get_field('you_will_also_like');
        if ($related_posts) : ?>
        <section class="also">
            <h2>You will also like</h2>
            <div class="video-list">
                <?php foreach ($related_posts as $post) : 
                                    // Настраиваем глобальные данные поста
                                    setup_postdata($post);
                                    
                                    // Получаем URL поста
                                    $post_url = get_permalink();
                                    ?>
                <div class="video-item conteiner">
                    <a href="<?php echo esc_url($post_url); ?>">
                        <div class="video-thumbnail">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                    </a>
                    <a class="button more_btn" href="<?php echo esc_url($post_link); ?>">
                        More info
                        <svg class="social-icon" viewBox="0 0 15 15" width="15" height="15">
                            <use href="<?php echo get_template_directory_uri() . '/assets/img/icons.svg#arrow-right' ?>"
                                x="0" y="0">
                            </use>
                        </svg>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
                        // Сбрасываем глобальные данные поста
                        wp_reset_postdata();
                    endif;
                    ?>

    </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>