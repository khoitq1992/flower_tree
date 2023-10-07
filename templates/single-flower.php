<?php
// Template Name: Single Flower Post
flower_plugin_get_header();

if (have_posts()) :
    the_post();
    ?>
    <style>
        article {
            max-width: 1024px;
            margin: auto;
        }
        .flower-content .title {
            font-weight: bold;
        }
        .entry-title {
            text-align: center;
        }
        .flower-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .flower-gallery img {
            object-fit: cover;
            aspect-ratio: 1;
        }
        .flower-ancestors {
            display: flex;
            justify-content: center;
        }
        .flower-thumb {
            width: fit-content;
            margin: auto;
        }
        @media screen and (max-width: 1024px) {
            .flower-gallery {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
        }
        @media screen and (max-width: 600px) {
            .flower-gallery {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }
    </style>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content flower-content">
            <div class="flower-thumb"><?php the_post_thumbnail([200, 200]); ?></div>
            <p><span class="title">Tên Tiếng Việt: </span><?= get_the_title(); ?></p>
            <p><span class="title">Tên khoa học: </span><?= get_field("science_name"); ?></p>
            <p><span class="title">Ngày đăng ký: </span><?= get_field("registration_date"); ?></p>
            <p><span class="title">Người phát hiện: </span><?= get_field("discovery_person"); ?></p>
            <p><span class="title">Nơi sinh trưởng: </span><?= get_field("habitat_field"); ?></p>

            <p><span class="title">Hình ảnh tự nhiên: </span></p>
            <div class="flower-gallery">
                <?php 
                $gallery = get_field("nature_gallery");
                if ($gallery) {
                    foreach ($gallery as $image_url ) {
                        echo "<img width='150px' height='150px' src='{$image_url}'>";
                    }
                }
                ?>
            </div>

            <p style="margin-bottom: 100px;"></p>

            <p style="text-align: center"><span class="title">Phả hệ</span></p>
            <div class="flower-ancestors">
                <?php echo do_shortcode("[flower_tree_display id={$post->ID}]"); ?>
            </div>
        </div>
    </article>
    <?php
endif;

flower_plugin_get_footer();
