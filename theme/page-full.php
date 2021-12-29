<?php
/*
Template Name: Page Full
*/
?>
<?php get_header(); ?>
<div class="container wrap">	
    <div class="row">
        
        <!-- AREA ESQUERDA	================================================== -->
        <div class="span12">
		<?php 
		wp_reset_query();
        if (have_posts()) : while (have_posts()) : the_post(); 
        ?>
        
            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">

            <?php
			$attr = array(
				'class'	=> "img-rounded img-polaroid top-thumbnails"
			);
							
			echo the_post_thumbnail('carousel-thumbnails',$attr);
			?>
            
            <h2 class="main-post"><?php the_title(); ?></h2>
            <?php
			$dataPublicacao = get_the_time('d/m/Y');
			$dataAlteração = get_the_modified_time('d/m/Y');
			if ($dataPublicacao != $dataAlteração){
				$data = 'Publicado em '. $dataPublicacao . ' e alterado em '.$dataAlteração;
			}else{
				$data = 'Publicado em '. $dataPublicacao;
			}
			?>
			<span class="resume-post">
			<?php echo($data); ?> | Categorias: <?php the_category(', '); ?>
			</span>
            <!-- PLUGIN FACEBOOK -->
            <div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="true" data-width="620" data-show-faces="false" data-font="tahoma"></div>
            
            <?php
			the_content();
			?>
            
            </div>
            </div>
            </div>
            </div>
            
        <?php endwhile; else: ?>
        <?php endif; ?>      
        </div>
        
    </div>  
</div>
<?php get_footer(); ?>