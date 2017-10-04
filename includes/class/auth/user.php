<?php
class User
{

	private $logged = false;

	private $userID;

	private $userName;

	private $firstName;

	private $lastName;

	private $email;

	private $company;

	private $username;

	public function __construct() 
	{
		if(isset($_SESSION['userID']) && $_SESSION['userID'] != 0 && $_SESSION['logged'] == "true" && isset($_SESSION['secretPass']))
		{
			$userSession = $this->get_user_session($_SESSION['secretPass']); 

			if(empty($userSession))
			{
				$this->logged = false;
				$this->logout();
			}
			elseif(((time() - strtotime($userSession->timestamp)) > 30*60)|| $userSession->ip_address != $this->get_ip_address()) //timeout 30 minutes and check ip address
			{
				echo 'delted';
				$this->delete_user_session($userSession->id);
				$this->logged = false;
			}
			elseif($userSession->user_id == $_SESSION['userID'])
			{
				$this->user = $this->get_user($_SESSION['userID']);
				$this->logged= true;
				$this->userID = $this->user->id;
				$this->userName = $this->user->username;
				$this->firstName = $this->user->first_name;
				$this->lastName = $this->user->last_name;
				$this->email = $this->user->email;
				$this->company = $this->user->company;

				//regenerate session id in database (15% chance)
				if(rand(1, 100) <= 15)
				{
					$this->regenerate_session($userSession->id, $this->get_user_id());
				}

				$this->session_update_time($userSession->id);
			}
		
		}
		else
		{

			$this->logged= false;
			$this->userID = 0;
			$this->userName = "";
			$this->firstName = "";
			$this->lastName = "";
			$this->email = "";
		}
	}	


	/***********************
		Gets user info..

		Returns false if user id does not exists and user array if user is found. 
	**********************/
	public function get_user($id)
	{
		global $db;

		$user = $db->get_row("SELECT * FROM ". USERS ." WHERE ID=$id");

		
		if(empty($user))
			return false;
		else
			return $user;
	}

	public function is_logged()
	{
		return $this->logged;
	}

	//get user session from db (return as object)
	private function get_user_session($hashid)
	{
		global $db;

		$session = $db->get_row("SELECT * FROM ". SESSIONS ." WHERE id='$hashid'");

		return $session;
	}

	private function delete_user_session($hashid)
	{
		global $db;

		$db->query("DELETE FROM ".SESSIONS." WHERE id='$hashid'");
	}

	private function create_user_session($hashid, $user_id)
	{
		global $db;

		$_SESSION['secretPass'] = $hashid;
		$ipaddress = $this->get_ip_address();
		$db->query("INSERT INTO ".SESSIONS." SET id='$hashid', user_id='$user_id', ip_address='$ipaddress', timestamp=NOW()");

	}

	private function regenerate_session($hashid, $userid)
	{
		global $db;

		$ipaddress = $this->get_ip_address();

		$uid = $this->generate_unique_code();
		$this->create_user_session($uid, $userid);
		$this->delete_user_session($hashid);
	}

	private function session_update_time($hashid)
	{
		global $db;

		$db->query("UPDATE ".SESSIONS." SET timestamp=NOW() WHERE id='$hashid'");
	}
//ALL FUNCTIONS FOR LOGGED IN USERS
	public function get_user_id()
	{
		return $this->userID;
	}

	public function get_user_username()
	{
		return $this->userName;
	}

	public function get_user_firstname()
	{
		return $this->firstName;
	}

	public function get_user_lastname()
	{
		return $this->lastName;
	}

	public function get_user_email()
	{
		return $this->email;
	}

	public function get_user_name()
	{
		return $this->username;
	}

	public function get_company()
	{
		return $this->company;
	}
	/******************
	 NEW USER FUNCTION
	*******************/
	public function set_new_user($firstName, $lastName, $email, $username, $company, $password)
	{
		global $db;

		if($this->email_exist($email))
		{
			die('Unexpected Error. Email Already Exists');
			return 'Error';
		}
		$password = $this->hash_password($password);

		$db->query("INSERT INTO ". USERS ." SET first_name='$firstName', last_name='$lastName', email='$email', username='$username', password='$password', company='$company', status='0', created=NOW()");
		
		$id = $db->insert_id;

		return $id;
	}

	//check if email exists via ajax call when adding new user.
	public function email_exist($email)
	{
		global $db;

		$r = $db->get_results("SELECT id FROM ". USERS ." WHERE email='$email'");

		if(empty($r))
			return false;
		else 
			return true;
	}

	private function generate_unique_code()
	{
		return md5(uniqid(rand(),true) + time());
	}

	//Password Functions
	private function hash_password($password)
	{
		$hash = hash('sha256', SALTSITE . $password );
		
		return $hash;
	}

	/*****************************
	LOGIN/LOGGED in FUNCTIONS
	*****************************/

	public function login($username, $password)
	{
		global $db;

		if($this->is_logged())
		{
			return 'Already Logged';
		}
		$user = $db->get_row("SELECT * FROM ".USERS." WHERE username='$username'");

		if(empty($user))
			return 'Invalid Login';
		elseif($user->status == 1)
			return 'Your account is suspended. Contact an administrator to resolve the issue';
		else
		{
			if(!$this->compare_passwords($password, $user->password))
				return 'Invalid Login';
			else
			{
				$this->loginS($user->id, $user->first_name);
				header('Location: '.SITEURL);
				return 'LOGIN';
			}
		}
	}
	//set session
	private function loginS($id, $firstName)
	{
		$uid = $this->generate_unique_code();
		$this->create_user_session($uid, $id);
		$_SESSION['logged'] = "true";
		$_SESSION['userID'] = $id;
		$_SESSION['fname'] = $firstName;
	}

	public function logOut()
	{

		session_destroy();
		$this->delete_user_session($_SESSION['secretPass']);
	}
	
	private function compare_passwords($password, $hash)
	{
		$hashpassword = $this->hash_password($password);
		
		if(strcmp($hashpassword,$hash) == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;
	}



	public function get_ip_address()
	{
		 $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
}

?>