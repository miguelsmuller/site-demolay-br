<?php global $ClassTheme; ?>
<footer class="footer-big">
    <div class="container">
        <div class="row">
            <div class="span4">
                <div class="nome_scodb_footer"></div>
                <div class="redesSociaisFooter visible-desktop">
                    <a href="<?php bloginfo('rss_url'); ?>" target="_blank" class="social_icon rss" data-toggle="tooltip" data-original-title="Feed RSS"> </a>
                    <?php                
                    if ($ClassTheme->getFacebook() != ''){
                    ?>
                        <a href="<?php $ClassTheme->getFacebook('echo');?>" target="_blank" class="social_icon facebook" data-toggle="tooltip" data-original-title="Curta no Facebook"> </a>
                    <?php
                    }
                    ?>
                    <?php    
                    if ($ClassTheme->getTwitter() != ''){
                    ?>
                        <a href="<?php $ClassTheme->getTwitter('echo');?>" target="_blank" class="social_icon twitter" data-toggle="tooltip" data-original-title="Siga no Twitter"> </a>
                    <?php
                    }
                    ?>
                    <?php
                    if ($ClassTheme->getPlus() != ''){
                    ?>
                        <a href="<?php $ClassTheme->getPlus('echo');?>" target="_blank" class="social_icon plus" data-toggle="tooltip" data-original-title="Página no Plus"> </a>
                    <?php
                    }
                    ?>
                    <?php
                    if ($ClassTheme->getLinkedin() != ''){
                    ?>
                        <a href="<?php $ClassTheme->getLinkedin('echo');?>" target="_blank" class="social_icon linkedin" data-toggle="tooltip" data-original-title="Algo no Linkedin"> </a>
                    <?php
                    }
                    ?>
                    <?php
                    if ($ClassTheme->getYoutube() != ''){
                    ?>
                        <a href="<?php $ClassTheme->getYoutube('echo');?>" target="_blank" class="social_icon youtube" data-toggle="tooltip" data-original-title="Veja no youtube"> </a>
                    <?php
                    }
                    ?>
                    
                </div>
                <div class="endereco">
                    <?php 
                    $ClassTheme->getEndereco('echo');
                    ?>
                </div>
            </div>
            <div class="span4 visible-desktop">
                <?php
                    if ( has_nav_menu( 'menu-rodape' ) ) {                      
                        $menuOptions = array(
                            'theme_location'    => 'menu-rodape',
                            'menu'              => '',
                            'container'         => '',
                            'container_id'      => '',
                            'container_class'   => '',
                            'menu_class'        => 'paginas',
                            'menu_id'           => 'menu-rodape'            
                        );
                        wp_nav_menu($menuOptions);
                    }else{}
                ?>
                
            </div>
            <div class="span4 hidden-phone">
                <?php                
                echo '<ul class="liderancas">';
                    echo '<li>';
                        echo '<h5>Grande Mestre Nacional' .'</h5>';
                        echo '<span>'. $ClassTheme->getGMN('echo') .'</span>';
                    echo '</li>';
                    
                    echo '<li>';
                        echo '<h5>Mestre Conselheiro Nacional' .'</h5>';
                        echo '<span>'. $ClassTheme->getMCN('echo') .'</span>';
                    echo '</li>';
                    
                    echo '<li>';
                        echo '<h5>Mestre Conselheiro Nacional Adjunto' .'</h5>';
                        echo '<span>'. $ClassTheme->getMCNA('echo') .'</span>';
                    echo '</li>';
                echo '</ul>';
                ?>
            </div>
        </div>
    </div>
</footer>
<footer class="footer-small">
    <div class="container">
        <div class="row">
            <div class="span8">
                <p>Todos os direitos reservados ao Supremo Conselho da Ordem DeMolay para o Brasil. Este material não pode ser publicado, transmitido por broadcast, reescrito ou redistribuição sem prévia autorização.</p>
            </div>
            <a href="https://www.facebook.com/miguel.sneto" target="_blank"><div class="span2 offset2 desenvolvedor"></div></a>
        </div>
    </div>
</footer>
<?php 
//if (!isset($_COOKIE['firsttime']) && $maintenance == 'false' && (is_home() || is_front_page())){
if (!isset($_COOKIE['firsttime']) && (is_home() || is_front_page())){
    
    if (isset($opcoes_gerais['treinamento']) && $opcoes_gerais[ 'treinamento' ] == 1){
        ?>
        <div id="myModal" class="modal hide fade purple_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Temos novidades para você</h3>
            </div>
            <div class="modal-body">
                <?php echo $opcoes_gerais[ 'texto_treinamento' ]; ?>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Não quero ver</button>
                <button class="btn" href="javascript:void(0);" onclick="javascript:$('#myModal').modal('hide'); introJs().start();"><i class="icon-bolt"></i> Quero ver as novidades</button>
            </div>
        </div>
        <script type="text/javascript">
        $(function () {
            $('#myModal').modal('show');
        });
        </script>
        <?php
    }
}
?>

<?php wp_footer(); ?>
</body>
</html>