<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
    <title><?php bloginfo( 'title' ) ?></title>
</head>

<body>
    <header>
        <div class="background-image">
            <picture>
                <!-- Мобильное изображение (вертикальное) -->
                <source media="(max-width: 768px)" srcset="/wp-content/uploads/2025/05/bg_mob.webp">
                <!-- Десктопное изображение (горизонтальное) -->
                <img src="/wp-content/uploads/2024/12/bg_1.webp" alt="Background" class="bg-img" decoding="async">
            </picture>
            <div class="container">
                <div class="sacramento">Veronica Silesto</div>
            </div>
        </div>
        <button class="menu-toggle" aria-controls="header-menu" aria-expanded="false">
            <span class="hamburger"></span>
            <span class="sr-only">Меню</span>
        </button>
        <?php 
            wp_nav_menu( [
                'theme_location'  => 'headermenu',
                'container'       => 'nav',
                'container_class'       => 'menu',
                'menu_class'      => 'header-menu, container',
	            'menu_id'         => 'header-menu',
        ] ); ?>
        <?php
		// Проверяем, что пользователь авторизован и является VIP-пользователем
		if ( is_user_logged_in() && current_user_can( 'vip_user' ) ) :
			$balance_text = silesto_get_current_user_balance_formatted();
			if ( $balance_text ) :
		?>
        <div class="header-balance-container container">
            <span class="user-balance"><?php echo esc_html( $balance_text ); ?></span>
        </div>
        <?php 
			endif;
		endif; 
		?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.menu');

            menuToggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                menu.classList.toggle('active');
            });
        });
        </script>
    </header>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        for (var j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
            k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(92093499, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true
    });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/92093499" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->