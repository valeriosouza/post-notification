<?php
/**
 * Notify Users E-Mail administration view.
 *
 * @package   Notify_Users_EMail_Admin
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div id="wpbody-content" aria-label="Conteúdo principal" tabindex="0">
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo __( 'Notification Rules', 'notify-users-e-mail' ); ?></h1>

        <?php /*<a href="http://localhost/wordpress/wp-admin/post-new.php" class="page-title-action disabled">Adicionar novo</a>*/?>
        <hr class="wp-header-end">
        <div class="tablenav top">
        </div>
    </div>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">Selecionar todos</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
                    <span>Título</span>
                </th>
                <th scope="col" id="author" class="manage-column column-author">Autor</th>
                <th scope="col" id="categories" class="manage-column column-categories">Categorias</th>
                <th scope="col" id="tags" class="manage-column column-tags">Tags</th>
                <th scope="col" id="comments" class="manage-column column-comments num sortable desc">
                    <span><span class="vers comment-grey-bubble" title="Comentários">
                        <span class="screen-reader-text">Comentários</span>
                    </span></span>
                </th>
                <th scope="col" id="date" class="manage-column column-date sortable asc">
                   <span>Data</span>
                </th>
            </tr>
        </thead>
        <tbody id="the-list">
            <tr id="post-1" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-uncategorized">
                <th scope="row" class="check-column">           
                <label class="screen-reader-text" for="cb-select-1">Selecionar Hello world!</label>
                <input id="cb-select-1" type="checkbox" name="post[]" value="1">
                <div class="locked-indicator">
                    <span class="locked-indicator-icon" aria-hidden="true"></span>
                    <span class="screen-reader-text">“Hello world!” está bloqueado</span>
                </div>
                </th>
                <td class="title column-title has-row-actions column-primary page-title" data-colname="Título">
                <div class="locked-info">
                    <span class="locked-avatar"></span> <span class="locked-text"></span>
                </div>
                <strong>
                    <a class="row-title" href="<?php echo get_admin_url(); ?>admin.php?page=notify-users-e-mail-settings" aria-label="“Hello world!” (Editar)">Hello world!</a>
                </strong>
                <td class="author column-author" data-colname="Autor">
                    <a href="edit.php?post_type=post&amp;author=1">admin</a>
                </td>
                <td class="categories column-categories" data-colname="Categorias">
                    <a href="edit.php?category_name=uncategorized">Uncategorized</a>
                </td>
                <td class="tags column-tags" data-colname="Tags">
                    <span aria-hidden="true">—</span><span class="screen-reader-text">Nenhuma tag</span>
                </td>
                <td class="comments column-comments" data-colname="Comentários">        
                    <div class="post-com-count-wrapper">
                    <a href="http://localhost/wordpress/wp-admin/edit-comments.php?p=1&amp;comment_status=approved" class="post-com-count post-com-count-approved"><span class="comment-count-approved" aria-hidden="true">1</span><span class="screen-reader-text">1 comentário</span></a><span class="post-com-count post-com-count-pending post-com-count-no-pending"><span class="comment-count comment-count-no-pending" aria-hidden="true">0</span><span class="screen-reader-text">Sem comentários pendentes</span></span>       </div>
                </td>
                <td class="date column-date" data-colname="Data">Publicado<br>
                    <abbr title="04/04/2017 18:56:45">5 horas atrás</abbr>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-2">Selecionar todos</label><input id="cb-select-all-2" type="checkbox">
                </td>
                <th scope="col" class="manage-column column-title column-primary sortable desc">
                   <span>Título</span>
                </th>
                <th scope="col" class="manage-column column-author">Autor</th>
                <th scope="col" class="manage-column column-categories">Categorias</th>
                <th scope="col" class="manage-column column-tags">Tags</th>
                <th scope="col" class="manage-column column-comments num sortable desc">
                    <span><span class="vers comment-grey-bubble" title="Comentários">
                        <span class="screen-reader-text">Comentários</span></span>
                    </span>
                </th>
                <th scope="col" class="manage-column column-date sortable asc">
                    <span>Data</span>
                </th>   
            </tr>
        </tfoot>
    </table>
    <br class="clear">
</div>