<?php
/**
 * Drawer component
 *
 * @package Soul
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<button id="drawerToggle" class="btn drawer__btn" aria-expanded="false" aria-controls="drawer" aria-label="<?php echo __('Toggle menu', 'soul'); ?>" type="button">
    <span class="drawer__btn-line" id="line1"></span>
    <span class="drawer__btn-line" id="line2"></span>
</button>

<div id="drawer" class="drawer">
    <div class="drawer__bg bg-white"></div>
    <div class="drawer__container">
        <div class="drawer__main">
            <nav class="drawer__main-nav grid" id="site-navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'soul' ); ?>">
                <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'drawerNav',
                            'menu_class'     => 'nav flex-column drawer__nav',
                            'container'      => false,
                            'walker'          => new Soul_Drawer_Navwalker()
                        )
                    );
                ?>
            </nav>
        </div>
        <div class="drawer__footer">
            <div class="drawer__footer-grid grid">
                <div class="drawer__footer-col drawer__footer-col--1">
                    <a href="https://www.nikoskoulis.com/my-account/"><span>Log in/Register</span></a>
                </div>
                <div class="drawer__footer-col drawer__footer-col--2">
                    <small id="copyright">
                        <span class="me-3">© All Rights Reserved</span>
                        <span>design &amp; development by <a href="https://souldesign.gr/" target="_blank">SOUL</a></span>
                    </small>
                </div>
                <div class="drawer__footer-col drawer__footer-col--3">
                <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'social',
                            'menu_id'        => 'socialNav',
                            'menu_class'     => 'nav nav--social',
                            'container'      => false
                        )
                    );
                ?>
                </div>
                <div class="drawer__footer-col drawer__footer-col--4">
                <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'privacyNav',
                            'menu_class'     => 'nav',
                            'container'      => false
                        )
                    );
                ?>
                </div>
            </div>
        </div>
    </div>
</div>