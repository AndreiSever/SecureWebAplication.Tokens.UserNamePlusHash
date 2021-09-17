<?php

	class route
	{
		private $uri;
		private $values;
		private $controllerPath;
		private $templatePath;
		private $path ="/home/host1582112/example1.ru/htdocs/www/";//"/home/host1582112/testlocal.net/htdocs/www/" C:\wamp/www/testlocal/;
		
		function route()
		{
			$this->uri = $_SERVER['REQUEST_URI'];
			$uri = trim($this->uri, '/\\');
			$this->values = explode('/', $uri);
			
			if (empty($this->values['0']))
			{	
				if (file_exists($this->path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'index' . '.php'))
					$this->controllerPath = $this->path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'index' . '.php';
					
				if (file_exists($this->path . DIRECTORY_SEPARATOR .  'templates' . DIRECTORY_SEPARATOR . 'practical' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'index' . '.php')){
					$this->templatePath = $this->path . DIRECTORY_SEPARATOR .  'templates' . DIRECTORY_SEPARATOR . 'practical' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'index' . '.php';
					//echo "fdsfd";
				}
			}
			else 
			{
				if (!isset($this->values['2']) || $this->values[2] == 'page' || is_numeric($this->values[2]) || /* (($this->values[2]<>'') && */ ($this->values[1]=='search'))
				{
					
					$error = true;
					if (file_exists($this->path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->values['0'] . DIRECTORY_SEPARATOR . $this->values['1'] . '.php'))
					{
						$this->controllerPath = $this->path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $this->values['0'] . DIRECTORY_SEPARATOR . $this->values['1'] . '.php';
						$error = false;
					}
					if (file_exists($this->path . DIRECTORY_SEPARATOR .  'templates' . DIRECTORY_SEPARATOR . 'practical' . DIRECTORY_SEPARATOR . $this->values['0'] . DIRECTORY_SEPARATOR . $this->values['1'] . '.php'))
					{
						$this->templatePath = $this->path . DIRECTORY_SEPARATOR .  'templates' . DIRECTORY_SEPARATOR . 'practical' . DIRECTORY_SEPARATOR . $this->values['0'] . DIRECTORY_SEPARATOR . $this->values['1'] . '.php';
						$error = false;
					}
					if ($error)
					{
						$this->applyError();
					}
				}
				else
				{
					$this->applyError();
				}
			}
		}
		
		private function applyError()
		{
			if (file_exists($this->path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'nofound' . DIRECTORY_SEPARATOR . 'index' . '.php'))
				$this->controllerPath = $this->path . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'nofound' . DIRECTORY_SEPARATOR . 'index' . '.php';
			if (file_exists($this->path . DIRECTORY_SEPARATOR .  'templates' . DIRECTORY_SEPARATOR . 'practical' . DIRECTORY_SEPARATOR . 'nofound' . DIRECTORY_SEPARATOR . 'index' . '.php'))
				$this->templatePath = $this->path . DIRECTORY_SEPARATOR .  'templates' . DIRECTORY_SEPARATOR . 'practical' . DIRECTORY_SEPARATOR . 'nofound' . DIRECTORY_SEPARATOR . 'index' . '.php';
				
		}
		public function applyErrorCopy()
		{
			if (file_exists($this->templatePath))
				require($this->templatePath);
				
		}
		
	    public function startController()
		{
			if (file_exists($this->controllerPath))
				require($this->controllerPath);
			
			//echo $this->controllerPath;
		}
		
		public function intro()
		{
			if (file_exists($this->templatePath))
				require($this->templatePath);
			//echo $this->templatePath;
		}
		
		public function getURI()
		{
			return $this->uri;
		}
		
		public function getControllerPath()
		{
			return $this->controllerPath;
		}
		
		public function getTemplatePath()
		{
			return $this->templatePath;
		}
		
		function getId()
		{
			return $this->values[2];
		}
		function getControll()
		{
			return $this->values[1];
		}
		
		function getPage()
		{
			if ($this->values[3] == "page")
			{
				if (is_numeric($this->values[4]) && $this->values[4] >= 0)
					return $this->values[4];
			}
			 if ($this->values[2] == "page")
			{
				if (is_numeric($this->values[3]) && $this->values[3] >= 0)
					return $this->values[3];
			} 
			//return "жерарж акбар"; */
		}
		
		function getWidget($name)
		{
			if (file_exists($this->path . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'index' . '.php'))
				require($this->path . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'index' . '.php');
			if (file_exists($this->path . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'index' . '.php'))
				require($this->path . DIRECTORY_SEPARATOR . 'widgets' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'index' . '.php');
		
		}
	}
?>