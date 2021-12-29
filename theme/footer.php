<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- FOOTER FIRST -->
<footer id="footer-first">
    <div class="container">
        <div class="row">

            <section class="footer-address">
                <img src="<?php echo get_bloginfo( 'template_directory' ) ?>/assets/images/scodb-texto-rodape.png" alt="<?php bloginfo('name') ?>">

                <ul class="social-icons animated-list">
                    <li>
                        <a href="<?php bloginfo('rss_url'); ?>" target="_blank" data-toggle="tooltip" data-original-title="Feed RSS">
                            <span class="sprites-rss"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Siga no Twitter">
                            <span class="sprites-facebook"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Página no Plus">
                            <span class="sprites-instagram"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Curta no Facebook">
                            <span class="sprites-twitter"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Veja no youtube">
                            <span class="sprites-youtube"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank" data-toggle="tooltip" data-original-title="Algo no Linkedin">
                            <span class="sprites-linkedin"></span>
                        </a>
                    </li>
                </ul>
                <address>
                    <p>Rua México, 41 - Sala 1008 - Centro - Rio de Janeiro
                    <br>CEP: 20031-905
                    <br>Tel/Fax: (21) 2456-8927</p>
                </address>
            </section>

            <section class="footer-lideranca">
                <h3>Liderança Executiva</h3>
                <ul>
                    <li>
                        <span class="role">Grande Mestre Nacional</span>
                        <br/> Halee Munoz Gilbert
                    </li>
                    <li>
                        <span class="role">Mestre Conselheiro Nacional</span>
                        <br/> Nina Pope Hobbs
                    </li>
                    <li>
                        <span class="role">Mestre Conselheiro Nacional Adjunto</span>
                        <br/> Germane Oneill Lloyd
                    </li>
                </ul>
            </section>

            <section class="footer-menu">
                <h3>Contato e Institucional</h3>
                <ul>
                    <li><a href="#">O que é a Ordem DeMolay</a></li>
                    <li><a href="#">O Nome "Ordem DeMolay"</a></li>
                    <li><a href="#">Membros da Diretoria Executiva</a></li>
                    <li><a href="#">Membros do Gabinente Juvenil Nacional</a></li>
                    <li><a href="#">Entre em contato com o SCODB</a></li>
                </ul>
            </section>
        </div>
    </div>
</footer>

<!-- FOOTER SECOND -->
<footer id="footer-second">
    <div class="container">
        <div class="row">
            <div class="footer-copyright">
                <?php echo copyright(); ?> Todos os direitos reservados ao Supremo Conselho da Ordem DeMolay para o Brasil. Este material não pode ser publicado, transmitido por broadcast, reescrito ou redistribuído sem prévia autorização.
            </div>
            <div class="footer-developed">
                <a href="http://www.devim.com.br" target="_blank">
                    <img class="footer-logo-devim" src="<?php echo get_bloginfo( 'template_directory' ) ?>/assets/images/logo-devim.png" alt="Devim - Desenvolvimento e Gestão Web">
                </a>
            </div>
        </div>
    </div>
</footer>
</div>

<!-- AUTO LOAD -->
<?php wp_footer(); ?>

<!-- LE FACEBOOK -->
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '784313788272475',
            xfbml      : true,
            version    : 'v2.1'
        });
    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/pt_BR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<!-- LE GOOGLE PLUS -->
<script type="text/javascript">
    window.___gcfg = {
        lang: 'pt-BR'
    };

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>

<!-- LE TWITTER -->
<script>
    window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
</script>

</body>
</html>