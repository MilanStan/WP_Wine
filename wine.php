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

remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash');
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');

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

<!-- Added wishlist button on product's image -->
<?php
    add_filter('woocommerce_single_product_image_thumbnail_html', 'add_wish_btn');
    function add_wish_btn($content){
        ob_start();
    ?>
    <div class="wish-list">
        <a href="#" class="yith-add-too-wishlist add_to_wishlist glyphicon glyphicon-heart" data-product-id="<?php the_id();?>" data-product-type="simple"></a>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean(); 
    return $output.$content;
}
/* Removed review and additional information tabs below product's images */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display');

