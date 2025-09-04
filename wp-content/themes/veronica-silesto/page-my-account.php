<?php get_header(); ?>
<style>
    .video-library-header {
        padding: 10px;
    }
    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    .ihc-ap-wrap {
        display: none;
    }
    .video-item_my-account {
        background: #000;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .ihc-user-page-top-ap-wrapper .ihc-account-page-top-mess {
        padding-left: 0;
    }
    .video-item_my-account:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .video-item_my-account a {
        display: block;
        color: #fff;
        text-decoration: none;
    }
    
    .video-thumbnail {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
    }
    
    .video-placeholder {
        width: 100%;
        height: 180px;
        background:rgb(0, 0, 0);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }
    
    .video-item_my-account h4 {
        margin: 15px;
        font-size: 14px;
        font-weight: 300;
        line-height: 1.3;
        text-transform: none;
    }
    
    .video-meta {
        padding: 0 15px 15px;
        text-align: center;
    }
    
    .watch-button {
        display: inline-block;
        padding: 8px 20px;
        background: red;
        color: white;
        text-decoration: none;
        font-weight: 400;
        transition: background 0.3s;
    }
    
    .watch-button:hover {
        background:rgb(216, 22, 0);
    }
    
    .access-message p {
        text-align: center;
        max-width: 850px;
        margin: 0 auto;
    }
    .access-message a {
        color: #ff0000;
        text-decoration: underline;
        font-weight: 600;
    }    
		.ihc-user-page-content-wrapper.ihc-ap-theme-4 {
				display: none;
		}
		.ihc-account-page-top-extra-mess {
    display: flex;
    width: 100%;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.ihc-account-page-top-mess {
    width: 100%;
}

.ihc-middle-side {
    width: 100%;
    max-width: 100% !important;
}
a.button.red-color {
    padding: 8px 16px;
}
.ihc-user-page-top-ap-wrapper.ihc-ap-top-theme-1 {
    padding: 0;
}
@media screen and (max-width: 768px) {
	.ihc-account-page-top-extra-mess {
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
}
}
</style>
<div class="post">
			<?php
        	echo do_shortcode('[ihc-user-page]');
        ?>
    <section class="video-archive">
        <h1>My Video Library</h1>
        <?php
        	echo do_shortcode('[my_video_library]');
        ?>
    </section>
</div>
<?php get_footer(); ?>