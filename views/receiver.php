<div class="wrap lavalinx lavalinx-receiver">
	
	<h2><strong>Step 2:</strong> Create Your First Contextual Backlink</h2>
		
	<form class="left-form">
		<h3>Links to: <a href="<?php bloginfo('home'); ?>" target="_blank">Home Page</a></h3>
		
		<label>
			<textarea id="ll-link-before" rows="3" placeholder="Text before link"></textarea>
		</label>
		<label>
			<input type="text" id="ll-link-anchor" placeholder="Link anchor text" />
		</label>
		<label>
			<textarea id="ll-link-after" rows="3" placeholder="Text after link"></textarea>
		</label>
		<input type="button" class="button-primary" value="Save Contextual Backlink" />
	</form>
	
	<p id="ll-preview-link">
		Preview your contextual link paragraph here.
	</p>
	
	You have <strong id="ll-char-count"></strong> characters remaining.
	
</div>

<script type="text/javascript">

jQuery(document).ready(function() {
	
	var max_chars = 385;
	jQuery('#ll-char-count').html(max_chars);
	
	jQuery('#ll-link-before, #ll-link-anchor, #ll-link-after').keyup(function() {
		var preview = jQuery('#ll-link-before').val().trim() + ' ';
		preview += '<a href="<?php bloginfo('home'); ?>" target="_blank">';
		preview += jQuery('#ll-link-anchor').val().trim() + '</a> ';
		preview += jQuery('#ll-link-after').val().trim();
		jQuery('#ll-preview-link').html(preview);
		jQuery('#ll-char-count').text(max_chars - preview.length);
	});
	
});

</script>