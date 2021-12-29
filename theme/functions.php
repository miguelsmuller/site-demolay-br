<?php if ( ! defined( 'ABSPATH' ) ) exit;

// VERIFICA A EXISTENCIA DO PLUGIN COMO REQUISITO
if ( !in_array('devim-core-plugin/devim-core-plugin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))) ){
	if ( strstr($_SERVER['PHP_SELF'],'wp-login.php')) return;
    if ( strstr($_SERVER['PHP_SELF'], 'wp-admin/')) return;
    if ( strstr($_SERVER['PHP_SELF'], 'async-upload.php')) return;
    if ( strstr(htmlspecialchars($_SERVER['REQUEST_URI']), '/plugins/')) return;
    if ( strstr($_SERVER['PHP_SELF'], 'upgrade.php')) return;

    global $wp_query;
    $wp_query->set_404();

    header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');

	//wp_die( '<pre>Um plugin essencial não foi localizado.</pre>' );
}

add_filter( 'class_dashboard', '__return_true');
add_filter( 'class_maintenance', '__return_true');
add_filter( 'class_cpt_evento', '__return_true');
add_filter( 'class_cpt_arquivo', '__return_true');

// CARREGA AS CLASSES ESPECIFICAS DO TEMA
include 'inc/class-theme.php';
