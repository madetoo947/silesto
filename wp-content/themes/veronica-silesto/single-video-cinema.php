<?php get_header(); ?>
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
?>
        <div class="post">
            <h1><?php echo esc_html($title); ?></h1>
            <?php
                // Проверка через шорткод (более надежный способ)
                $access_check = do_shortcode('[ihc-hide-content ihc_mb_type="show" ihc_mb_who="' . $subscription_level . '" ]TEMP[/ihc-hide-content]');
                $has_access = (strpos($access_check, 'TEMP') !== false);
            ?>
                <div class="video-box">
                <div class="video-container">
                    <iframe src="<?php if ($has_access) : ?><?php echo esc_url($video_url); ?><?php else : ?><?php echo esc_url($trailer); ?><?php endif; ?>"
                        allow="autoplay; fullscreen; picture-in-picture; encrypted-media; gyroscope; accelerometer; clipboard-write; screen-wake-lock;"
                        frameborder="0" allowfullscreen
                        style="width: 100%; height: 100%; top: 0; left: 0;"></iframe>
                </div>
                <div class="buttons-block">
                    <div class="one-group">
                        <a href="#" class="button gray-btn">HISTORY</a>
                        
                        <?php if (!$has_access) : ?>
                            <a href="#" class="button button_buy">SUBSCRIBE</a>
                        <?php else : ?>
                            <a href="#" class="button gray-btn">WATCH</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="two-group">
                        <?php if (!$has_access) : ?>
                            <a href="#" class="button red-btn">MONTH FOR $150</a>
                            <a href="#" class="button red-btn">WEEK FOR $60</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="content">
                <p><?php echo get_field('description'); ?></p>
                <p><?php echo $content; ?></p>
            </div>
            
        </div>
<?php 
    endwhile;
else : 
    echo '<p>Запись не найдена</p>';
endif;
?>
<?php get_footer(); ?>
