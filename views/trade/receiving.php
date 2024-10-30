<?php

$pagination = Lavalinx_View::factory('pagination', array(
	'pages' => $pages,
	'url' => 'admin.php?'.http_build_query($params).'&step='.$step.'&',
))->render(true);

?>

<p>Receiving Pages are pages that links are pointed to. They are usually homepages or other primary pages in your site.</p>

<div class="tablenav top">
	<div class="alignleft actions">
		<?php echo $pagination ?>
	</div>
	<div class="alignright actions">
		<input type="submit" value="Next Step" class="button-primary" />
	</div>
</div>
<table class="widefat lavalinx-trade">
	<thead>
		<tr>
			<th class="check-column"></th>
			<th>Page</th>
			<th>Contextual Link</th>
			<th>Tags</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="check-column"></th>
			<th>Page</th>
			<th>Contextual Link</th>
			<th>Tags</th>
		</tr>
	</tfoot>
	<tbody id="the-comment-list">
		
		<?php if (! $pages && $step == 1): ?>
			<tr class="ll-no-rows">
				<td colspan="4"><p>You do not have any receiving pages. Click <a href="admin.php?page=lavalinx_receiving_pages&amp;action=edit">here</a> to create one.</p></td>
			</tr>
		<?php endif; ?>

		<?php foreach($pages as $i => $page): ?>

		<?php

		$checked = (isset($_GET['p'.$step]) && $page->page_id == $_GET['p'.$step]) 
		         ? 'checked'
		         : '';

		?>

		<tr class="format-default<?php if ($i % 2) echo ' alternate'; ?>">
			<th scope="row" class="check-column">
				<input type="radio" name="p<?php echo $step ?>" value="<?php echo $page->page_id ?>" <?php echo $checked ?> />
			</th>
			<td>
				<strong><?php echo $page->name ?></strong> <br />
				<a href="<?php echo $page->url ?>" target="_blank"><?php echo $page->url ?>/</a>
			</td>
			<td>
				<p>
					<?php echo str_replace('<!--linkhere-->', '<a target="_blank" href="'.$page->url.'">'.$page->title.'</a>', $page->description) ?>
				</p>
			</td>
			<td>
				<?php echo $page->tags ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div class="tablenav bottom">
	<div class="alignleft actions">
		<?php echo $pagination ?>
	</div>
	<div class="alignright actions">
		<input type="submit" value="Next Step" class="button-primary" />
	</div>
</div>