<?php
function change_all_products() {
    if (isset( $_POST['change_all_products_field'] ) && wp_verify_nonce( $_POST['change_all_products_field'], 'change_all_products_action' )) {
        var_dump($_POST);
    } 
}
add_action('init','change_one_by_one_products');
function change_one_by_one_products() {
    var_dump($_POST);
    
    global $woocommerce;
    global $wpdb;
    /*if (isset( $_POST['change_all_products_field'] ) && wp_verify_nonce( $_POST['change_all_products_field'], 'change_all_products_action' )) {
        global $wpdb;
        $allposts = $wpdb->get_results( "SELECT DISTINCT post_parent FROM {$wpdb->prefix}posts WHERE post_type='product_variation'");
        foreach ($allposts as $key => $value) {            
            $posts[] = $value->post_parent;            
        }
        $products = $posts[];
        
    }*/
    if (isset( $_POST['change_one_by_one_products_field'] ) && wp_verify_nonce( $_POST['change_one_by_one_products_field'], 'change_one_by_one_products_action' )) {
        $products = $_POST['products'];
    }
    if(sizeof($products)) {
        foreach($products as $key => $value){       

            if (@$value['new_price']){
                $prices = []; 

                $product = wc_get_product( $key );
                $childrens = implode(",",$product->get_children()); 

                $data = $wpdb->get_results( "SELECT meta_value AS price FROM {$wpdb->prefix}postmeta WHERE meta_key='_price' AND post_id IN ({$childrens})");
                if($data && sizeof($data)) {
                    $n = 0;
                    foreach($data as $val) {
                        $prices[] = $val->price;
                        
                    }
                }
                sort($prices);
                $args = array( 
                    'post_parent' => $key,
                    'post_type' => 'product_variation'
                );

                $posts = get_posts( $args );

                if (is_array($posts) && count($posts) > 0) {

                    // Delete all the Children of the Parent Page
                    foreach($posts as $post){

                        wp_delete_post($post->ID, true);

                    }

                }
                //var_dump($prices);

                if (@$value['new_price'] == 'min'){
                    $price        = $prices[0];
                } elseif (@$value['new_price'] == 'max'){
                    $price        = end($prices);
                } else {
                    $regularPrice = $value['defined_price']; // Max regular price
                    $price        = $value['defined_price']; // Max price                        
                }
                wp_remove_object_terms( $key, 'variable', 'product_type' );
                wp_set_object_terms( $key, 'simple', 'product_type', true ); 
                delete_post_meta($key, '_price');
                delete_post_meta($key, '_regular_price');

                update_post_meta($key, '_price', $price);
                update_post_meta($key, '_regular_price', $price);
            }
            
        }
    } 
}
add_action('init','change_one_by_one_products');