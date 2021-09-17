 <?php
	// DEFAILT_PATH
	//$path = '/home/host1379770/funny-pixels.ru/htdocs/music'; //dirname(__FILE__);
	$path = dirname(__FILE__);
	$siteName=" http://example1.ru.host1582112.serv11.hostland.pro";       //"http://testlocal.net.host1582112.serv11.hostland.pro";
	//Имя сайта нужно изменить также в файлах
	// logout,login,recovery,route(там путь к папке на сервере),change
	// PATH_TO_CORE_FILE 
	require($path . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'core.php');
?>
