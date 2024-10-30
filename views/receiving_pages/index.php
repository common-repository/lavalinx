<?php

$pagination = Lavalinx_View::factory('pagination', array(
	'pages' => $pages,
	'url' => 'admin.php?page=lavalinx_receiving_pages&',
))->render(true);

?>

<div class="wrap lavalinx lavalinx-receiving">
	<?php //echo get_option('lavalinx_api_key') ?>
	<h2>
		Receiving Pages
		<a href="admin.php?page=lavalinx_receiving_pages&amp;action=edit" class="add-new-h2">Add New</a>
	</h2>

	<p>Receiving Pages are pages that links are pointed to. They are usually homepages or other primary pages in your site.</p>

	<div class="tablenav top">
		<div class="alignleft actions"> 
			<?php echo $pagination ?>
		</div>
	</div>
	<table class="widefat lavalinx-receiving">
		<thead>
			<tr>
				<th>Name</th>
				<th>URL</th>
				<th>Contextual Link</th>
				<th>Tags</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Name</th>
				<th>URL</th>
				<th>Contextual Link</th>
				<th>Tags</th>
			</tr>
		</tfoot>
		<tbody id="the-comment-list">
			<?php if (! $pages): ?>
				<td colspan="4"><p>You do not have any receiving pages. Click <a href="admin.php?page=lavalinx_receiving_pages&amp;action=edit">here</a> to create one.</p></td>
			<?php endif; ?>
			<?php foreach($pages as $i => $page): ?>
			<tr class="format-default<?php if ($i % 2) echo ' alternate'; ?>">
				<td>
					<a href="admin.php?page=lavalinx_receiving_pages&amp;action=edit&amp;id=<?php echo $page->page_id ?>"><strong><?php echo $page->name ?></strong></a>
					<div class="row-actions">
						<span class="edit">
							<a href="admin.php?page=lavalinx_receiving_pages&amp;action=edit&amp;id=<?php echo $page->page_id ?>">Edit</a> | 
						</span>
						<span class="edit">
							<a href="admin.php?page=lavalinx_receiving_pages&amp;action=edit&amp;copy=<?php echo $page->page_id ?>">Copy</a> | 
						</span>
						<span class="trash">
							<a href="#" id="ll-delete-<?php echo $page->page_id ?>">Delete</a>
						</span>
					</div>
				</td>
				<td>
					<a href="<?php echo $page->url ?>" target="_blank"><?php echo $page->url ?></a>
				</td>
				<td>
					<p>
						<?php echo str_replace('<!--linkhere-->', '<a href="#">'.$page->title.'</a>', $page->description) ?>
					</p>
				</td>
				<td><?php echo $page->tags; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="tablenav bottom">
		<div class="alignleft actions">
			<?php echo $pagination ?>
		</div>
	</div>
</div>

<form method="post" action="" id="ll-delete-form">
<input type="hidden" name="lavalinx_delete_receiving_page" />
</form>

<script type="text/javascript">

jQuery('.lavalinx-receiving .trash a').click(function() {
	var id = jQuery(this).attr('id').substring(10);
	jQuery('#ll-delete-form input').val(id);
	jQuery('#ll-delete-form').trigger('submit');
	return false;
});

</script>
