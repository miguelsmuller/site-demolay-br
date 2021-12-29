<?php global $ClassMaintenance; ?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/assets/images/icons/favicon.ico">

        <title><?php wp_title( '|', true, 'right' ); ?></title>

        <?php wp_head();?>

        <?php
        if ( ($ClassMaintenance->getstatus() == TRUE) ) {
        ?>
            <link rel='stylesheet' href='http://scodb.site.com.br/wp-content/themes/scodb/style-maintenance.css' />
            <link rel='stylesheet' href='http://scodb.site.com.br/wp-content/themes/scodb/features/maintenance_countdown/jquery.countdown.css' />
        <?php
        }
        ?>

    </head>

<?php
$status   = $ClassMaintenance->getstatus();
$liberado = $ClassMaintenance->getAcessoLiberado();
if ( ($status == FALSE) || ($status == TRUE && $liberado == TRUE) )
    get_template_part( 'template-part/header', 'loggedin' );
?>
