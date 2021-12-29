<?php
/*
Name: BasicFunctions
Description: Funções utéis que podem ser usadas em qualquer parte do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

function convertem($term, $tp) {
    if ($tp == "1") $palavra = strtr(strtoupper($term),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    elseif ($tp == "0") $palavra = strtr(strtolower($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
    return $palavra;
}

/* #############################################################
# FUNÇÕES PÚBLICAS
################################################################ */
function urlAtual($retorno = 'var')
{
    global $wp;
    $currentURL = home_url(add_query_arg(array(),$wp->request));

    if ($retorno == 'echo'){
        echo $currentURL;
    } else {
        return ($currentURL);
    }
}

/* #############################################################
# FUNÇÕES PÚBLICAS
################################################################ */
function mesExtenso($mes, $tipo = 'completa', $retorno = 'var')
{
    if ($tipo == 'reduzida') {
        switch ($mes)
        {
            case  01: case  '01': $mes = 'Jan'; break;
            case  02: case  '02': $mes = 'Fec'; break;
            case  03: case  '03': $mes = 'Mar'; break;
            case  04: case  '04': $mes = 'Abr'; break;
            case  05: case  '05': $mes = 'Mai'; break;
            case  06: case  '06': $mes = 'Jun'; break;
            case  07: case  '07': $mes = 'Jul'; break;
            case  08: case  '08': $mes = 'Ago'; break;
            case  09: case  '09': $mes = 'Set'; break;
            case  10: case  '10': $mes = 'Out'; break;
            case  11: case  '11': $mes = 'Nov'; break;
            case  12: case  '12': $mes = 'Dez'; break;
        }

        if ($retorno == 'echo'){
            echo $mes;
        } else {
            return ($mes);
        }
    } else {
        switch ($mes)
        {
            case  01: case  '01': $mes = 'Janeiro'; break;
            case  02: case  '02': $mes = 'Fevereiro'; break;
            case  03: case  '03': $mes = 'Março'; break;
            case  04: case  '04': $mes = 'Abril'; break;
            case  05: case  '05': $mes = 'Maio'; break;
            case  06: case  '06': $mes = 'Junho'; break;
            case  07: case  '07': $mes = 'Julho'; break;
            case  08: case  '08': $mes = 'Agosto'; break;
            case  09: case  '09': $mes = 'Setembro'; break;
            case  10: case  '10': $mes = 'Outubro'; break;
            case  11: case  '11': $mes = 'Novembro'; break;
            case  12: case  '12': $mes = 'Dezembro'; break;
        }

        echo $mes;
    }

}

/* #############################################################
# ADICIONA TAMANHOS DE RECORTE DE IMAGEM
############################################################# */
function create_list_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
    $add_below = 'div-comment';
    }
?>
        <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">

        <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>

        <div class="comment-author vcard">
            <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation">Comentário aguardando moderação</em>
                <br />
            <?php endif; ?>
            <?php echo get_comment_author_link() ?>
        </div>

        <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
            <?php
                printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
            ?>
        </div>

        <?php comment_text() ?>

        <div class="reply">
        <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </div>
        <?php if ( 'div' != $args['style'] ) : ?>
        </div>
        <?php endif; ?>
<?php
}
?>