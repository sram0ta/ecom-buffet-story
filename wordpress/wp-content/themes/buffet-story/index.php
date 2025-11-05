<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package buffet-story
 */

get_header();
?>
<main class="main page-home">
    <div class="hero container container_first">
        <div class="swiper" id="banner-gallery">
            <div class="swiper-wrapper">
                <?php
                    while ( have_rows('banner_gallery') ) : the_row();
                    ?>
                        <div class="swiper-slide">
                            <div class="hero__item" style="background-image: url('<?php the_sub_field('background'); ?>')">
                                <div class="hero__item__title"><?php the_sub_field('title'); ?></div>
                                <div class="hero__item__buttons">
                                    <a href="#form" class="hero__item__buttons__item _green">Заказать кейтеринг</a>
                                    <a href="<?php the_permalink(110); ?>" class="hero__item__buttons__item _white">Смотреть меню боксов</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                ?>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="advantages container">
        <div class="advantages__inner">
            <div class="advantages__title"><?php the_field('advantages_title'); ?></div>
            <div class="advantages__navigation navigation">
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
        <div class="advantages__list">
            <div class="swiper" id="advantages-gallery">
                <div class="swiper-wrapper">
                    <?php
                        while ( have_rows('repeater_advantages') ) : the_row();
                            ?>
                                <div class="swiper-slide">
                                    <div class="advantages__item">
                                        <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="advantages__item__image" loading="lazy">
                                        <p class="advantages__item__title"><?php the_sub_field('description'); ?></p>
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
    <div class="product-block">
        <div class="product-block__title">Популярные фуршетные наборы на любой повод</div>
        <div class="product-block__list grid-12">
            <?php
                $args = [
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ];
                $loop = new WP_Query($args);

                while ($loop->have_posts()) : $loop->the_post();
                    global $product;

                    ?>
                    <div class="product-item">
                        <?php if (get_field('product_tag')): ?>
                            <div class="product-item__tag">
                                <svg class="product-item__tag__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="20" height="20" rx="10" fill="white"/>
                                    <path d="M11.334 7.91406L11.3896 8.08691H15.8887L12.3955 10.624L12.249 10.7305L12.3047 10.9033L13.6387 15.0078L10.1465 12.4717L10 12.3643L9.85352 12.4717L6.36035 15.0078L7.69531 10.9033L7.75098 10.7305L7.60449 10.624L4.11133 8.08691H8.61035L8.66602 7.91406L10 3.80762L11.334 7.91406Z" stroke="#F07D2C" stroke-width="0.5"/>
                                </svg>
                                <div class="product-item__tag__title"><?= get_field('product_tag')->name; ?></div>
                            </div>
                        <?php endif; ?>
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="product-item__image">
                        <div class="product-item__content">
                            <div class="product-item__content_wrapper">
                                <div class="product-item__content__inner">
                                    <h3 class="product-item__content__title"><?php the_title(); ?></h3>
                                    <?php
                                    if ($product->get_weight()){
                                        ?>
                                            <div class="product-item__content__weight"><?= $product->get_weight(); ?> гр</div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                if (get_field('boxing_composition')){
                                    ?>
                                        <p class="product-item__content__description"><?php the_field('boxing_composition'); ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="product-item__content_wrapper">
                                <div class="product-item__content__price"><?= $product->get_price_html(); ?></div>
                                <div class="product-item__content__buttons">
                                    <div class="product-item__content__buttons__inner" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                        <button class="product-item__content__buttons__add">В корзину</button>
                                        <div class="product-item__content__buttons__wrapper">
                                            <button class="product-item__content__buttons__count-value">-</button>
                                            <div class="product-item__content__buttons__count-number">1</div>
                                            <button class="product-item__content__buttons__count-value">+</button>
                                        </div>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="product-item__content__buttons__more">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            ?>
        </div>
    </div>
    <div class="all-bocks__inner">
        <a href="/catalog/" class="all-bocks">Смотреть все наборы</a>
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
