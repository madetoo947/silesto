<?php get_header(); ?>
<style>
.flex {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 580px;
    margin: 60px auto 0 auto;
    gap: 20px;
    font-size: 16px;
    padding: 20px;
}
.flex p {
    text-align: center;
}

.ihc-login-template-2 {
    min-width: 300px;
    font-family: var(--iump-primary-font);
    font-size: 14px;
    box-sizing: border-box;
    display: block;
    width: 100%;
}
.ihc-login-template-2 .impu-form-submit input:hover {
    color: #fff !important;
    background: #ff0000 !important;
    border-radius: 50px;
    -webkit-border-radius: 50px;
    -moz-border-radius: 50px;
    -o-border-radius: 50px;
}
.ihc-login-template-2 .impu-form-submit input {
    color: #fff !important;
    width: 100%;
    background: #ff0000 !important;
    border: 3px solid #ff0000;
    border-color: #ff0000 !important;
    text-transform: uppercase;
}
</style>
<div class="flex">
<h1>Lost Password</h1>
<?php echo do_shortcode('[ihc-pass-reset]'); ?>
</div>
<?php get_footer(); ?>