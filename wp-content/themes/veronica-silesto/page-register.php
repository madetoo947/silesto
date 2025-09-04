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
.ihc-register-2 {
    width: 100%;
}
.ihc-register-2 form .iump-submit-form input:hover {
    color: #fff !important;
    background: #ff0000!important;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    -o-border-radius: 0px;
}

.ihc-register-2 form .iump-submit-form input {
    color: #fff !important;
    width: 100%;
    background: #ff0000 !important;
    border: 3px solid #ff0000;
    border-color: #ff0000 !important;
    box-shadow: none !important;
    -webkit-transition: .3s linear;
    -moz-transition: .3s linear;
    -ms-transition: .3s linear;
    -o-transition: .3s linear;
    transition: .3s linear;
    padding: 16px 22px;
    box-sizing: border-box;
    cursor: pointer;
    text-transform: uppercase;
}
</style>
<div class="flex">
<h1>Register</h1>
<?php echo do_shortcode('[ihc-register-lite]'); ?>
<p>
    Write a valid email address and click Register. Check your email, we will send you an email confirming your registration. If you haven’t received our message within 10 minutes, check your SPAM folder
</p>
<p>
    You have an account? <a style="
    color: #ff0000;
    text-decoration: underline;
" href="<?php echo get_permalink(764); ?>">Login</a>
</p>
</div>
<?php get_footer(); ?>