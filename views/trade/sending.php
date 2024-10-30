<?php

$pagination = Lavalinx_View::factory('pagination', array(
	'pages' => $pages,
	'url' => 'admin.php?'.http_build_query($params).'&step='.$step.'&',
))->render(true);

?>

<p>Sending pages are pages that point links to other websites.</p>

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
			<th>Tags</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="check-column"></th>
			<th>Page</th>
			<th>Tags</th>
		</tr>
	</tfoot>
	<tbody id="the-comment-list">
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
				<a target="_blank" href="<?php echo $page->url ?>"><?php echo $page->url ?></a>
			</td>
			<td>
				<p>
					<?php echo $page->tags ?>
				</p>
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