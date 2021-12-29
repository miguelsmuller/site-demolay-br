<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/assets/images/icons/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo('template_directory'); ?>/assets/images/icons/favicon-144.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/assets/images/icons/favicon-114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/assets/images/icons/favicon-72.png">
    <link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory'); ?>/assets/images/icons/favicon-57.png">

    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>
    <?php do_action( 'after_body' ); ?>

    <?php
    global $Class_Maintenance;
    $retorno = $Class_Maintenance->getRetorno();
    $retorno = $retorno['date'].' ás '.$retorno['time'];
    echo "<p style='text-align: center; display: block; margin-top: 50px;'>O site está em manutenção.<br/>A previsão de retorno é para <strong> $retorno; </strong></p>";
    ?>

    <div class="container">
    </div>

    <?php wp_footer(); ?>
</body>
</html>