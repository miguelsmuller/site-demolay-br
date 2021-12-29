<div class="row-fluid widget">
    <div class="span12">
        <div class="row-fluid widget-titulo">
            <div class="span12">
                <h4>Mais Convênios em:</h4>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="well widget-conteudo">
                    <ul>
                        <?php
                        $args = array(
                            'taxonomy'           => 'categoria_convenio',
                            'hide_empty'         => false,
                            'title_li'     => ''
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