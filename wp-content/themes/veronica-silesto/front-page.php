<?php 
/**
 * Главная страница сайта
 */
get_header(); ?>
<div class="post">
    <p>Hi! You came to this website and that means you know exactly who I am. I am Veronica Silesto, a glamorous model
        who has allowed herself
        the most extreme journey through the world of passion and seduction. My name is known by millions of people who
        have somehow looked
        into the world of unusual relationships. There are those who hate me, and those who remain indifferent. But my
        great happiness is
        that I give passion and incomparable pleasure to most of you. This is my drive and purpose.</p>
    <!-- Секция главного видео -->
    <section class="featured-video">
        <?php
                $subscription_level = 69;
                $post_ID = 2696;
                $main_trailer_url = get_field('main_trailer_url', $post_ID); 
                $popup_trailer_url = get_field('popup_trailer_url', $post_ID); 
								$preview = get_field('img_preview', $post_ID);
                // Проверка через шорткод (более надежный способ)
                $access_check = do_shortcode('[ihc-hide-content ihc_mb_type="show" ihc_mb_who="' . $subscription_level . '" ]TEMP[/ihc-hide-content]');
                $has_access = (strpos($access_check, 'TEMP') !== false);
            ?>
        <div class="video-box">
            <h2>VERONICA'S CINEMA</h2>
            <div class="video-container">
                <img class="cinema-img" src="<?php echo esc_url($preview['url']) ?>">
                <!-- <iframe src="<?php 
				// echo esc_url($main_trailer_url); 
				?>"
				 
					allow="autoplay; fullscreen; picture-in-picture; encrypted-media; gyroscope; accelerometer; clipboard-write; screen-wake-lock;"
					frameborder="0" allowfullscreen style="width: 100%; height: 100%; top: 0; left: 0;"></iframe> -->
                <!-- <video autoplay muted loop playsinline>
                    <source src="<?php // echo esc_url($main_trailer_url); ?>" type="video/mp4">
                </video> -->
                <?php 
					// if ($preview) {
					// 	echo '<img class="preview" src="' . esc_url($preview['url']) . '" alt="' . esc_attr($preview['alt']) . '">';
					// } 
				?>
            </div>
            <div class="buttons-block">
                <div class="one-group">
                    <a href="<?php echo get_permalink( $post_ID ); ?>" class="button blue-color">MORE INFO</a>
                    <!-- <a href="#cinema-trailer" data-trailer="<?php // echo esc_attr($popup_trailer_url); ?>" class="button grey-color cinema-trailer-btn">Watch Today</a> -->
                    <a href="https://t.me/+m0Mm_vjGPDY4ZjAy" data-trailer="<?php echo esc_attr($popup_trailer_url); ?>"
                        class="button grey-color cinema-trailer-btn">TRAILER</a>
                    <?php if (!$has_access) : ?>
                    <a href="#" class="button subscribe red-color">SUBSCRIBE</a>
                    <?php else : ?>
                    <a href="<?php echo get_permalink( $post_ID ); ?>" class="button grey-color">WATCH</a>
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
                        data-stripe="<?php echo esc_attr($stripe_week); ?>"
                        class="button button_subscribe border-red">WEEK FOR
                        $60
                    </a>
                </div>
                <?php endif; ?>

            </div>
            <div class="content">
                <p><?php echo get_field('description', $post_ID); ?></p>
            </div>
        </div>
    </section>

    <section>
        <h2 class="video-title">NEWS</h2>
        <div class="video-list">
            <div class="video-item conteiner">
                <a href="https://t.me/silestover">
                    <div class="video-thumbnail">
                        <img loading="lazy" decoding="async" width="700" height="395"
                            src="/wp-content/uploads/2025/05/1.webp" class="attachment" alt=""
                            srcset="/wp-content/uploads/2025/05/1.webp 700w, /wp-content/uploads/2025/05/1-300x169.webp 300w"
                            sizes="(max-width: 700px) 100vw, 700px">
                    </div>
                </a>
                <a class="button blue-color" href="https://t.me/silestover" target="_blank">WATCH ALL
                    TRAILERS</a>
            </div>

            <?php
                $post_id = 4820;
                $post = get_post($post_id);

                if ($post) :
                    setup_postdata($post);
                    $trailer = get_field('trailer_url', $post_id);
                    $post_link = get_permalink($post_id); // Добавляем получение ссылки на пост
                ?>
            <div class="video-item conteiner">
                <a href="<?php echo esc_url($post_link); ?>">
                    <div class="video-thumbnail">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                </a>
                <a class="button blue-color" href="<?php echo esc_url($post_link); ?>">More Info</a>
            </div>
            <?php 
                endif; 
                wp_reset_postdata(); 
            ?>
            <?php
                $post_id = 5232;
                $post = get_post($post_id);

                if ($post) :
                    setup_postdata($post);
                    $trailer = get_field('trailer_url', $post_id);
                    $post_link = get_permalink($post_id); // Добавляем получение ссылки на пост
                ?>
            <div class="video-item conteiner">
                <a href="<?php echo esc_url($post_link); ?>">
                    <div class="video-thumbnail">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                </a>
                <a class="button blue-color" href="<?php echo esc_url($post_link); ?>">More Info</a>
            </div>
            <?php 
                endif; 
                wp_reset_postdata(); 
            ?>
        </div>
    </section>

    <!-- Секция списка видео -->
    <section class="video-archive">
        <h2>Full Videos</h2>
        <form id="video-search-form" class="video-search">
            <div class="input-group">
                <input type="text" name="s" placeholder="Search videos..." autocomplete="off">
                <button type="submit">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 19.3601L15.4562 14.7939C18.7184 11.1583 18.4563 5.57412 14.8154 2.28758C11.1746 -0.998961 5.58224 -0.708117 2.29091 2.92744C-1.00042 6.56299 -0.709148 12.1472 2.9317 15.4337C6.31041 18.4876 11.4658 18.4876 14.8446 15.4337L19.4175 20L20 19.3601ZM8.87357 16.8298C4.47542 16.8298 0.892825 13.2524 0.892825 8.86066C0.892825 4.43983 4.47542 0.891527 8.87357 0.891527C13.2717 0.891527 16.8543 4.46891 16.8543 8.86066C16.8543 13.2524 13.2717 16.8298 8.87357 16.8298Z"
                            fill="#ffffff" />
                        <path
                            d="M8.87343 2.08405V2.95659C12.1356 2.95659 14.7862 5.60327 14.7862 8.86073H15.66C15.66 5.10883 12.6308 2.08405 8.87343 2.08405Z"
                            fill="#ffffff" />
                    </svg>

                </button>
            </div>
            <div class="search-results video-list"></div>
        </form>
        <div class="video-list" id="video-container"></div>
        <div id="loading" class="loading-indicator">
            <p>Loading more videos...</p>
            <div class="preloader-1">
                <div class="dot dot-1"></div>
                <div class="dot dot-2"></div>
                <div class="dot dot-3"></div>
            </div>
        </div>
    </section>
</div>
<div id="popup-message">
    <span id="popup-close">&times;</span>
    <div class="popup-content">
        <h3>Dear fans!</h3>
        <p>If you bought a video and didn't get access within 10 minutes, check your SPAM folder and write us at
            <a href="mailto:veronicasilesto@gmail.com">veronicasilesto@gmail.com</a>
        </p>
    </div>
</div>



<?php get_footer(); ?>