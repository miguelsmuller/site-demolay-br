<?php get_header(); ?>

<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">

                <h2>DeMocast</h2>

                <ul class="lista-download">
                <?php $loop_posts = new WP_Query( array('posts_per_page' => -1, 'post_type' => 'podcast', 'orderby'=> 'date', 'order'=> 'DESC') ); ?>
                <?php if ($loop_posts->have_posts()) : while ($loop_posts->have_posts()) : $loop_posts->the_post(); ?>
                
                <li class="well widget-conteudo widget-redonded widget-podcast widget-download">
                    <div class="row-fluid">
                        <div class="span12 detalhes-download">
                            <div class="pull-right">
                                <a class='btn btn-green' href='<?php echo get_post_meta($post->ID, 'urlArquivo', true); ?>' target='_blank'>Fazer download deste epsódio</a>
                            </div>
                            <h5><?php the_title(); ?></h5>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">


<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){

        $("#jquery_jplayer_<?php echo $post->ID; ?>").jPlayer({
            swfPath: "http://www.jplayer.org/latest/js/Jplayer.swf",
            ready: function () {
                $(this).jPlayer("setMedia", {
                    mp3: "<?php echo get_post_meta($post->ID, 'urlArquivo', true); ?>"
                });
            },
            play: function() { // To avoid multiple jPlayers playing together.
                $(this).jPlayer("pauseOthers");
            },

            supplied: "mp3",
            cssSelectorAncestor: "#jp_interface_<?php echo $post->ID; ?>"
        });
});
//]]>
</script>
<div id="jquery_jplayer_<?php echo $post->ID; ?>" class="jp-jplayer"></div>
<div id="jp_container_<?php echo $post->ID; ?>" class="jp-audio">
    <div class="jp-type-single">
        <div id="jp_interface_<?php echo $post->ID; ?>" class="jp-gui jp-interface">
            <ul class="jp-controls">
                <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
            </ul>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>
            <div class="jp-volume-bar">
                <div class="jp-volume-bar-value"></div>
            </div>
            <div class="jp-time-holder">
                <div class="jp-current-time"></div>
                <div class="jp-duration"></div>

                <ul class="jp-toggles">
                    <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
                    <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
                </ul>
            </div>
        </div>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>



                        </div>
                    </div>                                              
                </li>                

                <?php endwhile; else: ?>
                <?php endif; ?>
                </ul>

            </div>
            </div>
            </div>
            </div>

        </div>
        <div class="span4">
            <?php dynamic_sidebar('Sidebar Single'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>