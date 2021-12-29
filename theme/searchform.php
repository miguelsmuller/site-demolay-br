<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<form action="<?php bloginfo('url'); ?>" method="get" accept-charset="utf-8" role="search" >
    <div class="form-group">
        <input type="text" name="s" id="search" class="form-control" value="<?php the_search_query(); ?>" placeholder="Critério de pesquisa" />
    </div>
    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Procurar">
    <input type="hidden" name="post_type[]" value="page" />
    <input type="hidden" name="post_type[]" value="post" />
</form>