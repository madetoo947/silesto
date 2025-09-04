<footer id="colophon" role="contentinfo" class="post">
    <div class="footer-container">
        <div class="footer-logo">
            <img width="300" height="250" src="/wp-content/uploads/2024/11/Logo-1.png" alt="Silesto Logo">
        </div>

        <div class="footer-content">
            <div>
                <h2>ANY QUESTIONS?</h2>
                <p class="footer-notice">VERONICA'S TEAM IN TOUCH, NOT HERSELF. ONLY ENGLISH PLEASE.</p>
            </div>
            <a href="#contact-form" class="button red-color open-form">Contact us</a>
            <?php display_social_icons(); ?>
            <p class="copyright">@ Veronica Silesto Studio <?php echo date('Y'); ?></p>
        </div>
    </div>
</footer>
<div class="widget-right"><?php display_social_icons(); ?></div>
<div id="universal-popup" class="universal-popup">
    <div class="popup-overlay"></div>
    <div class="popup-container">
        <button class="popup-close-btn">×</button>
        <div class="popup-content">
            <div class="form-request-invoice">
                <?php echo do_shortcode('[ninja_form id=3]'); ?>
            </div>
            <div class="form-container">
                <?php echo do_shortcode('[ninja_form id=2]'); ?>
            </div>
            <iframe class="popup-iframe" allowfullscreen></iframe>
            <div class="popup-video-box">
                <h2>Cinema Trailer</h2>
                <div class="iframe-container">
                    <iframe class="popup-cinema-iframe" style="width: 100%; height: 100%; top: 0; left: 0;"
                        frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                </div>
            </div>
            <div class="video-container"></div>
            <div class="pay-container"></div>
        </div>
    </div>
</div>
<?php wp_footer();?>
</body>

</html>