<?php global $ClassUser;

/*$loop = new WP_Query(array('post_type' => 'arquivo', 'posts_per_page' => -1));
while ( $loop->have_posts() ) : $loop->the_post();
    global $post;
    $postOptions = get_post_custom( $post->ID );
    $status      = isset( $postOptions['privado'] ) ? esc_attr( $postOptions['privado'][0] ) : 'indisponivel';
    if ($status == 'indisponivel'){update_post_meta($post->ID, "privado", 'iniciatico');}
    echo $post->ID . ' '. get_the_title().'<br/>';
endwhile;

die();*/

$grausUsuario = $ClassUser->getLevelUsuario();

if (have_posts()) : while (have_posts()) : the_post();

    $id                  = get_the_ID();
    $title               = get_the_title();
    $permalink           = get_permalink();
    $urlArquivo          = get_post_meta($post->ID, "urlArquivo", true) != '' ? get_post_meta($post->ID, "urlArquivo", true) : '';
    $dirArquivo          = get_post_meta($post->ID, "dirArquivo", true) != '' ? get_post_meta($post->ID, "dirArquivo", true) : '';
    $nomeArquivo         = get_post_meta($post->ID, "nomeArquivo", true) != '' ? get_post_meta($post->ID, "nomeArquivo", true) : '';
    $extArquivo          = get_post_meta($post->ID, "extArquivo", true) != '' ? get_post_meta($post->ID, "extArquivo", true) : '';
    $indisponivel             = get_post_meta($post->ID, "indisponivel", true) != '' ? get_post_meta($post->ID, "indisponivel", true) : '';
    $privado             = get_post_meta($post->ID, "privado", true) != '' ? get_post_meta($post->ID, "privado", true) : '';
    $quantidadeDownloads = get_post_meta($post->ID, "quantidadeDownloads", true) != '' ? get_post_meta($post->ID, "quantidadeDownloads", true) : '';
    //MONTA O DIR NOVAMENTE POIS ATÉ AGORA NÃO INTENDI PQ NA CLASSE TÁ MONTANDO ERRADO
    $fullname            =  WP_CONTENT_DIR . '/uploads/arquivo-post-type/'.$nomeArquivo;

if ($indisponivel == '1' ) {
    if (current_user_can( 'edit_post' )){
        $retorno = true;
    }else{
        $retorno = false;
    }
} else {
    if ( array_key_exists($privado, $grausUsuario) && is_user_logged_in() ){
        $retorno = true;
    }else{
        $retorno = false;
    }
}

/*if (is_user_logged_in()) {
    if ($privado == 'indisponivel' ) {
        $retorno = false;
    } else {
        $retorno = true;
    }
}else{
    $retorno = false;
}*/

if ( $retorno == false ) {
    header('Content-type: text/html; charset=utf-8');
    echo "<meta http-equiv='refresh' content='3;url=$permalink/action_arquivo/download/'>";
    echo '<br/>Esse download existe mais se encontra indisponível no momento. Tente novamente mais tarde.';
    //wp_redirect( wp_login_url( urlAtual() ), 302 );
} else {
    //echo 'liberado';

    if ($action_arquivo == 'download'){
        if (headers_sent()) {
            header( "refresh:5;url=get_bloginfo( 'url' )" );
            echo 'HTTP header already sent';
        }else {
            if (!is_file($fullname)) {
                header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
                echo 'File not found';
                header( "refresh:5;url=get_bloginfo( 'url' )" );

            } else if (!is_readable($fullname)) {
                header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
                echo 'File not readable';
                header( "refresh:5;url=get_bloginfo( 'url' )" );

            } else {
                update_post_meta( $id, 'quantidadeDownloads', $quantidadeDownloads + 1 );

                header($_SERVER['SERVER_PROTOCOL'].' 200 OK');

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header("Content-Disposition: attachment; filename=\"".$title.".".$extArquivo."\"");
                //header('Content-Disposition: attachment; filename="'.$title.'.'.$extArquivo.'"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: public');
                header('Pragma: no-cache');
                header('Content-Length: ' . filesize($fullname));

                //ob_end_flush();
                ob_clean();
                flush();
                @readfile($fullname);
            }
        }

    } else{
        if ($extArquivo == 'pdf'){
            update_post_meta( $id, 'quantidadeDownloads', $quantidadeDownloads + 1 );

            header('Content-type: application/pdf');
            readfile($fullname);

        }elseif ( ($extArquivo == 'jpeg') || ($extArquivo == 'jpg') || ($extArquivo == 'gif') || ($extArquivo == 'png') ){
            update_post_meta( $id, 'quantidadeDownloads', $quantidadeDownloads + 1 );

            ob_clean();
            header('Content-type: image/jpg');
            readfile($fullname);

        }else{
            header('Content-type: text/html; charset=utf-8');
            echo "<meta http-equiv='refresh' content='3;url=$permalink/action_arquivo/download/'>";
            echo '<br/>O navegador não tem suporte para abrir esse tipo de documento. <br/> Você será encaminhado ao download em alguns segundos.';
        }
    }
}

endwhile; else: endif;
?>