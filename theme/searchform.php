<?php
/*<div class="well widget-conteudo widget-busca widget-redonded">
<form action="http://demolay.org.br/resultado-da-busca/" id="cse-search-box">
  <div class="input-busca">
    <input type="hidden" name="cx" value="partner-pub-7247944374677599:8162980154" />
    <input type="hidden" name="cof" value="FORID:10" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="55" placeholder="Buscar no site" />
    <input name="sa" type="image" class="submit" src="<?php bloginfo('template_directory'); ?>/images/icons/pesquisar.png" alt="Search" >
  </div>
</form>
<div>*/
?>
<div class="well widget-conteudo widget-busca widget-redonded">
<form action="/" method="get" accept-charset="utf-8" id="searchform" role="search">
  <div class="input-busca">
    <input type="hidden" name="cx" value="partner-pub-7247944374677599:8162980154" />
    <input type="hidden" name="cof" value="FORID:10" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="s" size="55" placeholder="Buscar no site" value="<?php the_search_query(); ?>" />
    <input name="sa" type="image" class="submit" src="<?php bloginfo('template_directory'); ?>/assets/images/icons/pesquisar.png" alt="Search" >
  </div>
</form>
<div>