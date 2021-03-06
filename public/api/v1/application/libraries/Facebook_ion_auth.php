<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_ion_auth {

	/*
		Library for login with facebook and create a ion_auth compatibility session.

		author: Daniel Georgiev
		website: http://dgeorgiev.biz
	*/

	public function __construct() {

		// get Codeigniter instance
	    $this->CI =& get_instance();

	    // Load config
	    $this->CI->load->config('facebook_ion_auth', TRUE);

		$this->app_id = $this->CI->config->item('app_id', 'facebook_ion_auth');
		$this->app_secret = $this->CI->config->item('app_secret', 'facebook_ion_auth');
		$this->scope = $this->CI->config->item('scope', 'facebook_ion_auth');

		if($this->CI->config->item('redirect_uri', 'facebook_ion_auth') === '' ) {
			$this->my_url = site_url('');
		} else {
			$this->my_url = $this->CI->config->item('redirect_uri', 'facebook_ion_auth');
		}

	}


    public function login() {

    	// null at first
		$code = $this->CI->input->get('code');
		// if is not set go make a facebook connection
		if(!$code) {
			// create a unique state
	   		$this->CI->session->set_userdata('state', md5(uniqid(rand(), TRUE)));

	   		// redirect to facebook oauth page
	   		$url_to_redirect =  "https://www.facebook.com/dialog/oauth?client_id="
	       						.$this->app_id
	       						."&redirect_uri=".urlencode($this->my_url)
	       						."&state=".$this->CI->session->userdata('state').'&scope='.$this->scope;

	       	redirect($url_to_redirect);

	   	} else {
			$this->CI->email_activation = FALSE;

	   		// check if session state is equal to the returned state

			if($this->CI->session->userdata('state') && ($this->CI->session->userdata('state') === $this->CI->input->get('state'))) {

				$token_url = "https://graph.facebook.com/oauth/access_token?"
			       . "client_id=" . $this->app_id . "&redirect_uri=" . urlencode($this->my_url)
			       . "&client_secret=" . $this->app_secret . "&code=" . $code;

				$c = curl_init($token_url);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($c);
				curl_close($c);

				$params = null;

				parse_str($response, $params);
				$this->CI->session->set_userdata('access_token', $params['access_token']);

				$graph_url = "https://graph.facebook.com/me?fields=email,id,name,gender,age_range&access_token=".$params['access_token'];

				$c = curl_init($graph_url);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($c);
				curl_close($c);
				$user = json_decode($response);
				// check if this user is already registered

				// Here we actually register / login
				if(!$this->CI->ion_auth_model->identity_check($user->email)){
					$name = explode(" ", $user->name);
					$register = $this->CI->ion_auth->register($user->name, $user->id, $user->email, array('first_name' => $name[0], 'last_name' => $name[1]));
					if(!$register)
					{
						$this->errors = $this->CI->ion_auth->errors();
						return FALSE;
					}
					$this->CI->ion_auth->login($user->email, $user->id, 1);
					$this->CI->ion_auth->update($this->CI->ion_auth->user()->row()->id, array('facebook_login' => 1));
				} else {
					// We need to update their password to their user->id, so we can then log them in
					$password = $user->id;
					$this->CI->ion_auth->reset_password($user->email, $user->id);
					$login = $this->CI->ion_auth->login($user->email, $user->id, 1);
					$this->CI->ion_auth->update($this->CI->ion_auth->user()->row()->id, array('facebook_login' => 1));
				}

				return true;
		    }
		    else {
		   		return false;
		    }
	    }
    }
}

/* End of file Facebook_ion_auth.php */
