<?php if (isset($pages) && $pages): ?>

	<?php

	$record_count = $pages[0]->record_count;
	$page_count = ceil($record_count / Lavalinx::PAGE_SIZE);

	$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
	
	if ($page > $page_count)
		$page = $page_count;
		
	if ($page < 1)
		$page = 1; 

	?>

	<div class="tablenav-pages">
		
		<span class="displaying-num"><?php echo $record_count ?> items</span>
		
		<span class="pagination-links">
			
			<a class="first-page <?php if ($page <= 1) echo 'disabled' ?>" title="Go to the first page" href="<?php echo $url ?>">«</a>
			
			<a class="prev-page <?php if ($page <= 1) echo 'disabled' ?>" title="Go to the previous page" href="<?php echo $url ?>paged=<?php echo $page - 1 ?>">‹</a>
			
			<span class="paging-input">
				<?php echo $page ?>
				of <span class="total-pages"><?php echo $page_count ?></span>
			</span>
			
			<a class="next-page <?php if ($page >= $page_count) echo 'disabled' ?>" title="Go to the next page" href="<?php echo $url ?>paged=<?php echo $page + 1 ?>">›</a>
			
			<a class="last-page <?php if ($page >= $page_count) echo 'disabled' ?>" title="Go to the last page" href="<?php echo $url ?>paged=<?php echo $page_count ?>">»</a>

		</span>

	</div>

<?php endif; ?>