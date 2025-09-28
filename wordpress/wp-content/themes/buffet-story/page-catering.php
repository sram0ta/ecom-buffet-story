<?php
/*
Template Name: Кейтеринг
*/

get_header();
?>
    <main class="main page-catering">
        <div class="hero container">
            <div class="hero__item" style="background-image: url('<?= get_field('banner_bg')['url']; ?>')">
                <div class="hero__item__title"><?php the_field('banner_title'); ?></div>
                <div class="hero__item__buttons">
                    <a href="#form" class="hero__item__buttons__item _green">Получить предложение</a>
                </div>
            </div>
        </div>
        <div class="catering container">
            <h2 class="catering__title"><?php the_field('catering_title', 40); ?></h2>
            <div class="catering__list grid-12">
                <?php
                while ( have_rows('repeater_catering', 40) ) : the_row();
                    ?>
                    <div class="catering__item">
                        <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="catering__item__image" loading="lazy">
                        <div class="catering__item__content">
                            <div class="catering__item__content__inner">
                                <h3 class="catering__item__content__title"><?php the_sub_field('title'); ?></h3>
                                <p class="catering__item__content__description"><?php the_sub_field('description'); ?></p>
                            </div>
                            <button class="catering__item__content__button">Подробнее</button>
                        </div>
                    </div>
                <?php
                endwhile;
                ?>
            </div>
        </div>
        <div class="stages container">
            <div class="stages__inner">
                <div class="stages__title"><?php the_field('stages_title'); ?></div>
                <div class="stages__navigation navigation">
                    <button class="navigation__button _prev">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.397833 8.49715L8.06071 15.7984C8.18857 15.9203 8.35844 15.9883 8.53508 15.9883C8.71173 15.9883 8.8816 15.9203 9.00946 15.7984L9.01771 15.7901C9.07991 15.7311 9.12943 15.6599 9.16328 15.5811C9.19712 15.5023 9.21457 15.4174 9.21457 15.3316C9.21457 15.2458 9.19712 15.1609 9.16328 15.0821C9.12943 15.0032 9.07991 14.9321 9.01771 14.873L1.80171 7.99802L9.01771 1.12577C9.07991 1.06669 9.12943 0.995561 9.16328 0.916729C9.19712 0.837895 9.21457 0.753002 9.21457 0.667211C9.21457 0.581421 9.19712 0.496526 9.16328 0.417694C9.12943 0.338861 9.07991 0.267737 9.01771 0.208648L9.00946 0.200398C8.8816 0.0785118 8.71173 0.0105158 8.53508 0.0105158C8.35844 0.0105158 8.18857 0.0785118 8.06071 0.200398L0.397833 7.50165C0.330441 7.56586 0.276789 7.64308 0.240131 7.72864C0.203472 7.8142 0.18457 7.90632 0.18457 7.9994C0.18457 8.09248 0.203472 8.1846 0.240131 8.27016C0.276789 8.35572 0.330441 8.43294 0.397833 8.49715Z" fill="white"/>
                        </svg>
                    </button>
                    <button class="navigation__button _next">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.60314 7.50285L1.94027 0.2016C1.81241 0.0797131 1.64254 0.0117184 1.46589 0.0117184C1.28925 0.0117184 1.11938 0.079713 0.991519 0.2016L0.983268 0.20985C0.92107 0.268938 0.871543 0.340062 0.837699 0.418894C0.803855 0.497727 0.786403 0.582621 0.786403 0.668412C0.786403 0.754203 0.803855 0.839097 0.837699 0.91793C0.871543 0.996762 0.92107 1.06789 0.983268 1.12697L8.19927 8.00198L0.983268 14.8742C0.92107 14.9333 0.871543 15.0044 0.837699 15.0833C0.803855 15.1621 0.786403 15.247 0.786403 15.3328C0.786403 15.4186 0.803855 15.5035 0.837699 15.5823C0.871543 15.6611 0.92107 15.7323 0.983268 15.7914L0.991519 15.7996C1.11938 15.9215 1.28925 15.9895 1.46589 15.9895C1.64254 15.9895 1.81241 15.9215 1.94027 15.7996L9.60314 8.49835C9.67054 8.43414 9.72419 8.35692 9.76085 8.27136C9.7975 8.1858 9.81641 8.09368 9.81641 8.0006C9.81641 7.90752 9.7975 7.8154 9.76085 7.72984C9.72419 7.64428 9.67054 7.56706 9.60314 7.50285Z" fill="white"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="stages__list">
                <div class="swiper" id="stages-gallery">
                    <div class="swiper-wrapper">
                        <?php
                        while ( have_rows('repeater_stages') ) : the_row();
                            ?>
                            <div class="swiper-slide">
                                <div class="stages__item">
                                    <img src="<?= get_sub_field('icon')['url']; ?>" alt="<?= get_sub_field('icon')['alt']; ?>" class="stages__item__icon">
                                    <div class="stages__item__inner">
                                        <div class="stages__item__title"><?php the_sub_field('title'); ?></div>
                                        <div class="stages__item__description"><?php the_sub_field('description'); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form container grid-12">
            <div class="form__inner">
                <div class="form__title">Рассчитайте кейтеринг или доставку еды на лучших условиях прямо сейчас</div>
                <?= do_shortcode('[contact-form-7 id="6c4e621" title="Форма для заявки"]'); ?>
            </div>
            <img src="/wp-content/uploads/2025/09/iei.jpg" alt="" class="form__image" loading="lazy">
        </div>
        <div class="gallery container">
            <?php
                while ( have_rows('content') ) : the_row();

                    if( get_row_layout() == 'two_image' ):
                    ?>
                        <div class="gallery__two grid-12">
                            <?php
                            $two_gallery = get_sub_field('gallery');

                            foreach( $two_gallery as $two_image ){
                                ?>
                                    <img src="<?= $two_image['url']; ?>" alt="<?= $two_image['alt']; ?>" class="gallery__two__image" loading="lazy">
                                <?php
                            }
                            ?>
                        </div>
                    <?php
                    elseif( get_row_layout() == 'three_image' ):
                    ?>
                        <div class="gallery__three grid-12">
                            <?php
                            $three_gallery = get_sub_field('gallery');

                            foreach( $three_gallery as $three_image ){
                                ?>
                                    <img src="<?= $three_image['url']; ?>" alt="<?= $three_image['alt']; ?>" class="gallery__three__image" loading="lazy">
                                <?php
                            }
                            ?>
                        </div>
                    <?php
                    endif;
                endwhile;
            ?>
        </div>
        <?= get_template_part('template-part/section-platforms') ?>
        <div class="reviews container">
            <div class="reviews__title"><?php the_field('reviews_title', 10); ?></div>
            <div class="reviews__rating__inner">
                <img src="<?= get_field('reviews_image', 10)['url']; ?>" alt="<?= get_field('reviews_image', 10)['alt']; ?>" class="reviews__rating" loading="lazy">
            </div>
            <div class="reviews__all">
                <a href="<?= get_field('reviews_link', 10)['url']; ?>" target=""><?= get_field('reviews_link', 10)['title']; ?></a>
            </div>
            <div class="reviews__gallery">
                <div class="reviews__gallery__navigation navigation">
                    <button class="navigation__button _prev">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.397833 8.49715L8.06071 15.7984C8.18857 15.9203 8.35844 15.9883 8.53508 15.9883C8.71173 15.9883 8.8816 15.9203 9.00946 15.7984L9.01771 15.7901C9.07991 15.7311 9.12943 15.6599 9.16328 15.5811C9.19712 15.5023 9.21457 15.4174 9.21457 15.3316C9.21457 15.2458 9.19712 15.1609 9.16328 15.0821C9.12943 15.0032 9.07991 14.9321 9.01771 14.873L1.80171 7.99802L9.01771 1.12577C9.07991 1.06669 9.12943 0.995561 9.16328 0.916729C9.19712 0.837895 9.21457 0.753002 9.21457 0.667211C9.21457 0.581421 9.19712 0.496526 9.16328 0.417694C9.12943 0.338861 9.07991 0.267737 9.01771 0.208648L9.00946 0.200398C8.8816 0.0785118 8.71173 0.0105158 8.53508 0.0105158C8.35844 0.0105158 8.18857 0.0785118 8.06071 0.200398L0.397833 7.50165C0.330441 7.56586 0.276789 7.64308 0.240131 7.72864C0.203472 7.8142 0.18457 7.90632 0.18457 7.9994C0.18457 8.09248 0.203472 8.1846 0.240131 8.27016C0.276789 8.35572 0.330441 8.43294 0.397833 8.49715Z" fill="white"/>
                        </svg>
                    </button>
                    <button class="navigation__button _next">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.60314 7.50285L1.94027 0.2016C1.81241 0.0797131 1.64254 0.0117184 1.46589 0.0117184C1.28925 0.0117184 1.11938 0.079713 0.991519 0.2016L0.983268 0.20985C0.92107 0.268938 0.871543 0.340062 0.837699 0.418894C0.803855 0.497727 0.786403 0.582621 0.786403 0.668412C0.786403 0.754203 0.803855 0.839097 0.837699 0.91793C0.871543 0.996762 0.92107 1.06789 0.983268 1.12697L8.19927 8.00198L0.983268 14.8742C0.92107 14.9333 0.871543 15.0044 0.837699 15.0833C0.803855 15.1621 0.786403 15.247 0.786403 15.3328C0.786403 15.4186 0.803855 15.5035 0.837699 15.5823C0.871543 15.6611 0.92107 15.7323 0.983268 15.7914L0.991519 15.7996C1.11938 15.9215 1.28925 15.9895 1.46589 15.9895C1.64254 15.9895 1.81241 15.9215 1.94027 15.7996L9.60314 8.49835C9.67054 8.43414 9.72419 8.35692 9.76085 8.27136C9.7975 8.1858 9.81641 8.09368 9.81641 8.0006C9.81641 7.90752 9.7975 7.8154 9.76085 7.72984C9.72419 7.64428 9.67054 7.56706 9.60314 7.50285Z" fill="white"/>
                        </svg>
                    </button>
                </div>
                <div class="swiper" id="reviews-gallery">
                    <div class="swiper-wrapper">
                        <?php
                        $images = get_field('reviews_gallery', 10);

                        foreach ($images as $image) {
                            ?>
                            <div class="swiper-slide">
                                <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>" class="reviews__gallery__item">
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
get_footer();
