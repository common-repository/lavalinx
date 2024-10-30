<?php
/*
Plugin Name: Lavalinx
Plugin URI: http://www.lavalinx.com/plugin.lava
Description: Lavalinx is a revolutionary SEO tool for relevant, contextual link exchange.
Version: 1.1.2
Author: LavaLinx.com
Author URI: http://lavalinx.com
License: GPL2
*/

if (! class_exists('Lavalinx'))
{
	class Lavalinx
	{
		const API_URL = 'http://www.lavalinx.com/api/';
		const DISPATCH = 'dispatch.php/';
		const EXPIRATION = 600; // 10 minutes
		const PAGE_SIZE = 20;
		
		public $pages;
		public $meta_boxes;
		public $links_rendered = false;
	
		private static $_plugin;
	
		public static function get_plugin()
		{
			if (! isset(self::$_plugin))
				self::$_plugin = new Lavalinx;

			return self::$_plugin;
		}
	
		protected function __construct()
		{	
			// Require classes
			require WP_PLUGIN_DIR.'/lavalinx/classes/view.php';
			
			// Activate / Deactivate
			register_activation_hook(__FILE__, array($this, 'install'));
			
			// Actions
			add_action('admin_init', array($this, 'admin_init'));
			add_action('admin_notices', array($this, 'admin_notices'));
			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
			add_action('admin_print_styles', array($this, 'admin_print_styles'));
			add_action('save_post', array($this, 'save_post'));
			add_action('trashed_post', array($this, 'trashed_post'));

			// Filters
			add_filter('the_content', array($this, 'the_content'), 99); 
						
			// Shortcodes
			add_shortcode('lavalinx', array($this, 'shortcode'));
		}
		
		function install()
		{
			add_option('lavalinx_activate_redirect', true);
		}
		
		function admin_init()
		{
			if (get_option('lavalinx_activate_redirect', false))
			{
				delete_option('lavalinx_activate_redirect');
				wp_redirect('admin.php?page=lavalinx&action=register');
			}

			require WP_PLUGIN_DIR.'/lavalinx/classes/meta_boxes.php';
			$this->meta_boxes = new Lavalinx_Meta_Boxes; 
			
			if ($this->pages->menu)
				$this->pages->process_forms();
		}

		function save_post($post_id)
		{
			// save meta boxes
			$this->meta_boxes->save($post_id);

			if (! wp_is_post_revision($post_id) && get_post_status($post_id) == 'publish')
			{
				$post = get_post($post_id);

				if (! get_post_meta($post_id, '_lavalinx_trade_hide', true))
				{
					$page = array(
						'api_key' => get_option('lavalinx_api_key'),
						'post_id' => $post->ID,
						'name' => $post->post_title,
						'url' => get_permalink($post->ID),
						'tags' => implode(', ', wp_get_post_terms($post->ID, 'post_tag', array(
							'fields'=>'names',
						))),
					);

					$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/sendingpage', array(
						'method' => 'POST',
						'body' => json_encode($page),
					));
				}
			}
		}

		function trashed_post($post_id)
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
		
		function admin_print_styles()
		{
			wp_register_style('lavalinx_meta_boxes_css', WP_PLUGIN_URL.'/lavalinx/styles/meta_boxes.css');
			wp_enqueue_style('lavalinx_meta_boxes_css');
		}
		
		function admin_notices()
		{
			if (! get_option('lavalinx_api_key'))
			{
				Lavalinx_View::factory('notice_register')->render();
			}
		}
		
		function admin_menu()
		{
			require WP_PLUGIN_DIR.'/lavalinx/classes/pages.php';
			$this->pages = new Lavalinx_Pages;
			$this->pages->menu();
		}
		
		function add_meta_boxes()
		{
			$this->meta_boxes->add();
		}
		
		public function shortcode($atts)
		{
			global $post;

			$this->links_rendered = true;

			return $this->get_links($post->ID);
		}

		public function the_content($content)
		{
			if ($this->links_rendered)
				return $content;
			
			global $post;
			$this->links_rendered = true;

			return $content.$this->get_links($post->ID);
		}

		protected function get_links($post_id)
		{
			if (false === ($value = get_transient('lavalinx_'.$post_id)))
			{
				$response = wp_remote_get(self::API_URL.'dispatch.php/links/'.$post_id.'?api_key='.get_option('lavalinx_api_key'));
				$value = is_wp_error($response) ? false : $response['body'];

				if (false === $value)
				{
					$value = get_option('lavalinx_'.$post_id);
				}
				else
				{
					delete_option('lavalinx_'.$post_id);
					add_option('lavalinx_'.$post_id, $value, '', 'no');
				}
				set_transient('lavalinx_'.$post_id, $value, self::EXPIRATION);
			}

			$pages = json_decode($value);
			if (! $pages) $pages = array();

			foreach($pages as $page)
			{
				$links .= '<p>'.str_replace('<!--linkhere-->', '<a href="'.$page->url.'">'.$page->title.'</a>', $page->description).'</p>';
			}

			return $links;
		}
	
		public function __clone()
		{
			trigger_error('Cloning not allowed', E_USER_ERROR);
		}
	
	}
	
	Lavalinx::get_plugin();
}