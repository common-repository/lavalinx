<div class="wrap">
	<h2>Pending Trades</h2>
	<div class="tablenav top">
		<div class="alignleft actions">
			<select name="bulk">
				<option value="">Bulk Actions</option>
				<option value="">Accept</option>
				<option value="">Reject</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply" />
		</div>
	</div>
	<table class="widefat">
		<thead>
			<tr>
				<th class="check-column"><input type="checkbox" /></th>
				<th>Remote Site</th>
				<th style="text-align: right;">Remote Page</th>
				<th class="column-comments">&nbsp;</th>
				<th>My Page</th>
				<th>Type</th>
				<th>Date</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class="check-column"><input type="checkbox" /></th>
				<th>Remote Site</th>
				<th style="text-align: right;">Remote Page</th>
				<th class="column-comments">&nbsp;</th>
				<th>My Page</th>
				<th>Type</th>
				<th>Date</th>
			</tr>
		</tfoot>
		<tbody id="the-comment-list">
			<?php foreach(range(1, 8) as $i): ?>
			<tr class="format-default<?php if ($i % 2) echo ' alternate'; if ($i < 3) echo ' unapproved' ?>">
				<th scope="row" class="check-column"><input type="checkbox" /></th>
				<td>
					<strong>www.socialsecuritydeathindex-search.com</strong>
					<div class="row-actions">
						<span class="edit">
							<a href="">Accept</a> | 
						</span>
						<span class="edit">
							<a href="">Counter</a> | 
						</span>
						<span class="trash">
							<a href="">Decline</a> | 
						</span>
						<span class="edit">
							<a href="">Details</a>
						</span>
					</div>
				</td>
				<td align="right">
					<a href="">Products</a><br />
					<a href="">Social Security Death</a>
				</td>
				<td class="column-comments">
					&raquo;<br />
					&laquo;
				</td>
				<td>
					<a href="">Home</a><br />
					<a href="">About Us</a>
				</td>
				<td>Reciprocal</td>
				<td>2011/06/08</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="tablenav bottom">
		<div class="alignleft actions">
			<select name="bulk">
				<option value="">Bulk Actions</option>
				<option value="">Accept</option>
				<option value="">Reject</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply" />
		</div>
	</div>
</div>