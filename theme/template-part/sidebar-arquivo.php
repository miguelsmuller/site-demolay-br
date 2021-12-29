<div class="row-fluid widget">
    <div class="span12">
        <div class="row-fluid widget-titulo">
            <div class="span12">
                <h4>Mais Downloads em:</h4>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="well widget-conteudo">
                    <ul>
                    <?php
                    $args = array(
                        'taxonomy'   => 'arquivo_categoria',
                        'hide_empty' => true,
                        'title_li'   => ''
                    );                    
                    wp_list_categories( $args );                    
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php dynamic_sidebar('Sidebar Single'); ?> 