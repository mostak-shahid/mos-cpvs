<?php
function change_all_products() {
    if (isset( $_POST['change_all_products_field'] ) && wp_verify_nonce( $_POST['change_all_products_field'], 'change_all_products_action' )) {
        var_dump($_POST);
    } 
}
add_action('init','change_all_products');