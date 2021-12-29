<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<section id="comments" class="panel panel-purple">
    <div class="panel-heading">
        <span class="comment-title">Comentários</span>
        <span class="comment-count">197</span>
    </div>
    <div class="panel-body">

        <?php if ( post_password_required() ) { ?>
            <p class="nocomments">Este artigo é protegido por senha. Insira-a para ver os comentários.</p>
        <?php return; } ?>

        <?php if ( have_comments() ) : ?>
            <ol class="commentlist">
                <?php
                    $args = array(
                        'avatar_size' => '64',
                        'type'        => 'all',
                        //'per_page'    => '3',
                        'callback'    => 'list_comment'
                    );
                    wp_list_comments($args);
                    ?>
            </ol>

                <div class="navigation">
                    <?php paginate_comments_links(); ?>
                </div>

        <?php endif; ?>
    </div>
</section>

<section id="comments-form" class="panel panel-purple">
    <div class="panel-heading">
        <span class="comment-title">Deixe seu comentário</span>
    </div>
    <div class="panel-body">
        <?php if ( comments_open() ) : ?>

            <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
                <fieldset>
                    <div class="row">
                        <?php if ( $user_ID ) : ?>
                            <p>
                                Autentificado como <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="Sair desta conta">Sair desta conta.</a>
                            </p>

                        <?php else : ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="author">*Nome: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon icon-user"></span>
                                        <input type="text" name="author" class="form-control" value="<?php echo $comment_author; ?>" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="author">*Email: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon icon-mail"></span>
                                        <input type="text" name="email" class="form-control" value="<?php echo $comment_author_email; ?>" required="required">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="url">Comentário:</label>
                                <textarea name="comment" class="form-control" rows="7"></textarea>
                            </div>

                            <input type="submit" class="btn btn-default" value="Enviar Comentário" />

                            <?php comment_id_fields(); ?>
                            <?php do_action('comment_form', $post->ID); ?>
                        </div>
                    </div>
                </fieldset>
            </form>
            <p class="cancel"><?php cancel_comment_reply_link('Cancelar Resposta'); ?></p>
        <?php else : ?>
            <h2>Os comentários estão fechados.</h2>
        <?php endif; ?>

    </div>
</section>