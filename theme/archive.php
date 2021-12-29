<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>
<main class="container" role="main">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Panel heading without title</div>
                <div class="panel-body">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/600x250.gif" class="img-responsive" alt="<?php bloginfo('name') ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Panel heading without title</div>
                <div class="panel-body">
                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/600x250.gif" class="img-responsive" alt="<?php bloginfo('name') ?>">
                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>