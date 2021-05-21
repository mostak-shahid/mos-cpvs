<?php

function mos_cpvs_admin_enqueue_scripts() {
    $page = @$_GET['page'];
    global $pagenow, $typenow;
    /*var_dump( $pagenow );
    //options-general.php( If under settings )/edit.php( If under post type )
    var_dump( $typenow );
    //post type( If under post type )
    var_dump( $page );
    //mos_cpvs_settings( If under settings )*/

    if ( $pagenow == 'options-general.php' AND $page == 'mos_cpvs_settings' ) {
        wp_enqueue_style( 'mos-cpvs-admin', plugins_url( 'css/mos-cpvs-admin.css', __FILE__ ) );

        //wp_enqueue_media();

        wp_enqueue_script( 'jquery' );

        wp_enqueue_script( 'mos-cpvs-functions', plugins_url( 'js/mos-cpvs-functions.js', __FILE__ ), array( 'jquery' ) );
        wp_enqueue_script( 'mos-cpvs-admin', plugins_url( 'js/mos-cpvs-admin.js', __FILE__ ), array( 'jquery' ) );
    }

}
add_action( 'admin_enqueue_scripts', 'mos_cpvs_admin_enqueue_scripts' );

function mos_cpvs_enqueue_scripts() {
    global $mos_cpvs_option;
    if ( @$mos_cpvs_option['jquery'] ) {
        wp_enqueue_script( 'jquery' );
    }
    if ( @$mos_cpvs_option['bootstrap'] ) {
        wp_enqueue_style( 'bootstrap.min', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
        wp_enqueue_script( 'bootstrap.min', plugins_url( 'js/bootstrap.min.js', __FILE__ ), array( 'jquery' ) );
    }
    if ( @$mos_cpvs_option['awesome'] ) {
        wp_enqueue_style( 'font-awesome.min', plugins_url( 'fonts/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ) );
    }
    wp_enqueue_style( 'mos-cpvs', plugins_url( 'css/mos-cpvs.css', __FILE__ ) );
    wp_enqueue_script( 'mos-cpvs-functions', plugins_url( 'js/mos-cpvs-functions.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'mos-cpvs', plugins_url( 'js/mos-cpvs.js', __FILE__ ), array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'mos_cpvs_enqueue_scripts' );

function mos_cpvs_ajax_scripts() {
    wp_enqueue_script( 'mos-cpvs-ajax', plugins_url( 'js/mos-cpvs-ajax.js', __FILE__ ), array( 'jquery' ) );
    $ajax_params = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'ajax_nonce' => wp_create_nonce( 'mos_cpvs_verify' ),
    );
    wp_localize_script( 'mos-cpvs-ajax', 'ajax_obj', $ajax_params );
}
add_action( 'wp_enqueue_scripts', 'mos_cpvs_ajax_scripts' );
add_action( 'admin_enqueue_scripts', 'mos_cpvs_ajax_scripts' );

function mos_cpvs_scripts() {
    global $mos_cpvs_option;
    if ( @$mos_cpvs_option['css'] ) {
        ?>
        <style>
        <?php echo $mos_cpvs_option['css'] ?>
        </style>
        <?php
    }
    if ( @$mos_cpvs_option['js'] ) {
        ?>
        <style>
        <?php echo $mos_cpvs_option['js'] ?>
        </style>
        <?php
    }
}
add_action( 'wp_footer', 'mos_cpvs_scripts', 100 );