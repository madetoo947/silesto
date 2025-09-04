<?php 
/**
 * Страница HUMAN VIDEOS
 */
get_header(); ?>

<div class="post">
    <section class="video-archive">
        <h2>Human Videos</h2>
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

<script>
jQuery(document).ready(function($) {
    // При загрузке страницы передаем категорию human_videos
    loadVideos('human_videos');
});
</script>

<?php get_footer(); ?>