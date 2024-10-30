<p>Please review your trade and click "Submit Trade Request".</p>

<h3 class="trade-review-heading">
	Your Website
	<span>Trading With</span>
</h3>
<table class="trade-review" cellspacing="0">
	<tr>
		<td class="table-receiving-page">
			<h4><a href="<?php echo $pages[1]->url ?>" target="_blank"><?php echo $pages[1]->name ?></a></h4>
			<?php echo $pages[1]->url ?>
		</td>
		<td class="table-left-arrow"></td>
		<td class="table-sending-page">
			<h4><a href="<?php echo $pages[2]->url ?>" target="_blank"><?php echo $pages[2]->name ?></a></h4>
			<?php echo str_replace('<!--linkhere-->', '<a href="#">'.$pages[1]->title.'</a>', $pages[1]->description) ?>
		</td>
	</tr>
</table>

<table class="trade-review" cellspacing="0">
	<tr>
		<td class="table-sending-page">
			<h4><a href="<?php echo $pages[4]->url ?>" target="_blank"><?php echo $pages[4]->name ?></a></h4>
			<?php echo str_replace('<!--linkhere-->', '<a href="#">'.$pages[3]->title.'</a>', $pages[3]->description) ?>
		</td>
		<td class="table-right-arrow"></td>
		<td class="table-receiving-page">
			<h4><a href="<?php echo $pages[3]->url ?>" target="_blank"><?php echo $pages[3]->name ?></a></h4>
			<?php echo $pages[3]->url ?>
		</td>
	</tr>
</table>

<input type="submit" class="button-primary" value="Submit Trade Request" />

