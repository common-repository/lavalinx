<?php

$paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
$link_base = 'admin.php?page=lavalinx&amp;paged='.$paged.'&amp;';

?>

<div class="wrap">
	<h2>
		My Trades
		<a href="admin.php?page=lavalinx_new_link_trade" class="add-new-h2">New Link Trade</a>
	</h2>

	<?php Lavalinx_View::factory('messages')->render(); ?>
	
	<!-- <div class="tablenav top">
		<div class="alignleft actions">
			<?php Lavalinx_View::factory('pagination')->render(); ?>
		</div>
		<div class="alignright">
			<select name="bulk">
				<option value="">Bulk Actions</option>
				<option value="">Delete</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply" />
			<select name="filter">
				<option value="">All Trades</option>
				<option value="">Pending Link</option>
				<option value="">Complete</option>
				<option value="">Denied</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Filter" />
		</div>
	</div> -->
	<table class="widefat">
		<thead>
			<tr>
				<!-- <th class="check-column"><input type="checkbox" /></th> -->
				<th>Site Linking To Me</th>
				<th style="text-align: right;">Remote Pages</th>
				<th class="column-comments">&nbsp;</th>
				<th>My Pages</th>
				<th>Status</th>
				<th>Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<!-- <th class="check-column"><input type="checkbox" /></th> -->
				<th>Remote Site</th>
				<th style="text-align: right;">Remote Page</th>
				<th class="column-comments">&nbsp;</th>
				<th>My Page</th>
				<th>Status</th>
				<th>Date</th>
			</tr>
		</tfoot>
		<tbody id="the-comment-list">
			<?php if (! $trades): ?>
				<td colspan="6"><p>You do not have any link trades. Click <a href="admin.php?page=lavalinx_new_link_trade">here</a> to create one.</p></td>
			<?php endif; ?>
			<?php foreach($trades as $i => $trade): ?>
			<tr class="format-default<?php if ($i % 2) echo ' alternate'; if ($trade->status == 0 && ! $trade->mine) echo ' unapproved' ?>">
				<!-- <th scope="row" class="check-column"><input type="checkbox" /></th> -->
				<td>
					<strong><?php echo $trade->site_url ?></strong>
					<div class="row-actions">
						<?php if ($trade->status == 0 && ! $trade->mine): ?>
							<span class="edit">
								<a href="<?php echo $link_base ?>complete=<?php echo $trade->trade_id ?>">Accept</a> | 
							</span>
							<span class="trash">
								<a href="<?php echo $link_base ?>decline=<?php echo $trade->trade_id ?>">Decline</a>
							</span>
						<?php elseif ($trade->status == 0): ?>
							Awaiting approval | 
							<span class="trash">
								<a href="<?php echo $link_base ?>delete=<?php echo $trade->trade_id ?>">Delete</a>
							</span>
						<?php elseif ($trade->status == 2 &&  ! $trade->mine): ?>
							<span class="trash">
								<a href="<?php echo $link_base ?>delete=<?php echo $trade->trade_id ?>">Delete</a>
							</span>
						<?php elseif ($trade->status == 3): ?>
							<span class="trash">
								<a href="<?php echo $link_base ?>delete=<?php echo $trade->trade_id ?>">Delete</a>
							</span>
						<?php endif; ?>
					</div>
				</td>
				<td align="right">
					<a href="<?php echo $trade->remote_sending->url ?>" target="_blank"><?php echo $trade->remote_sending->name ?></a><br />
					<a href="<?php echo $trade->remote_receiving->url ?>" target="_blank"><?php echo $trade->remote_receiving->name ?></a>
				</td>
				<td class="column-comments">
					&rarr;<br />
					&larr;
				</td>
				<td>
					<a href="<?php echo $trade->my_receiving->url ?>" target="_blank"><?php echo $trade->my_receiving->name ?></a><br />
					<a href="<?php echo $trade->my_sending->url ?>" target="_blank"><?php echo $trade->my_sending->name ?></a>
				</td>
				<td>
					<?php if ($trade->status == 0 && $trade->mine): ?>
						Trade Sent
					<?php else: ?>
						<?php echo $trade->trade_progress ?>
					<?php endif; ?>
				</td>
				<td><?php echo date('m/d/Y', strtotime($trade->created)) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<!--<div class="tablenav bottom">
		<div class="alignleft actions">
			<?php Lavalinx_View::factory('pagination')->render(); ?>
		</div>
		<div class="alignright">
			<select name="bulk">
				<option value="">Bulk Actions</option>
				<option value="">Delete</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply" />
			<select name="filter">
				<option value="">All Trades</option>
				<option value="">Pending</option>
				<option value="">Active</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Filter" />
		</div>
	</div> -->
</div>