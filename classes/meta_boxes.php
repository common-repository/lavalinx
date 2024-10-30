<?php

if (! class_exists('Lavalinx_Meta_Boxes'))
{
	class Lavalinx_Meta_Boxes
	{
		public function add()
		{
			foreach (array('post', 'page') as $page)
			{				
				add_meta_box(
					'lavalinx_page_options',
					__('Lavalinx Options', 'lavalinx_textdomain'), 
					array($this, 'page_options'),
					$page, 'side'
				);
			}
		}

		public function save($post_id)
		{
			// save hide from trade option
			$previous_value = get_post_meta($post->ID, '_lavalinx_trade_hide', true);
			$value = (isset($_POST['lavalinx_options']['trade_hide']) && $_POST['lavalinx_options']['trade_hide']) ? 1 : 0;
			update_post_meta($post_id, '_lavalinx_trade_hide', $value);

			// remove from lavalinx
			if ($value && ! $previous_value)
			{
				$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/sendingpage', array(
					'method' => 'POST',
					'body' => json_encode(array(
						'delete' => 1,
						'post_id' => $post_id,
						'api_key' => get_option('lavalinx_api_key'),
					)),
				));
			}
		}
		
		public function page_options()
		{
			global $post;
			$trade_hide = get_post_meta($post->ID, '_lavalinx_trade_hide', true);

			Lavalinx_View::factory('meta_boxes/page_options', array('trade_hide' => $trade_hide))->render();
		}
	}
}