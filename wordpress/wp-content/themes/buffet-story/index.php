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
    <div class="hero container">
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
        <div class="advantages__title"><?php the_field('advantages_title'); ?></div>
        <div class="advantages__list">
            <?php
                while ( have_rows('repeater_advantages') ) : the_row();
                    ?>
                        <div class="advantages__item">
                            <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="advantages__item__image" loading="lazy">
                            <p class="advantages__item__title"><?php the_sub_field('description'); ?></p>
                        </div>
                    <?php
                endwhile;
            ?>
        </div>
    </div>
    <div class="form container grid-12">
        <div class="form__inner">
            <div class="form__title">Рассчитайте кейтеринг или доставку еды на лучших условиях прямо сейчас</div>
            <div class="form__list">
                <div class="form__item">
                    <div class="form__item__title">Ваше имя</div>
                    <input type="text" class="form__item__input" placeholder="Иванов Иван Иванович">
                </div>
            </div>
            <div class="form__list">
                <div class="form__item">
                    <div class="form__item__title">Ваше имя</div>
                    <input type="text" class="form__item__input" placeholder="Иванов Иван Иванович">
                </div>
                <div class="form__item">
                    <div class="form__item__title">Ваше имя</div>
                    <input type="text" class="form__item__input" placeholder="Иванов Иван Иванович">
                </div>
            </div>
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
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="product-item__image">
                        <div class="product-item__content">
                            <div class="product-item__content__inner">
                                <h3 class="product-item__content__title"><?php the_title(); ?></h3>
                                <div class="product-item__content__weight"><?= $product->get_weight(); ?> гр</div>
                            </div>
                            <p class="product-item__content__description"><?php the_field('boxing_composition'); ?></p>
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
                    <?php
                endwhile;
                wp_reset_postdata();
            ?>
        </div>
    </div>
    <div class="all-bocks__inner">
        <a href="#" class="all-bocks">Смотреть все наборы</a>
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
    <div class="platforms container">
        <div class="platforms__title">Предлагаем площадки под ваше мероприятия на лучших условиях</div>
        <div class="platforms__list grid-12">
            <?php
            $my_posts = get_posts( array(
                'numberposts' => -1,
                'post_type'   => 'platforms',
                'suppress_filters' => true,
            ) );

            foreach( $my_posts as $post ){
                setup_postdata( $post );
                ?>
                <div class="platforms__item">
                    <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="platforms__item__image" loading="lazy">
                    <div class="platforms__item__information">
                        <div class="platforms__item__information__title"><?php the_title(); ?></div>
                        <div class="platforms__item__information__inner">
                            <div class="platforms__item__information__paragraph">
                                <div class="platforms__item__information__paragraph__icon">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.79994 5.03995L9.98711 1.14187C11.1808 0.573168 12.4264 1.8194 11.8584 3.01368L7.95998 11.1995C7.42704 12.318 5.81207 12.2491 5.37673 11.0886L4.65632 9.16551C4.58593 8.9779 4.4762 8.80753 4.3345 8.66585C4.1928 8.52416 4.02241 8.41443 3.83479 8.34405L1.91088 7.62299C0.750911 7.18768 0.681397 5.57285 1.79994 5.03995Z" stroke="white" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="platforms__item__information__paragraph__title"><?php the_field('address'); ?></div>
                            </div>
                            <div class="platforms__item__information__paragraph">
                                <div class="platforms__item__information__paragraph__icon">
                                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.33398 4.91406L8.38965 5.08691H12.8887L9.39551 7.62402L9.24902 7.73047L9.30469 7.90332L10.6387 12.0078L7.14648 9.47168L7 9.36426L6.85352 9.47168L3.36035 12.0078L4.69531 7.90332L4.75098 7.73047L4.60449 7.62402L1.11133 5.08691H5.61035L5.66602 4.91406L7 0.807617L8.33398 4.91406Z" stroke="white" stroke-width="0.5"/>
                                    </svg>
                                </div>
                                <div class="platforms__item__information__paragraph__title"><?php the_field('type'); ?></div>
                            </div>
                            <div class="platforms__item__information__paragraph">
                                <div class="platforms__item__information__paragraph__icon">
                                    <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 5.5L8 1L15 5.5M13.5 4.5V12H2.5V4.5M6.5 8H9.5V12H6.5V8Z" stroke="white" stroke-width="0.5" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="platforms__item__information__paragraph__title"><?php the_field('square'); ?></div>
                            </div>
                            <div class="platforms__item__information__paragraph">
                                <div class="platforms__item__information__paragraph__icon">
                                    <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 2.75C2 2.45453 2.0582 2.16194 2.17127 1.88896C2.28434 1.61598 2.45008 1.36794 2.65901 1.15901C2.86794 0.950078 3.11598 0.784344 3.38896 0.671271C3.66194 0.558198 3.95453 0.5 4.25 0.5C4.54547 0.5 4.83806 0.558198 5.11104 0.671271C5.38402 0.784344 5.63206 0.950078 5.84099 1.15901C6.04992 1.36794 6.21566 1.61598 6.32873 1.88896C6.4418 2.16194 6.5 2.45453 6.5 2.75C6.5 3.34674 6.26295 3.91903 5.84099 4.34099C5.41903 4.76295 4.84674 5 4.25 5C3.65326 5 3.08097 4.76295 2.65901 4.34099C2.23705 3.91903 2 3.34674 2 2.75ZM4.25 0C3.52065 0 2.82118 0.289731 2.30546 0.805456C1.78973 1.32118 1.5 2.02065 1.5 2.75C1.5 3.47935 1.78973 4.17882 2.30546 4.69454C2.82118 5.21027 3.52065 5.5 4.25 5.5C4.97935 5.5 5.67882 5.21027 6.19454 4.69454C6.71027 4.17882 7 3.47935 7 2.75C7 2.02065 6.71027 1.32118 6.19454 0.805456C5.67882 0.289731 4.97935 0 4.25 0ZM9 3.5C9 3.10218 9.15804 2.72064 9.43934 2.43934C9.72064 2.15804 10.1022 2 10.5 2C10.8978 2 11.2794 2.15804 11.5607 2.43934C11.842 2.72064 12 3.10218 12 3.5C12 3.89782 11.842 4.27936 11.5607 4.56066C11.2794 4.84196 10.8978 5 10.5 5C10.1022 5 9.72064 4.84196 9.43934 4.56066C9.15804 4.27936 9 3.89782 9 3.5ZM10.5 1.5C9.96957 1.5 9.46086 1.71071 9.08579 2.08579C8.71071 2.46086 8.5 2.96957 8.5 3.5C8.5 4.03043 8.71071 4.53914 9.08579 4.91421C9.46086 5.28929 9.96957 5.5 10.5 5.5C11.0304 5.5 11.5391 5.28929 11.9142 4.91421C12.2893 4.53914 12.5 4.03043 12.5 3.5C12.5 2.96957 12.2893 2.46086 11.9142 2.08579C11.5391 1.71071 11.0304 1.5 10.5 1.5ZM1.5 6.5C1.10218 6.5 0.720644 6.65804 0.43934 6.93934C0.158035 7.22064 0 7.60218 0 8C0 8.558 0.2085 9.315 0.8595 9.9315C1.512 10.5495 2.58 11 4.2495 11C5.919 11 6.988 10.55 7.6405 9.9315C8.2915 9.315 8.5 8.558 8.5 8C8.5 7.60218 8.34196 7.22064 8.06066 6.93934C7.77936 6.65804 7.39782 6.5 7 6.5H1.5ZM0.5 8C0.5 7.73478 0.605357 7.48043 0.792893 7.29289C0.98043 7.10536 1.23478 7 1.5 7H7C7.26522 7 7.51957 7.10536 7.70711 7.29289C7.89464 7.48043 8 7.73478 8 8C8 8.442 7.8335 9.06 7.297 9.5685C6.762 10.0755 5.83 10.5 4.25 10.5C2.67 10.5 1.738 10.075 1.203 9.5685C0.6665 9.06 0.5 8.442 0.5 8ZM8.9815 9.294C8.91817 9.44467 8.84117 9.59467 8.7505 9.744C9.2135 9.9025 9.789 10 10.5 10C11.921 10 12.8005 9.6115 13.327 9.12C13.848 8.634 14 8.067 14 7.75C14 7.41848 13.8683 7.10054 13.6339 6.86612C13.3995 6.6317 13.0815 6.5 12.75 6.5H8.677C8.811 6.65 8.9255 6.818 9.016 7H12.75C12.9489 7 13.1397 7.07902 13.2803 7.21967C13.421 7.36032 13.5 7.55109 13.5 7.75C13.5 7.9335 13.402 8.3665 12.9855 8.755C12.575 9.1385 11.829 9.5 10.5 9.5C9.8725 9.5 9.375 9.4195 8.9815 9.294Z" fill="white"/>
                                    </svg>
                                </div>
                                <div class="platforms__item__information__paragraph__title"><?php the_field('capacity'); ?></div>
                            </div>
                        </div>
                        <a href="#form" class="platforms__item__information__more">Подробнее</a>
                    </div>
                </div>
                <?php
            } wp_reset_postdata();
            ?>
        </div>
    </div>
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
