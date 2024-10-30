<?php

class Lavalinx_Pages
{
	public $error;
	public $message;
	public $show_login;
	public $menu;
	
	public function menu()
	{
		$this->menu = array();
		
		if (get_option('lavalinx_api_key'))
		{
			// build menu
			$this->menu[] = add_menu_page('Lavalinx', 'Lavalinx'.$this->get_pending_count(),
				'manage_options', 'lavalinx', array($this, 'index'));
			
			$this->menu[] = add_submenu_page('lavalinx', 'My Trades - Lavalinx', 
				'My Trades', 'manage_options', 'lavalinx', 
				array($this, 'index'));

			$this->menu[] = add_submenu_page('lavalinx', 'New Link Trade - Lavalinx', 
				'New Link Trade', 'manage_options', 'lavalinx_new_link_trade', 
				array($this, 'new_link_trade'));

			$this->menu[] = add_submenu_page('lavalinx', 'Receiving Pages - Lavalinx', 
				'Receiving Pages', 'manage_options', 
				'lavalinx_receiving_pages', array($this, 'receiving_pages'));
				
			$this->menu[] = add_submenu_page('lavalinx', 'Feedback - Lavalinx', 
				'Feedback', 'manage_options', 
				'lavalinx_feedback', array($this, 'feedback'));
		}
		else
		{
			$this->menu[] = add_menu_page('Lavalinx', 'Activate Lavalinx',
				'manage_options', 'lavalinx', array($this, 'register'));
		}

		// add styles to all lavalinx pages
		foreach ($this->menu as $page)
			add_action('admin_print_styles-'.$page, array($this, 'styles'));
	}
	
	public function styles()
	{
		wp_register_style('lavalinx_admin_css', WP_PLUGIN_URL.'/lavalinx/styles/admin.css');
		wp_enqueue_style('lavalinx_admin_css');
	}
	
	protected function get_pending_count()
	{
		
		if (false === ($value = get_transient('lavalinx_pending_count')))
		{
			$response = wp_remote_get(Lavalinx::API_URL.'dispatch.php/trades?request_count=1&api_key='.get_option('lavalinx_api_key'));
			$value = is_wp_error($response) ? false : $response['body'];

			if (false === $value)
			{
				$value = get_option('lavalinx_'.$post_id);
			}
			else
			{
				delete_option('lavalinx_pending_count');
				add_option('lavalinx_pending_count', $value, '', 'no');
			}
			set_transient('lavalinx_pending_count', $value, Lavalinx::EXPIRATION);
		}

		return Lavalinx_View::factory('awaiting_mod', array('count' => $value))->render(true);
	}
	
	public function process_forms()
	{
		// register
		if (isset($_POST['lavalinx_register']['agree']))
		{
			global $current_user;
			get_currentuserinfo();
			
			$data = array(
				'name' => $current_user->user_firstname.' '.$current_user->user_lastname,
				'email' => $current_user->user_email,
				'url' => get_home_url(),
				'account_name' => get_bloginfo('name'),
				'tags' => get_terms('category', array('fields'=>'names')),
			);
			
			if (isset($_POST['lavalinx_register']['activation_key']))
				$data['activation_key'] = $_POST['lavalinx_register']['activation_key'];
				
			if (isset($_POST['lavalinx_register']['auth_user']))
				$data['auth_user'] = $_POST['lavalinx_register']['auth_user'];
				
			if (isset($_POST['lavalinx_register']['auth_pass']))
				$data['auth_pass'] = $_POST['lavalinx_register']['auth_pass'];
			
			$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/user', array(
				'method' => 'POST',
				'body' => json_encode($data),
			));
			
			if (is_wp_error($response))
			{
				exit($response->get_error_message());
			}
			
			$status = $response['response']['code'];
			$user = json_decode($response['body']);
			
			if ($status == 302)
			{
				$this->message = (object) array(
					'title' => 'Site already Registered.',
					'text' => 'Please login with your Lavalinx.com username and password or enter your API key.',
				);
				$this->show_login = true;
			}
			elseif ($status == 401)
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'The activation key you entered is not valid. Please try again.',
				);
			}
			elseif ($status == 403)
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'You do not have access to this account',
				);
				$this->show_login = true;
			}
			elseif ($status == 404)
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'Incorrect Username/Password or API Key',
				);
				$this->show_login = true;
			}
			elseif ($status == 400)
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'Unable to activate. Please contact Lavalinx.',
				);
			}
			else
			{
				// Everything worked
				update_option('lavalinx_api_key', $user->api_key);

				// Add all pages as sending pages

				$posts = get_posts(array(
					'numberposts' => 100,
					'post_type'=>array('post', 'page'),
				));
				
				$sending = array();
				
				foreach ($posts as $post)
				{
					$sending[] = array(
						'post_id' => $post->ID,
						'name' => $post->post_title,
						'url' => get_permalink($post->ID),
						'tags' => implode(', ', wp_get_post_terms($post->ID, 'post_tag', array(
							'fields'=>'names',
						))),
					);
				}

				$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/sendingpages', array(
					'method' => 'POST',
					'body' => json_encode(array('api_key' => $user->api_key, 'items'=>$sending)),
				));
				
				wp_redirect(admin_url().'admin.php?page=lavalinx_receiving_pages&action=edit&first=true');
				
			}
		}
		
		// send feedback
		if (isset($_POST['lavalinx_feedback']))
		{
			$data = array(
				'feedback' => $_POST['lavalinx_feedback'],
				'api_key' => get_option('lavalinx_api_key'),
				);
			
			$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/feedback', array(
				'method' => 'POST',
				'body' => json_encode($data),
			));
			
			$status = $response['response']['code'];
			$feedback = json_decode($response['body']);
			
			if ($status == 201)
			{
				$this->message = (object) array(
					'title' => 'Thank you!',
					'text' => 'We have received your feedback and will contact you if we have any questions.',
				);
			}
			else
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'Unable to send feedback. Please try again later.',
				);
			}
		}

		// save link trade
		if (isset($_GET['step']) && $_GET['step'] == 6 && $_GET['page'] == 'lavalinx_new_link_trade' && isset($_GET['p1']) && isset($_GET['p2']) && isset($_GET['p3']) && isset($_GET['p4']))
		{
			$data = array(
				'my_receiving' => $_GET['p1'],
				'remote_sending' => $_GET['p2'],
				'remote_receiving' => $_GET['p3'],
				'my_sending' => $_GET['p4'],
				'api_key' => get_option('lavalinx_api_key'),
			);

			$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/trade', array(
				'method' => 'POST',
				'body' => json_encode($data),
			));

			if ($response['response']['code'] == 201)
			{
				wp_redirect(admin_url().'admin.php?page=lavalinx');
			}
			else
			{
				$_GET['step'] = 5;
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'Unable to save link trade. Please try again.',
				);
			}
			
		}

		// save receiving page
		if (isset($_POST['lavalinx_receiving_page']))
		{
			$data = array_map('stripslashes', $_POST['lavalinx_receiving_page']);
			$data['api_key'] = get_option('lavalinx_api_key');

			isset($_GET['id']) || $_GET['id'] = 0;
			
			$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/receivingpage/'.$_GET['id'], array(
				'method' => 'POST',
				'body' => json_encode($data),
			));

			//exit($response['body']);
			
			$status = $response['response']['code'];
			$feedback = json_decode($response['body']);
			
			if ($status == 201 || $status == 200)
			{
				if (isset($_GET['first']) && $_GET['first'])
					wp_redirect(admin_url().'admin.php?page=lavalinx_new_link_trade&first=true');
				else
					wp_redirect(admin_url().'admin.php?page=lavalinx_receiving_pages');
			}
			else
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'Unable to save sending page. Please try again later.'.$response['body'].$response['response']['code'],
				);
			}
		}
		if (isset($_POST['lavalinx_delete_receiving_page']))
		{
			$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/receivingpage/'.$_POST['lavalinx_delete_receiving_page'], array(
				'method' => 'POST',
				'body' => json_encode(array(
					'delete' => 1,
					'api_key' => get_option('lavalinx_api_key'),
				)),
			));

			if (is_wp_error($response))
			{
				exit($response->get_error_message());
			}
			wp_redirect(admin_url().'admin.php?page=lavalinx_receiving_pages');
		}
	}
	
	public function register()
	{		
		Lavalinx_View::factory('register')->render();
	}
	
	public function receiver()
	{
		Lavalinx_View::factory('receiver')->render();
	}
	
	public function index()
	{
		$action = '';
		$id = 0;
		
		if (isset($_GET['complete']))
		{
			$action = 'complete';
			$id = intval($_GET['complete']);
		} elseif (isset($_GET['decline']))
		{
			$action = 'decline';
			$id = intval($_GET['decline']);
		} elseif (isset($_GET['delete']))
		{
			$action = 'delete';
			$id = intval($_GET['delete']);
		}

		if ($action)
		{
			$data = array(
				'action' => $action,
				'api_key' => get_option('lavalinx_api_key'),
			);

			if ($action == 'delete')
			{
				$data['delete'] = 1;
			}

			$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/trade/'.$id, array(
				'method' => 'POST',
				'body' => json_encode($data),
			));

			if ($response['response']['code'] == 200)
			{
				$action_titles = array(
					'complete' => 'Trade Completed.',
					'decline' => 'Trade Declined.',
					'delete' => 'Trade Deleted.'
				);

				$this->message = (object) array(
					'title' => $action_titles[$action],
					'text' => '',
				);
			}
			else
			{
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'Could not update trade status.',
				);
			}
		}

		$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/trades?api_key='.get_option('lavalinx_api_key'), array(
			'method' => 'GET'
		));
		
		if (is_wp_error($response))
			exit($response->get_error_message());
		
		//exit($response['body']);

		$trades = json_decode($response['body']);

		Lavalinx_View::factory('index', array('trades' => $trades))->render();
	}
	
	public function new_link_trade()
	{
		if (! isset($_GET['step']) || intval($_GET['step']) <= 0 || $_GET['step'] > 5)
			$step = 1;
		else
			$step = $_GET['step'];

		$i = 1; while ($i < $step)
		{
			if (! isset($_GET['p'.$i]) || ! $_GET['p'.$i])
			{
				$step = $i;
				$this->error = (object) array(
					'title' => 'Error:',
					'text' => 'You must select a page before you can continue.',
				);
				break;
			}

			$i += 1;
		}

		$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
		
		switch($step)
		{
			case 1:
				$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/receivingpages?page='.$page.'&api_key='.get_option('lavalinx_api_key'), array(
					'method' => 'GET'
				));
				
				if (is_wp_error($response))
					exit($response->get_error_message());
				
				$pages = json_decode($response['body']);
				break; 
			case 2:
				$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/sendingpages?page='.$page.'&related_to='.$_GET['p1'].'&api_key='.get_option('lavalinx_api_key'), array(
					'method' => 'GET'
					));
				
				if (is_wp_error($response))
					exit($response->get_error_message());

				$pages = json_decode($response['body']);
				break;
			case 3:
				$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/receivingpages?page='.$page.'&page_site='.$_GET['p2'].'&api_key='.get_option('lavalinx_api_key'), array(
					'method' => 'GET'
					));
				
				if (is_wp_error($response))
					exit($response->get_error_message());

				$pages = json_decode($response['body']);
				break;
			case 4:
				$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/sendingpages?page='.$page.'&page_site=true&related_to='.$_GET['p3'].'&api_key='.get_option('lavalinx_api_key'), array(
					'method' => 'GET'
					));
				
				if (is_wp_error($response))
					exit($response->get_error_message());

				$pages = json_decode($response['body']);
				break;
			case 5:
				$pages = array();
				for ($i = 1; $i <= 4; $i += 1)
				{
					$page_type = ($i % 2) ? 'receiving' : 'sending';
					$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/'.$page_type.'page/'.$_GET['p'.$i].'?api_key='.get_option('lavalinx_api_key'), array(
						'method' => 'GET'
					));
					
					if (is_wp_error($response))
						exit($response->get_error_message());

					$pages[$i] = json_decode($response['body']);
				}
				break;
		}
		
		Lavalinx_View::factory('trade/main', array('pages' => $pages, 'step' => $step))->render();
	}
	
	public function pending_trades()
	{
		Lavalinx_View::factory('pending_trades')->render();
	}

	public function receiving_pages()
	{
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		
		switch($action)
		{
			case 'edit': return $this->edit_receiving_page();
		}

		$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

		$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/receivingpages?page='.$page.'&api_key='.get_option('lavalinx_api_key'), array(
			'method' => 'GET'
		));
		
		if (is_wp_error($response))
			exit($response->get_error_message());
		
		//exit($response['body']);

		$pages = json_decode($response['body']);

		Lavalinx_View::factory('receiving_pages/index', array('pages'=>$pages))->render();
	}

	public function edit_receiving_page()
	{
		isset($_GET['id']) || $_GET['id'] = 0;
		if (! $_GET['id'] and isset($_GET['copy']))
			$_GET['id'] = $_GET['copy'];

		$response = wp_remote_request(Lavalinx::API_URL.'dispatch.php/receivingpage/'.$_GET['id'].'?empty=1&api_key='.get_option('lavalinx_api_key'), array(
			'method' => 'GET'
		));
		
		if (is_wp_error($response))
			exit($response->get_error_message());

		$page = json_decode($response['body']);

		if (! $page && isset($_GET['first']) && $_GET['first'])
		{
			$categories = get_terms('category', array('fields'=>'names'));
			$page = new StdClass;
			$page->name = get_bloginfo('name');
			$page->url = get_bloginfo('wpurl');
			$page->tags = implode(', ', $categories);
			if (count($categories))
			{
				$page->description = 'For more information about <!--linkhere--> visit '.get_bloginfo('name').'.';
				$page->title = $categories[0];
			}
		}

		Lavalinx_View::factory('receiving_pages/edit', array('page'=>$page))->render();
	}
	
	public function feedback()
	{
		Lavalinx_View::factory('feedback')->render();
	}
}