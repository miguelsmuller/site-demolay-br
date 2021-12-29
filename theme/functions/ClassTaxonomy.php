<?php
/*
Name: CUOP
Description: Controle único das opções do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassTaxonomy
{
    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct(){
        /*add_action('admin_print_styles',
            array( &$this, 'admin_print_styles' )
        );

        add_filter('edit_tag_form_fields',
            array( &$this, 'taxonomy_tinycme_add_wp_editor_term' )
        );
        add_filter('edit_category_form_fields',
            array( &$this, 'taxonomy_tinycme_add_wp_editor_term' )
        );

        add_action('admin_head',
            array( &$this, 'admin_head' )
        );

        add_filter('manage_edit-post_tag_columns',
            array( &$this, 'manage_my_taxonomy_columns' )
        );
        add_filter('manage_edit-category_columns',
            array( &$this, 'manage_my_taxonomy_columns' )
        );

        remove_filter( 'pre_term_description', 'wp_filter_kses' );
        remove_filter( 'term_description', 'wp_kses_data' );*/
    }

    function admin_print_styles() { ?>
        <style type="text/css">
            .quicktags-toolbar input{width: 55px !important;}
        </style> <?php
    }

    function taxonomy_tinycme_add_wp_editor_term($tag) { ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
            <td>
                <?php
                    $settings = array(
                        'wpautop' => true,
                        'media_buttons' => true,
                        'quicktags' => true,
                        'textarea_rows' => '15',
                        'textarea_name' => 'description'
                    );
                    wp_editor(html_entity_decode($tag->description ), 'description2', $settings); ?>
            </td>
        </tr> <?php
    }

    function admin_head() {
        global $pagenow;
        //only hide on detail not yet on overview page.
        if( ($pagenow == 'edit-tags.php' && isset($_GET['action']) )) :
        ?>
            <script type="text/javascript">
                jQuery(function($) {
                    $('#description, textarea#tag-description').closest('.form-field').hide();
                });
            </script>
        <?php
        endif;
    }

    function manage_my_taxonomy_columns($columns)
    {
        global $show_description_column;
         // only edit the columns on the current taxonomy, this should be a setting.
        if ( $show_description_column)
            return $columns;

        // unset the description columns
        if ( $posts = $columns['description'] ){ unset($columns['description']); }

        return $columns;
    }
}
$ClassTaxonomy = new ClassTaxonomy();
?>
