<?php
/*
Template Name: Каталог
*/

get_header();
?>
<main class="main page-catalog">
    <div class="catalog container">
        <h1><?php the_field('catalog_title'); ?></h1>
        <div class="catalog__category">
            <?php
            $categories = get_terms([
                'taxonomy'   => 'product_cat',
                'parent'     => 0,
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ]);
            foreach ($categories as $category) {
                ?>
                <button class="catalog__category__button" data-category="<?= esc_html($category->term_id); ?>"><?= esc_html($category->name); ?></button>
                <?php
            }
            ?>
        </div>
        <div class="catalog__inner grid-12">
            <div class="catalog__filters">
                <div class="catalog__filters__title">Фильтры и категории</div>
                <div class="catalog__filters__list">
                    <?php
                    // Родительские категории
                    $parents = get_terms([
                        'taxonomy'   => 'product_cat',
                        'parent'     => 0,
                        'hide_empty' => false,
                        'orderby'    => 'menu_order',
                        'order'      => 'ASC',
                        'pad_counts' => true,
                    ]);

                    if ( ! is_wp_error($parents) && ! empty($parents) ) :
                        foreach ($parents as $parent) :
                            // Дочерние категории
                            $children = get_terms([
                                'taxonomy'   => 'product_cat',
                                'parent'     => $parent->term_id,
                                'hide_empty' => true,
                                'orderby'    => 'name',
                                'order'      => 'ASC',
                                'pad_counts' => true,
                            ]);

                            if ( is_wp_error($children) ) {
                                continue;
                            }

                            if ( empty($children) ) : ?>
                                <button class="catalog__filters__individual" data-category="<?= esc_attr($parent->term_id); ?>">
                                    <?= esc_html($parent->name); ?>
                                </button>
                            <?php
                            else : ?>
                                <div class="catalog__filters__category" data-category="<?php echo esc_attr($parent->term_id); ?>">
                                    <div class="catalog__filters__category__title">
                                        <?php echo esc_html($parent->name); ?>
                                        <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                            <path d="M6.42828 6.34073L11.8229 1.44026C12.059 1.22523 12.059 0.876844 11.8229 0.661272C11.5867 0.446242 11.203 0.446242 10.9668 0.661272L6.0003 5.17305L1.03376 0.661816C0.797568 0.446786 0.413932 0.446786 0.177143 0.661816C-0.059048 0.876845 -0.059048 1.22577 0.177143 1.4408L5.57167 6.34127C5.80542 6.553 6.19508 6.553 6.42828 6.34073Z" fill="black"/>
                                        </svg>
                                    </div>
                                    <div class="catalog__filters__category__list">
                                        <?php foreach ($children as $child) : ?>
                                            <button class="catalog__filters__category__item" data-category="<?php echo esc_attr($child->term_id); ?>">
                                                <span class="catalog__filters__category__item__inner">
                                                    <span class="catalog__filters__category__item__icon" aria-hidden="true">
                                                        <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M14.6663 1L5.49967 10.1667L1.33301 6" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                    <span class="catalog__filters__category__item__title"><?php echo esc_html($child->name); ?></span>
                                                </span>
                                                <span class="catalog__filters__category__item__count"><?php echo (int) $child->count; ?></span>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            <div class="catalog__list grid-12">
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
                    <div class="product-item" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="product-item__image">
                        <div class="product-item__content">
                            <div class="product-item__content__inner">
                                <h3 class="product-item__content__title"><?php the_title(); ?></h3>
                                <div class="product-item__content__weight"><?= $product->get_weight(); ?> гр</div>
                            </div>
                            <p class="product-item__content__description"><?php the_field('boxing_composition'); ?></p>
                            <div class="product-item__content__price"><?= $product->get_price_html(); ?></div>
                            <div class="product-item__content__buttons">
                                <div class="product-item__content__buttons__inner">
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
    </div>
    <div class="menu container grid-12">
        <div class="menu__content">
            <div class="menu__content__inner">
                <div class="menu__content__title"><?php the_field('menu_title'); ?></div>
                <div class="menu__content__description"><?php the_field('menu_description'); ?></div>
            </div>
            <a href="#form" class="menu__content__button">Оставить заявку</a>
        </div>
        <?php
            $gallery = get_field('menu_gallery');

            foreach ($gallery as $image) {
                ?>
                    <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>" class="menu__image" loading="lazy">
                <?php
            }
        ?>
    </div>
    <div class="questions container grid-12">
        <div class="questions__title"><?php the_field('questions_title'); ?></div>
        <div class="questions__list">
            <?php
                while ( have_rows('repeater_questions') ) : the_row();
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
                while ( have_rows('repeater_questions') ) : the_row();
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
    <div class="form container grid-12">
        <div class="form__inner">
            <div class="form__item__inner"></div>
            <div class="form__title">Рассчитайте кейтеринг или доставку еды на лучших условиях прямо сейчас</div>
            <?= do_shortcode('[contact-form-7 id="6c4e621" title="Форма для заявки"]'); ?>
        </div>
        <img src="/wp-content/uploads/2025/09/iei.jpg" alt="" class="form__image" loading="lazy">
    </div>
</main>
<?php
get_footer();
