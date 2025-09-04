<?php get_header(); ?>
<div class="post">
<section class="video-archive">
        <h1>Change password</h1>
				<style>
					.iump-user-page-box-title {
						display: none;
					}
					.iump-submit-form #ihc_submit_change_pass_bttn {
							background: #ff0000 !important;
							border: 3px solid #ff0000;
							border-color: #ff0000 !important;
							box-shadow: none !important;
					}
				</style>
				<?php
        	echo do_shortcode('[ihc-edit-profile-form]');
        ?>
    </section>
</div>
<script>
	document.querySelector('.iump-user-page-wrapper.ihc_userpage_template_1:has([name="edituser"])').remove();
</script>
<?php get_footer(); ?>