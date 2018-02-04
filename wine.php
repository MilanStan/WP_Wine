<!-- THIS SHOULD BE ADDED TO FUNCTIONS.PHP OF YOUR THEME-->

<!-- SHOP PAGE -->

<!-- Removed title on pages -->
<?php
    add_filter('woocommerce_show_page_title','clear_title_shop');
    function clear_title_shop($content){
        $content='';
        return $content;
    }
?>
<!-- Added wishlist button and changed html structure of items inside div.image -->

<!-- NOTICE: You should remove functions which are hooked to woocommerce_before_shop_loop_item_title action hook and which generate html code before shop item title. I removed functions which are hooked by default woocommerce installation. After that I added function get_thumb_with_wish -->
<?php
remove_action('woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open');

remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash',10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail',10);

function get_thumb_with_wish(){
    ob_start();
    ?>
    <div class="item">
        <div class="image full-gallery">
            <a href="<?php the_permalink();?>" class="thumb">
                <?php
                    the_post_thumbnail('post_thumbnail', ['class'=>'attachment-shop-thumb-1 size-shop-thumb-1']);
                ?>
            </a>
            <div class="wish-list">
                <a href="#" class="yith-add-to-wishlist add_to_wishlist glyphicon glyphicon-heart" data-product-id="<?php the_id();?>" data-product-type="simple"></a> <!--I didn't know what is data-product-type so I put static-->
            </div>
        </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean(); 
    echo $output;    
}
add_action('woocommerce_before_shop_loop_item_title','get_thumb_with_wish');

/* Changed html structure of div.white-blok.description */ 
/*I removed functions which are hooked by default*/
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title',10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating',5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price',10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close',5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10);

function get_shop_item_desc(){
    ob_start();
    ?>
        <div class="white-block description">
            <h4 class="title">
                <a href="<?php the_permalink();?>">
                    <?php the_title();?>
                </a>
            </h4>
            <span class="type">
                <?php 
                    global $product;
                    echo $product->get_categories();
                ?>
            </span>
            <div class="divider"></div>
            <span class="type1">
                <a href="#"></a>
            </span>
            <div class="clearfix"></div>
            <span class="woocommerce-Price-amount amount">
                <span class="woocommerce-Price-currencySymbol">€</span>
                <?php
                    global $product;
                    echo $product->get_price();
                ?>
                </span>
            </span> 
            <div class="error-container"></div>
        </div> 
    <div class="loading-disabled">
        <div class="loader">
            <strong><?php _e('Adding to cart');?></strong> 
            <span></span>
            <span></span>
            <span></span>
        </div> 
    </div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean(); 
    echo $output;
}
add_action('woocommerce_shop_loop_item_title','get_shop_item_desc');
?>

<!-- SINGLE PRODUCT PAGE -->

<!-- Added images for slider and wishlist button on product image -->
<?php
function add_img_slider(){
    ob_start();
    ?>
    <div class="image-wrapper">
        <div class="wish-list">
            <a href="#" class="yith-add-too-wishlist add_to_wishlist glyphicon glyphicon-heart" data-product-id="<?php the_id();?>" data-product-type="simple"></a>
        </div>
        <div id="main-image-slider" class="owl-carousel owl-theme">
            <?php echo get_product_img_slider();?>
        </div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean(); 
    echo $output.$content;
}
function get_product_img_slider(){
    $output='';
    global $product;
    if(has_post_thumbnail()){
        $output.='
        <div class="slide-item">
            <a href="'.get_the_post_thumbnail_url(get_the_ID(),'full').'" class="woocommerce-main-image zoom" data-lightbox-gallery="main-images">'.
            get_the_post_thumbnail(). 
        '</a></div>';
    }
    $attachment_ids=$product->get_gallery_attachment_ids();
    foreach($attachment_ids as $attachment){
        $full_img=wp_get_attachment_image_src($attachment,'full');
        $image_item='
            <div class="slide-item">
            <a href="'.esc_url($full_img[0]).'" class="woocommerce-main-image zoom" data-lightbox-gallery="main-images">
            <img src="'.wp_get_attachment_image_url($attachment,'full').'"
            class="attachment-large size-large wp-post-image" alt="'.get_the_title($attachment).'" 
            srcset="'.wp_get_attachment_image_srcset($attachment).'">
            </a></div>';
        $output.=$image_item;
    } 
    return $output;   
}
/* Removed review and additional information tabs below product's images */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs',10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display',15);

/* Removed default image product and added image slider*/
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images',20);
add_action('woocommerce_before_single_product_summary', 'add_img_slider',30);

/*Removed inline image size*/
function remove_image_size_attributes( $html ) {
    return preg_replace( '/(width|height)="\d*"/', '', $html );
} 
// Remove image size attributes from post thumbnails
add_filter( 'post_thumbnail_html', 'remove_image_size_attributes' ); 
// Remove image size attributes from images added to a WordPress post
add_filter( 'image_send_to_editor', 'remove_image_size_attributes' );


/* NOTICE: You should change path to owl-init.js file inside include_owl function

Include OWL carousel neccessary for slider on product page*/
function include_owl(){
        if(is_singular('product')){
        wp_enqueue_style('owl-carousel','https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css)',null,null,'all');
        wp_enqueue_style('owl-theme','https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css', null, null,'all');
        wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js', array('jquery'), null, true);
        wp_enqueue_script('owl-init',get_stylesheet_directory_uri().'/js/owl-init.js',array('owl-carousel'),null,true);
    }
}
add_action('wp_enqueue_scripts', 'include_owl');