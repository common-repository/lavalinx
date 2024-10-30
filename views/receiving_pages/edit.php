<div class="wrap lavalinx lavalinx-receiving">
	<h2>
		<?php if (isset($_GET['first']) && $_GET['first']): $first = '&amp;first=1'; ?>
			<strong>Step 2:</strong> Create Your First Receiving Page
		<?php else: $first = ''; ?>
			<?php echo ($page->page_id && ! isset($_GET['copy'])) ? 'Edit' : 'Add New' ?> 
			Receiving Page
			<a href="admin.php?page=lavalinx_receiving_pages" class="add-new-h2">View All</a>
		<?php endif; ?>
	</h2>

	<?php Lavalinx_View::factory('messages')->render(); ?>

	<?php if (isset($_GET['first']) && $_GET['first']): ?>
		<p>Receiving Pages are pages that links are pointed to. They are 
		usually homepages or other primary pages in your site.</p>
	<?php endif; ?>

	<form method="post" action="" class="receiving">
	
		<label class="required">
			Page Title
			<input type="text" name="lavalinx_receiving_page[name]" value="<?php echo $page->name ?>" />
		</label>
		<label class="required">
			Page URL
			<input type="text" name="lavalinx_receiving_page[url]" value="<?php echo $page->url ?>" />
		</label>
		<label class="required">
			Tags <em>Separate with comma</em>
			<input type="text" name="lavalinx_receiving_page[tags]" value="<?php echo $page->tags ?>" />
		</label>

		<div class="separator"></div>

		<label>
			Text before link
			<textarea id="ll-link-before" rows="2" name="lavalinx_receiving_page[before]"><?php 
				if ($page->description):
					list($before, $after) = explode('<!--linkhere-->', $page->description);
					echo trim($before);
				endif;
			?></textarea>
		</label>
		<label class="required">
			Link anchor text
			<input type="text" id="ll-link-anchor" name="lavalinx_receiving_page[anchor]" value="<?php echo $page->title ?>" />
		</label>
		<label>
			Text after link
			<textarea id="ll-link-after" rows="2" name="lavalinx_receiving_page[after]"><?php
				if (isset($after)):
					echo trim($after);
				endif;
			?></textarea>
		</label>

		<div class="separator"></div>

		<p id="ll-preview-link"></p>

		You have <strong id="ll-char-count"></strong> characters remaining.

		<div class="separator"></div>

		<input type="submit" name="lavalinx_receiving_page[edit]" class="button-primary" value="Save Receiving Page" />

	</form>

	</div>
</div>

<script type="text/javascript">

jQuery(document).ready(function() {
	'use strict';

	var max_chars = 385,
	    over_max = false;
	
	jQuery('#ll-link-before, #ll-link-anchor, #ll-link-after').keyup(function() {
		var preview = jQuery('#ll-link-before').val().trim() + ' ',
		    count = 0;
		
		preview += '<a href="<?php bloginfo('home'); ?>" target="_blank">';
		preview += jQuery('#ll-link-anchor').val().trim() + '</a> ';
		preview += jQuery('#ll-link-after').val().trim();
		
		count = jQuery('<div>').html(preview).text().trim().length;
		if (count) {
			jQuery('#ll-preview-link').html(preview);	
		} else {
			jQuery('#ll-preview-link').html('This will be a preview of your contextual link paragraph.');
		}
		if (count <= max_chars) {
			over_max = false;
		} else {
			over_max = true;
		}
		jQuery('#ll-char-count').text(max_chars - count);
	});

	jQuery('#ll-link-anchor').trigger('keyup');

	jQuery('.lavalinx-receiving form').submit(function() {
		
		var form = jQuery(this),
		    required = form.find('label.required'),
		    required_length = required.length,
		    label,
		    i;

		for (i = 0; i < required_length; i += 1) {
			label = jQuery(required[i]);  
			if (label.find('input').val() === '') {
				alert('The ' + label.text().trim() + ' field is required.');
				return false;
			}
		}

		if (over_max) {
			alert('Your contextual link paragraph can not be more than ' + max_chars + ' characters.');
			return false;
		} 

	});
	
});

</script>
