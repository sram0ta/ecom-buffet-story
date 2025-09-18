<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buffet-story
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header">
    <a href="<?= home_url(); ?>" class="header__logo">
        <img src="/wp-content/uploads/2025/09/Логотип.svg" alt="logo">
    </a>
    <nav class="header__navigation">
        <?php
        global $post;
        $menu_items = wp_get_nav_menu_items(16);

        foreach ($menu_items as $menu_item) {
            $active_class = ($menu_item->object_id == $post->ID) ? 'active' : '';
            ?>
            <a href="<?= esc_url($menu_item->url); ?>" class="header__navigation__item <?= $active_class; ?>">
                <?= esc_html($menu_item->title); ?>
            </a>
            <?php
        }
        ?>
    </nav>
    <div class="header__buttons">
        <div class="header__cart" data-count="1">
            <div class="header__cart__count">1</div>
            <svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.73463 17.773L1.2138 9.516C0.983907 8.2671 0.868961 7.6438 1.2378 7.2344C1.60538 6.825 2.28242 6.825 3.63525 6.825H22.3652C23.718 6.825 24.3951 6.825 24.7626 7.2344C25.1315 7.6438 25.0153 8.2671 24.7866 9.516L23.2658 17.773C22.7618 20.51 22.5104 21.8774 21.481 22.6893C20.4528 23.5 18.9698 23.5 16.004 23.5H9.99646C7.03059 23.5 5.54765 23.5 4.51945 22.6881C3.48999 21.8774 3.23862 20.5089 2.73463 17.7719M19.9475 6.825C19.9475 5.14751 19.2156 3.53872 17.9127 2.35255C16.6098 1.16638 14.8428 0.5 13.0002 0.5C11.1577 0.5 9.3906 1.16638 8.08773 2.35255C6.78486 3.53872 6.05291 5.14751 6.05291 6.825" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <a href="#form" class="header__button-form">Связаться с нами</a>
    </div>
</header>
