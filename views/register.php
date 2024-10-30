<div class="wrap lavalinx lavalinx-register">
	
	<h2><strong>Step 1:</strong> Activate your website with Lavalinx</h2>
	
	<?php Lavalinx_View::factory('messages')->render(); ?>
	
	<div class="about">
		<h2>Welcome to Lavalinx</h2>
		 
		<p>The Lavalinx Wordpress plugin is a revolutionary advancement in relevant, contextual link exchange. Anyone that wants to promote their site knows that obtaining backlinks is a core piece of the SEO puzzle. With the Lavalinx plugin, users will be able to obtain relevant, hard links within WordPress posts.</p>
		 
		<h3>Here are some of the advantages:</h3>
		
		<ol>
			<li>It is totally FREE</li>
			<li>All link exchanges are created and managed within Worpress</li>
			<li>It’s the only Wordpress plugin that actually helps you get hard links with your targeted keywords</li>
			<li>All links are placed contextually within copy to get the maximum SEO benefit</li>
			<li>You never have to deal with any code, all link exchanges are automatically posted</li>
			<li>The Lavalinx plugin automatically finds relevant posts for you to get links from</li>
			<li>For advanced SEOs, functionality is in place for triangle trades</li>
		</ol>
	</div>

	<form method="post" action="" class="left-form">
		<?php if (Lavalinx::get_plugin()->pages->show_login): ?>
			<label>
				Lavalinx.com Username or Email Address:
				<input type="text" name="lavalinx_register[auth_user]" />
			</label>
			<label>
				Password:
				<input type="password" name="lavalinx_register[auth_pass]" />
				<a href="">I forgot my password</a>
			</label>
			<div class="separator"><span>OR</span></div>
			<label>
				Lavalinx API Key:
				<input type="text" name="api_key" />
				<a href="">I lost my API key</a>
			</label>
		<?php else: ?>
			<img src="<?php echo Lavalinx::API_URL ?>activation-key.php/<?php echo time() ?>.png" />
			<label>
				Enter your activation key:
				<input type="text" name="lavalinx_register[activation_key]" class="key-field" />
			</label>
		<?php endif; ?>

		<div class="terms">
			<h3>Terms and Conditions</h3>
			<?php Lavalinx_View::factory('terms')->render(); ?>
		</div>

		<input type="submit" name="lavalinx_register[agree]" class="button-primary" value="I Agree. Activate my website!" />
	</form>
	
</div>