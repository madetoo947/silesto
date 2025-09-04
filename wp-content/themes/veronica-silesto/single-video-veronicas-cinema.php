<?php get_header(); ?>
<style>
.content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

h2.small {
    margin: 0;
}

.post {
    gap: 30px;
}

.post h1 {
    margin: 0;
}

.post .video-container:has(iframe) {
    height: calc(1256px / 16 * 9);
}

@media screen and (max-width: 1380px) {
    .post .video-container:has(iframe) {
        height: calc((100vw - 60px) / 16 * 9);
    }
}

@media screen and (max-width: 768px) {
    .post .video-container:has(iframe) {
        height: calc((100vw - 20px) / 16 * 9);
    }
}
</style>
<?php
if (have_posts()) : 
    while (have_posts()) : the_post();
        $title = get_the_title(); 
        $content = apply_filters('the_content', get_the_content()); 
        $buy_week_pugello = get_field('buy_week_pugello'); 
        $buy_week_stripe = get_field('buy_week_stripe'); 
        $buy_month_pugello = get_field('buy_month_pugello'); 
        $buy_month_stripe = get_field('buy_month_stripe'); 
        $trailer = get_field('trailer_url'); 
        $video_url = get_field('video_url'); 
        $subscription_level = get_field('subscription_level');
        $popup_trailer_url = get_field('popup_trailer_url');
				$main_trailer_url = get_field('main_trailer_url');
				$preview = get_field('img_preview');
?>
<div class="post">
    <h1><?php echo esc_html($title); ?></h1>
    <?php
                // Проверка через шорткод (более надежный способ)
                $access_check = do_shortcode('[ihc-hide-content ihc_mb_type="show" ihc_mb_who="' . $subscription_level . '" ]TEMP[/ihc-hide-content]');
                $has_access = (strpos($access_check, 'TEMP') !== false);
            ?>
    <div class="video-box featured-video">
        <div class="video-container">
            <?php if ($has_access) : ?>
            <iframe src="<?php echo esc_url($video_url); ?>"
                allow="autoplay; fullscreen; picture-in-picture; encrypted-media; gyroscope; accelerometer; clipboard-write; screen-wake-lock;"
                frameborder="0" allowfullscreen style="width: 100%; height: 100%; top: 0; left: 0;">
            </iframe>
            <?php else : ?>
            <video autoplay muted loop playsinline>
                <source src="<?php echo esc_url($main_trailer_url); ?>" type="video/mp4">
            </video>
            <?php 
					if ($preview) {
						echo '<img class="preview" src="' . esc_url($preview['url']) . '" alt="' . esc_attr($preview['alt']) . '">';
					} 
				?>
            <?php endif; ?>
        </div>
        <div class="buttons-block">
            <div class="one-group">
                <?php if (!$has_access) : ?>
                <!-- <a href="#cinema-trailer" data-trailer="<?php // echo esc_attr($popup_trailer_url); ?>" class="button grey-color cinema-trailer-btn">Watch Today</a> -->
                <a href="https://t.me/+m0Mm_vjGPDY4ZjAy" data-trailer="<?php echo esc_attr($popup_trailer_url); ?>"
                    class="button grey-color cinema-trailer-btn">TRAILER</a>
                <a href="#" class="button subscribe red-color">SUBSCRIBE</a>
                <?php else : ?>
                <a href="/" class="button red-color">Back</a>
                <a href="<?php echo get_permalink(3064); ?>" class="button grey-color">Previously</a>
                <?php endif; ?>
            </div>
            <?php if (!$has_access) : ?>
            <div class="two-group">
                <?php 
                        $post_ID = 2696;
                        $paguello_month = get_field('buy_month_paguello', $post_ID);
                        $paguello_week = get_field('buy_week_paguello', $post_ID);
												$stripe_month = get_field('buy_month_stripe', $post_ID);
                        $stripe_week = get_field('buy_week_stripe', $post_ID);
                    ?>
                <a href="#" data-paguello="<?php echo esc_attr($paguello_month); ?>"
                    data-stripe="<?php echo esc_attr($stripe_month); ?>"
                    class="button button_subscribe border-red">MONTH
                    FOR $150
                </a>
                <a href="#" data-paguello="<?php echo esc_attr($paguello_week); ?>"
                    data-stripe="<?php echo esc_attr($stripe_week); ?>" class="button button_subscribe border-red">WEEK
                    FOR
                    $60
                </a>
            </div>
            <div class="content">
                <?php echo get_field('description'); ?>
                <?php echo $content; // Выводит содержимое поста ?>
                <a class="button red-color back" href="/">Back</a>
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>



<?php 
    endwhile;
else : 
    echo '<p>Запись не найдена</p>';
endif;
?>
<?php get_footer(); ?>