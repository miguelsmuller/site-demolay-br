<?php get_header(); ?>
<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
<div class="container wrap">    
    <div class="row">
        
        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">
                <h2>DeMolay Service</h2>

                <div class="row-fluid">
                    <div class="page-list well span10"><ul>
                        <?php
                        //$terms = get_terms( 'lotacao' );
                        
                        $args = array(
                            'taxonomy'   => 'categoria_convenio',
                            'hide_empty' => false,
                            'title_li'   => ''
                        );
                        
                        wp_list_categories( $args );
                        
                        ?>
                    </ul></div>
                </div>                

            </div>
            </div>
            </div>
            </div>
        

        </div>
        
        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php get_template_part( 'template-part/sidebar', 'convenio' ); ?>
        </div>
    </div>  
</div>
<?php get_footer(); ?>