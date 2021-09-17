<?php	

	class user
	{		
		private $defaultRoles = array ('guest', 'user', 'moderator', 'administrator');
		private $userData;
		private $isAuthorization;
		
		
		function user()
		{	
			global $db;
			if (isset($_COOKIE['id']) and isset($_COOKIE['hash_pass'])){ 
				$useragent=$this->sanitizeMySql($_SERVER['HTTP_USER_AGENT']);
				$remoteAddr=$this->sanitizeMySql($_SERVER['REMOTE_ADDR']);
				$id=$this->sanitizeMySql($_COOKIE['id']);
				$cookie=$this->sanitizeMySql($_COOKIE['hash_pass']);
				$query = mysqli_query($db,"SELECT * FROM users WHERE id = '".$id."'") or die(mysql_error());
				$data = mysqli_fetch_array($query, MYSQLI_ASSOC);
				if (($data['hash_pass']==$cookie)&&($remoteAddr==$data['remote_addr'])&&($useragent==$data['user_agent'])){
					$this->isAuthorization = true;
					$this->userData=$data;
					
				}else {
					$this->isAuthorization = false;
				}
			}	
			else {
				$this->isAuthorization = false;
				
			}
		}
		
		public function isGuest() 
		{
			if ($this->isAuthorization == true)
				return false;
			else
				return true;
		}

		public function getNickname()
		{
			return $this->userData['nickname'];
		}
		
		public function getRole()
		{
			return $this->userData['role'];
		}
		public function getId()
		{
			return $this->userData['id'];
		}
		public function getHash()
		{
			return $this->userData['hash_pass'];
		}

		
		public function privateRoleOnly()
		{
			
			if ($this->getRole() == sha1(sha1($this->getNickname()).'moderator') || $this->getRole() == sha1(sha1($this->getNickname()).'administrator'))//sha1($this->getNickname()+
				return true;
			else
				return false;
		}
		
		public function authorization($nickname, $password)
		{	
			global $db;
			
			if (isset($nickname) && isset($password)) 
			{	
				$hashnick=sha1($nickname);
				$password=sha1("Не бейте".$hashnick.$password."я новичок");//$password=sha1("Не бейте".$password."я новичок");
				$query = mysqli_query($db,"SELECT * FROM users WHERE nickname = '".$nickname."' AND password = '".$password."'") or die(mysql_error());
				$this->userData = mysqli_fetch_array($query, MYSQLI_ASSOC);
				if (!empty($this->userData['id']))
				{	
					$hash = $this->sanitizeMySql($this->generateCode(10));
					$hash = sha1("Не бейте".$hash."я новичок");
					$useragent=$this->sanitizeMySql($_SERVER['HTTP_USER_AGENT']);
					$remoteAddr=$this->sanitizeMySql($_SERVER['REMOTE_ADDR']);
					mysqli_query($db,"UPDATE users SET hash_pass = '".$hash."' , user_agent = '".$useragent."', remote_addr = '".$remoteAddr."' WHERE id='".$this->userData['id']."'") or die(mysql_error());
					setcookie("id", $this->userData['id'], 0, "/");
					setcookie("hash_pass", $hash, 0, "/");
					$this->isAuthorization = true;
					return true;
				}else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		public function logout()
		{
			setcookie("id", "", 0, "/");
			setcookie("hash_pass", "", 0, "/");
			$this->isAuthorization = false;
		}
		
		public function sanitizeString($var)
		{
			if (get_magic_quotes_gpc()) $var=stripslashes($var);
			$var=htmlentities($var);
			$var=strip_tags($var);
			return $var;
		}		
		public function sanitizeMySql($var)
		{
			global $db;
			$var=mysqli_real_escape_string($db,$var);
			$var=$this->sanitizeString($var);
			return $var;
		}
		

		public function generateCode($length=10) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  
		while (strlen($code) < $length) {
				$code .= $chars[mt_rand(0,$clen)];  
		}
		return $code;
		}
		public function generateNumber($length=10) {
		$chars = "0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  
		while (strlen($code) < $length) {
				$code .= $chars[mt_rand(0,$clen)];  
		}
		return $code;
		}

	}
?>