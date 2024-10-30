<?php

$params = array(
	'page' => 'lavalinx_new_link_trade',
);

foreach (range(1, $step) as $i)
{
	$params['p'.$i] = isset($_GET['p'.$i]) ? intval($_GET['p'.$i]) : 0;
}

$param_str = http_build_query($params);

?>

<div class="wrap lavalinx">
	<h2>
		<?php if (isset($_GET['first']) && $_GET['first']): $first = '&amp;first=1'; ?>
			<strong>Step 3:</strong> Create Your First Link Trade
		<?php else: $first = ''; ?>
			Create a New Link Trade
		<?php endif; ?>
	</h2>

	<form method="get" action="admin.php" id="ll-trade-form">

	<input type="hidden" name="step" value="<?php echo $step + 1 ?>" />
	
	<?php if (isset($_GET['first'])): ?>
	<input type="hidden" name="first" value="<?php echo $_GET['first'] ?>" />
	<?php endif; ?>
	
	<?php foreach ($params as $key => $value): if ($key != 'p'.$step): ?>
	<input type="hidden" name="<?php echo $key ?>" value="<?php echo $value ?>" />
	<?php endif; endforeach; ?>

	<?php Lavalinx_View::factory('messages')->render(); ?>
	
	<h3 class="nav-tab-wrapper">
		<a href="admin.php?<?php echo $param_str ?>&amp;step=1" class="nav-tab<?php if ($step == 1) echo ' nav-tab-active' ?>">1. Your Receiving Page</a> &raquo;
		<a href="admin.php?<?php echo $param_str ?>&amp;step=2" class="nav-tab<?php if ($step == 2) echo ' nav-tab-active' ?>">2. Trader's Sending Page</a> &raquo;
		<a href="admin.php?<?php echo $param_str ?>&amp;step=3" class="nav-tab<?php if ($step == 3) echo ' nav-tab-active' ?>">3. Trader's Receiving Page</a> &raquo;
		<a href="admin.php?<?php echo $param_str ?>&amp;step=4" class="nav-tab<?php if ($step == 4) echo ' nav-tab-active' ?>">4. Your Sending Page</a> &raquo;
		<a href="admin.php?<?php echo $param_str ?>&amp;step=5" class="nav-tab<?php if ($step == 5) echo ' nav-tab-active' ?>">Review and Submit</a>
	</h3>
	
	<?php
	
	if ($step == 5)
		Lavalinx_View::factory('trade/review', array(
			'pages' => $pages, 
			'step' => $step,
			'params' => $params,
		))->render();
	elseif ($step % 2 == 1)
		Lavalinx_View::factory('trade/receiving', array(
			'pages' => $pages, 
			'step' => $step,
			'params' => $params,
		))->render();
	else
		Lavalinx_View::factory('trade/sending', array(
			'pages' => $pages, 
			'step' => $step,
			'params' => $params,
		))->render();
		
	?>
	
	<?php if (count($_POST['lavalinx_trade_page'])): foreach ($_POST['lavalinx_trade_page'] as $name => $value): ?>
	<input type="hidden" name="lavalinx_trade_page[<?php echo $name ?>]" value="<?php echo $value ?>" />
	<?php endforeach; endif; ?>
	
	</form>
</div>

<script>

(function($) {
	
	$(document).ready(function() {
	
		var rows = $('table.lavalinx-trade tbody tr[class!="ll-no-rows"]');

		$('.lavalinx .nav-tab-wrapper a').click(function() {
			
			var $this = $(this),
			    step = $this.attr('href').charAt($this.attr('href').length - 1);

			$('.lavalinx input[name=step]').val(step);
			$('.lavalinx form').trigger('submit');

			return false;
		});

		$('a', rows).click(function(e){
			
			e.stopPropagation();
		});

		rows.click(function(){
			
			rows.removeClass('unapproved');
			$(this).addClass('unapproved').find('input').attr('checked', 'checked');

			return false;
		});

		<?php if ($step == 1): ?>
			if (rows.length == 1) {
				$('input', rows).trigger('click');
				<?php if (! isset($_GET['p1'])): ?>
					$('.lavalinx form').trigger('submit');
				<?php endif; ?>
			}
		<?php endif; ?>
		
	});

})(jQuery);

</script>