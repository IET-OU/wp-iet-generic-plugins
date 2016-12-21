<?php
/**
 * Bootstrap & Fontawesome default template for comments for Simple Menu plugin.
 *
 * @author  Nick Freear, 21 December 2016.
 */

  use IET_OU\WP_Generic_Plugins\Simple_Menu as SM;

  $comment_count = get_comments_number( SM::post_id() );

?>

<a class="comments-btn btn X-btn-primary" role="button" data-toggle="collapse" data-parent="#sm-menu-Z" href="#comments-<?php echo SM::post_id() ?>"
	title="Existing thoughts: <?php echo $comment_count ?>"><i class="fa fa-comments"></i>Your thoughts! <?php
	if ($comment_count): ?><b>(<?php echo $comment_count ?>)</b><?php endif; ?></a>
<div class="item-comments collapse" id="comments-<?php echo SM::post_id() ?>">
	<?php comment_form( [
		'id_form'     => 'commentform-' . SM::post_id(),
		'id_submit'   => 'submit-' . SM::post_id(),
		'class_submit'=> 'submit btn btn-primary',
		'title_reply' => '<i class="fa fa-comments"></i>Your thoughts!',
		'label_submit'=> 'Submit',
	], SM::post_id() ) ?>
<?php if ( $comment_count ): ?>
<ol class="comment-list">
	<?php
		wp_list_comments( [
			'style'       => 'ol',
			'short_ping'  => true,
			'avatar_size' => 56,
		], get_comments([ 'post_id' => SM::post_id() ]) );
	?>
</ol><!-- .comment-list -->
<?php else: ?>
	<p class="no-comments">No thoughts yet. Be the first!</p>
<?php endif; ?>
</div>
