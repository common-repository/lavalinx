<?php if (Lavalinx::get_plugin()->pages->error): ?>
	<div class="error">
		<p>
			<strong><?php echo Lavalinx::get_plugin()->pages->error->title ?></strong> 
			<?php echo Lavalinx::get_plugin()->pages->error->text ?>
		</p>
	</div>
<?php endif; ?>

<?php if (Lavalinx::get_plugin()->pages->message): ?>
	<div class="updated">
		<p>
			<strong><?php echo Lavalinx::get_plugin()->pages->message->title ?></strong> 
			<?php echo Lavalinx::get_plugin()->pages->message->text ?>
		</p>
	</div>
<?php endif; ?>