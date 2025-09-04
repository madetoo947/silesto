<?php 
/**
 * HOW TO BUY
 */
get_header(); ?>
<style>
.post p {
    text-align: left;
}
.post h2 {
    text-align: left;
    font-size: 36px;
    color: #fff;
    font-family: 'Montserrat';
}
@media screen and (max-width: 768px) {
.post h2 {
    font-size: 20px;
}
.post section {
    gap: 10px;
}
}
</style>
<div class="post">
    <section class="video-archive">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </section>
</div>
<?php get_footer(); ?>