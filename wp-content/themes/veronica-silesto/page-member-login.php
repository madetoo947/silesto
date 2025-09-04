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
form .ihc-login-error, .ihc-login-notice {
    background: #ff1100 !important;
}
form .ihc-login-template-2 .impu-form-links a:hover {
    color: #ff0000 !important;
}
form .ihc-login-template-2 .impu-form-submit input {
	background: #ff0000 !important;
	height: 50px;
	cursor: pointer;
    text-transform: uppercase;
}
</style>
<div class="flex">
<h1>Log IN</h1>
<?php echo do_shortcode('[ihc-login-form]'); ?>
</div>
<?php get_footer(); ?>