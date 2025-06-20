<?php
/**
* Partial template for homepage grid section
*
* @package Soul
*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
$panels = get_sub_field( 'panel' );

if ( empty( $panels ) ) {
    return; // Exit if no panels are set
}
?>

<section class="homepage__grid">
    <div class="homepage__grid-container">
        <?php 
        $c=0; 
        foreach ( $panels as $panel ) {
            $c++; 
            $item_order = 0; 
            $items = $panel['item'];
            if (!$items) continue;
        ?>
        <div class="homepage__grid-panel homepage__grid-panel--<?php echo $c; ?>">
            <div class="grid grid--section">
                <?php foreach ( $items as $item ) {
                    $item_order++;
                    $type = $item['acf_fc_layout'];
                    $link = $item['link'];
                    $card_class = '';

                    if ($type === 'image') {
                        $card_class .= ' card--tilted';
                    }

                    $speed = mt_rand(-20, 100) / 100;
                    ?>
                    <div class="homepage__grid-item homepage__grid--<?php echo $type; ?> grid__item--<?php echo $item_order; ?>" data-speed="<?php echo $speed; ?>">
                        <?php if (!empty($link)) { ?>
                        <a class="card card--<?php echo $type . $card_class; ?>" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>"> 
                        <?php } else { ?>
                        <div class="card card--<?php echo $type . $card_class; ?>"> 
                        <?php } 
                        if ($type === 'image') { 
                            $image_id = $item['image'] ?? 0;
                            if (!$image_id) {
                                continue; 
                            }
                            $image_url = wp_get_attachment_url($image_id);
                            ?>
                            <div class="card__img" style="background-image: url(<?php echo $image_url; ?>)">
                                <?php echo wp_get_attachment_image(
                                    $image_id, 
                                    'full', 
                                    false, 
                                    array('class' => 'card__img--image')
                                ); ?>
                            </div>
                        <?php } elseif ($type === 'video') {
                            $video_id = $item['video'] ?? 0;
                            $video_url = wp_get_attachment_url($video_id);
                            ?>
                            <div class="card__video">
                                <video class="card__video--video" muted="" loop="" autoplay="" playsinline="" preload="metadata" poster="<?php echo esc_url(get_the_post_thumbnail_url($video_id, 'full')); ?>">
                                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                    <?php esc_html_e('Your browser does not support the video tag.', 'soul'); ?>
                                </video>
                            </div>
                        <?php } ?>
                        <?php if (!empty($link)) {
                            if (!empty($link['title'])) { ?>
                            <div class="card__content">
                                <h3 class="card__title"><span class="card__title-text"><?php echo esc_html($link['title']); ?></span></h3>
                            </div>
                            <?php } ?>
                        </a> 
                        <?php } else { ?>
                        </div> 
                        <?php } ?>
                    </div>
                <?php } ?>   
            </div>
        </div>
        <?php } ?>
    </div>
</section>