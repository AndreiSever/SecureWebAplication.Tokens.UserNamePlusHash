<?	
	class Roles
	{
		private $roles = array (
			'ADMIN',
			'USER',
			
			// ADD_ROLE
		);
	
		public function access($role)
		{
			$answer = false;
			foreach ($this->$roles as $value)
			{
				if ($value == $role)
					$answer = true; break;
			}
			return $answer;
		}
	}
?>