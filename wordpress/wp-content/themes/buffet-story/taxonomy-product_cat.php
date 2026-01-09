<?php

get_header();
?>
    <main class="main page-catalog">
        <div class="hero container container_first">
            <div class="hero__item" style="background-image: url('<?= get_field('banner_bg', 110)['url']; ?>')">
                <div class="hero__item__title"><?php the_field('banner_title', 110); ?></div>
                <div class="hero__item__buttons">
                    <a href="#shop" class="hero__item__buttons__item _green"><?php the_field('banner_title_button', 110); ?></a>
                </div>
            </div>
        </div>
        <div class="form container container_first grid-12 form-catalog">
            <div class="form__inner">
                <div class="form__title">Рассчитайте кейтеринг или доставку еды на лучших условиях прямо сейчас</div>
                <?= do_shortcode('[contact-form-7 id="6c4e621" title="Форма для заявки"]'); ?>
            </div>
            <img src="/wp-content/uploads/2025/09/iei.jpg" alt="" class="form__image" loading="lazy">
        </div>
        <div class="catalog container">
            <div class="anchor" id="shop"></div>
            <h1><?php the_field('catalog_title', 110); ?></h1>
            <div class="catalog__category">
                <?php
                $categories = get_terms([
                    'taxonomy'   => 'product_cat',
                    'parent'     => 0,
                    'hide_empty' => true,
                ]);

                foreach ($categories as $category) :
                    $term_link = get_term_link($category);
                    ?>
                    <a href="<?php echo esc_url($term_link); ?>" class="catalog__category__button" data-category-id="<?php echo esc_attr($category->term_id); ?>">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; wp_reset_postdata();?>
            </div>
            <div class="catalog__inner grid-12">
                <div class="catalog__filters">
                    <div class="catalog__filters__title">Фильтры</div>
                    <div class="catalog__filters__list">
                        <?php
                        $child_terms = get_terms([
                            'taxonomy'   => 'product-type',
                            'hide_empty' => true,
                            'parent'     => 0,
                        ]);

                        foreach ($child_terms as $child_term){
                            ?>
                            <button class="catalog__filters__category__item" data-product-category="<?= esc_attr($child_term->term_id) ?>">
                                <span class="catalog__filters__category__item__inner">
                                    <span class="catalog__filters__category__item__icon" aria-hidden="true">
                                      <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.6663 1L5.49967 10.1667L1.33301 6" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                                      </svg>
                                    </span>
                                    <span class="catalog__filters__category__item__title"><?= esc_html($child_term->name) ?></span>
                                </span>
                                <span class="catalog__filters__category__item__count"><?= $child_term->count; ?></span>
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="catalog__list grid-12">
                    <?php
                    $qo = get_queried_object();

                    $args = [
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'post_status'    => 'publish',
                    ];

                    if ( $qo && isset($qo->taxonomy) && $qo->taxonomy === 'product_cat' ) {
                        $args['tax_query'] = [[
                            'taxonomy'         => 'product_cat',
                            'field'            => 'term_id',
                            'terms'            => (int) $qo->term_id,
                            'include_children' => true,
                            'operator'         => 'IN',
                        ]];
                    }

                    $loop = new WP_Query($args);

                    if ( $loop->have_posts() ) :
                        while ( $loop->have_posts() ) : $loop->the_post();
                            global $product;
                            ?>
                            <div class="product-item">
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title_attribute(); ?>" class="product-item__image" loading="lazy">
                                </a>
                                <div class="product-item__content">
                                    <div class="product-item__content__inner">
                                        <h3 class="product-item__content__title"><?php the_title(); ?></h3>
                                        <?php if ( $product && $product->get_weight() ) : ?>
                                            <div class="product-item__content__weight"><?php echo esc_html( wc_format_weight($product->get_weight()) ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="product-item__content__description"><?php the_field('boxing_composition'); ?></p>
                                    <div class="product-item__content__price"><?php echo wp_kses_post( $product ? $product->get_price_html() : '' ); ?></div>
                                    <div class="product-item__content__buttons">
                                        <div class="product-item__content__buttons__inner" data-product-id="<?php echo esc_attr( $product ? $product->get_id() : get_the_ID() ); ?>">
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
                        <?php endwhile;

                    else :
                        echo '<div class="catalog__empty">Товары не найдены</div>';
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
        <div class="menu container grid-12">
            <div class="menu__content">
                <div class="menu__content__inner">
                    <div class="menu__content__title"><?php the_field('menu_title', 110); ?></div>
                    <div class="menu__content__description"><?php the_field('menu_description', 110); ?></div>
                </div>
                <a href="#form" class="menu__content__button">Оставить заявку</a>
            </div>
            <?php
            $gallery = get_field('menu_gallery', 110);

            foreach ($gallery as $image) {
                ?>
                <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>" class="menu__image" loading="lazy">
                <?php
            }
            ?>
        </div>
        <div class="questions container grid-12">
            <div class="questions__title"><?php the_field('questions_title', 110); ?></div>
            <div class="questions__list">
                <?php
                while ( have_rows('repeater_questions', 110) ) : the_row();
                    ?>
                    <div class="questions__item">
                        <div class="questions__item__inner">
                            <div class="questions__item__icon"></div>
                            <div class="questions__item__title"><?php the_sub_field('title'); ?></div>
                        </div>
                        <svg class="questions__item__arrow" width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1L9 9L1 17" stroke="#D1D7CE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                <?php
                endwhile;
                ?>
            </div>
            <div class="questions__content">
                <?php
                while ( have_rows('repeater_questions', 110) ) : the_row();
                    ?>
                    <div class="questions__content__item">
                        <div class="questions__content__item__inner">
                            <div class="questions__content__item__title"><?php the_sub_field('title'); ?></div>
                            <div class="questions__content__item__description"><?php the_sub_field('description'); ?></div>
                        </div>
                        <img src="<?= get_sub_field('image')['url']; ?>" alt="<?= get_sub_field('image')['alt']; ?>" class="questions__content__item__image">
                    </div>
                <?php
                endwhile;
                ?>
            </div>
        </div>
    </main>
<?php
get_footer();
