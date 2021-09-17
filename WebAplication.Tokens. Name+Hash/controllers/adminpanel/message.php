<?php
	// разрешенные расширения файлов
	//$allowExt = array('gif', 'jpeg', 'jpg', 'png');
	// отдает расширение файла
	function getFileExt($filename) {
		$temp = explode('.', $filename);
		return strtolower(end($temp));
	}
	// проверка валиден ли тип и расширение
	function isPicture($type, $ext) {
		$allowExt = array('gif', 'jpeg', 'jpg', 'png');
		$result = false;
		switch ($type) {
			case 'image/jpeg':
			case 'image/jpg':
			case 'image/pjpeg':
			case 'image/x-png':
			case 'image/png':
			case 'image/gif':
				$result = true;
				break;
		}
		if ($result) {
			$result = in_array(strtolower($ext), $allowExt);
		}
		return $result;
	}
	function str_replace_once($search, $replace, $text) 
	{ 
	   $pos = strpos($text, $search); 
	   return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
	} 
	function uniqmes(){
		$EFabc = new EFabc();
		global $db;
		do{
					$login=$EFabc->user->generateCode(10);
					$result1 = mysqli_query($db,"SELECT * FROM message WHERE uniqmes='".$login."'")or die(mysql_error());
					$myrow1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
		}while (!empty($myrow1['id']));
		
		return $login;
	}

	session_start(); 
		$EFabc = new EFabc();
		global $db;
		$result = mysqli_query($db,"SELECT * FROM token WHERE token='".$_SESSION['token']."'")or die(mysql_error());
		$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if (($EFabc->user->isGuest()==false) && !empty($myrow['token'])){
				if (!empty($_FILES['imageFile']['name'])){
					$exist=1;
					$ext = getFileExt($_FILES['imageFile']['name']);
					if (isPicture($_FILES['imageFile']['type'], $ext)) {
						$noerrorsimg=1;
						
					} else {
						$noerrorsimg=0;
					}
				}else{
					$exist=0;
				}
				if (!empty($_FILES["txtFile"]["name"]))
					{
						$target_txt_dir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'docs'.DIRECTORY_SEPARATOR;
						$target_txt_file = $target_txt_dir . basename($_FILES["txtFile"]["name"]);
						$txtFileType = pathinfo($target_txt_file, PATHINFO_EXTENSION);
						$existtxt=1;
						$noerrorstxt=1;
						if ($noerrorstxt==1 && $txtFileType != "txt"){
								$noerrorstxt= 0;
						}
					}else{
						$existtxt=0;
					}
			if ($existtxt==1 &&$exist==1) {
				if ($noerrorstxt==1 && $noerrorsimg==1) {
					$ext=$EFabc->user->sanitizeMySql($ext);
					do{
								$login=$EFabc->user->generateCode(10);
								$login=$login.'.'.$ext;
								$result1 = mysqli_query($db,"SELECT * FROM message WHERE image='".$login."'")or die(mysql_error());
								$myrow1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
					}while (!empty($myrow1['id']));
					$image=$login;
					$uploaddir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR;
					$uploadfile = $uploaddir . basename($image);
					move_uploaded_file( $_FILES['imageFile']['tmp_name'], $uploadfile);
					
					$txtFileType=$EFabc->user->sanitizeMySql($txtFileType);
					do{
								$login=$EFabc->user->generateCode(10);
								$login=$login.'.'.$txtFileType;
								$result1 = mysqli_query($db,"SELECT * FROM message WHERE file='".$login."'")or die(mysql_error());
								$myrow1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
					}while (!empty($myrow1['id']));
					$file=$login;;
					$target_txt_file = $target_txt_dir .basename($file);
					move_uploaded_file($_FILES["txtFile"]["tmp_name"], $target_txt_file);
					
					$message=$EFabc->user->sanitizeMySql($_POST['comment']);
					$uniqmes=uniqmes();
					$message=str_replace("&lt;i&gt;", "<i>",$message);
					$message=str_replace("&lt;/i&gt;", "</i>",$message);
					$message=str_replace("&lt;strike&gt;", "<strike>",$message);
					$message=str_replace("&lt;/strike&gt;", "</strike>",$message);
					$message=str_replace("&lt;strong&gt;", "<strong>",$message);
					$message=str_replace("&lt;/strong&gt;", "</strong>",$message);
					$message=str_replace("&lt;code&gt;", "<code>",$message);
					$message=str_replace("&lt;/code&gt;", "</code>",$message);
					$result = mysqli_query($db,"INSERT INTO message (id_users,message,image,file,date,uniqmes) VALUES('".$EFabc->user->getId()."','".$message."','".$image."','".$file."',now(),'".$uniqmes."')") or die(mysql_error());
					$result = mysqli_query($db,"SELECT * FROM message WHERE id_users='".$EFabc->user->getId()."' and uniqmes='".$uniqmes."'")or die(mysql_error());
					$myrow1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$EFabc->user->getId()."'")or die(mysql_error());
					$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
							if (!empty($myrow['id'])){
								$ans = array(
									  "secondname" => $myrow['secondname'],
									  "name" => $myrow['name'],
									  "thirdname" => $myrow['thirdname'],
									  "login" => $myrow['nickname'],
									  "message" => $message,
									  "image" => $image,
									  "file" => $file,
									  "date" => $myrow1['date'],
									  "login" => $myrow['nickname'],
									  "addmess" => "Ok"
									);		 
									echo json_encode( $ans );
								}else{
									$ans = array(
									  "addmess" => "No"
									);
									 
									echo json_encode( $ans );
								}
				}else{
					$ans = array(
						  "both" => "No"
						 );
						 
					echo json_encode( $ans );
				}
			}
			if ($existtxt==1 &&$exist==0) {
				if ($noerrorstxt==1) {

					$txtFileType=$EFabc->user->sanitizeMySql($txtFileType);
					do{
								$login=$EFabc->user->generateCode(10);
								$login=$login.'.'.$txtFileType;
								$result1 = mysqli_query($db,"SELECT * FROM message WHERE file='".$login."'")or die(mysql_error());
								$myrow1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
					}while (!empty($myrow1['id']));
					$file=$login;
					$target_txt_file = $target_txt_dir .basename($file);
					move_uploaded_file($_FILES["txtFile"]["tmp_name"], $target_txt_file);
					$message=$EFabc->user->sanitizeMySql($_POST['comment']);
					$uniqmes=uniqmes();
					$message=str_replace("&lt;i&gt;", "<i>",$message);
					$message=str_replace("&lt;/i&gt;", "</i>",$message);
					$message=str_replace("&lt;strike&gt;", "<strike>",$message);
					$message=str_replace("&lt;/strike&gt;", "</strike>",$message);
					$message=str_replace("&lt;strong&gt;", "<strong>",$message);
					$message=str_replace("&lt;/strong&gt;", "</strong>",$message);
					$message=str_replace("&lt;code&gt;", "<code>",$message);
					$message=str_replace("&lt;/code&gt;", "</code>",$message);
					$result = mysqli_query($db,"INSERT INTO message (id_users,message,image,file,date,uniqmes) VALUES('".$EFabc->user->getId()."','".$message."','','".$file."',now(),'".$uniqmes."')") or die(mysql_error());
					$result = mysqli_query($db,"SELECT * FROM message WHERE id_users='".$EFabc->user->getId()."'  and uniqmes='".$uniqmes."'")or die(mysql_error());
					$myrow1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$EFabc->user->getId()."'")or die(mysql_error());
					$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
							if (!empty($myrow['id'])){
								$ans = array(
									  "secondname" => $myrow['secondname'],
									  "name" => $myrow['name'],
									  "thirdname" => $myrow['thirdname'],
									  "login" => $myrow['nickname'],
									  "message" => $message,
									  "image" => "No",
									  "file" => $file,
									  "date" => $myrow1['date'],
									  "login" => $myrow['nickname'],
									  "addmess" => "Ok"
									);		 
									echo json_encode( $ans );
								}else{
									$ans = array(
									  "addmess" => "No"
									);
									 
									echo json_encode( $ans );
								}
				}else{
					$ans = array(
						  "txt" => "No"
						 );
						 
					echo json_encode( $ans );
				}
			}
			if ($existtxt==0 &&$exist==1) {
				if ($noerrorsimg==1) {

					$ext=$EFabc->user->sanitizeMySql($ext);
					do{
								$login=$EFabc->user->generateCode(10);
								$login=$login.'.'.$ext;
								$result1 = mysqli_query($db,"SELECT * FROM message WHERE image='".$login."'")or die(mysql_error());
								$myrow1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
					}while (!empty($myrow1['id']));
					$image=$login;
					$uploaddir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR;
					$uploadfile = $uploaddir . basename($login);

					move_uploaded_file( $_FILES['imageFile']['tmp_name'], $uploadfile);
		
					$message=$EFabc->user->sanitizeMySql($_POST['comment']);
					$uniqmes=uniqmes();
					$message=str_replace("&lt;i&gt;", "<i>",$message);
					$message=str_replace("&lt;/i&gt;", "</i>",$message);
					$message=str_replace("&lt;strike&gt;", "<strike>",$message);
					$message=str_replace("&lt;/strike&gt;", "</strike>",$message);
					$message=str_replace("&lt;strong&gt;", "<strong>",$message);
					$message=str_replace("&lt;/strong&gt;", "</strong>",$message);
					$message=str_replace("&lt;code&gt;", "<code>",$message);
					$message=str_replace("&lt;/code&gt;", "</code>",$message);
					$result = mysqli_query($db,"INSERT INTO message (id_users,message,image,file,date,uniqmes) VALUES('".$EFabc->user->getId()."','".$message."','".$image."','',now(),'".$uniqmes."')") or die(mysql_error());
					$result = mysqli_query($db,"SELECT * FROM message WHERE id_users='".$EFabc->user->getId()."' and uniqmes='".$uniqmes."'")or die(mysql_error());
					$myrow1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$EFabc->user->getId()."'")or die(mysql_error());
					$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
							if (!empty($myrow['id'])){
								$ans = array(
									  "secondname" => $myrow['secondname'],
									  "name" => $myrow['name'],
									  "thirdname" => $myrow['thirdname'],
									  "login" => $myrow['nickname'],
									  "message" => $message,
									  "image" => $image,
									  "file" => "No",
									  "date" => $myrow1['date'],
									  "login" => $myrow['nickname'],
									  "addmess" => "Ok"
									);		 
									echo json_encode( $ans );
								}else{
									$ans = array(
									  "addmess" => "No"
									);
									 
									echo json_encode( $ans );
								}
				}else{
					$ans = array(
						  "img" => "No"
						 );
						 
					echo json_encode( $ans );
				}
			}	

			if ($existtxt==0 &&$exist==0) {
				
					$message=$EFabc->user->sanitizeMySql($_POST['comment']);
					$uniqmes=uniqmes();
					$message=str_replace("&lt;i&gt;", "<i>",$message);
					$message=str_replace("&lt;/i&gt;", "</i>",$message);
					$message=str_replace("&lt;strike&gt;", "<strike>",$message);
					$message=str_replace("&lt;/strike&gt;", "</strike>",$message);
					$message=str_replace("&lt;strong&gt;", "<strong>",$message);
					$message=str_replace("&lt;/strong&gt;", "</strong>",$message);
					$message=str_replace("&lt;code&gt;", "<code>",$message);
					$message=str_replace("&lt;/code&gt;", "</code>",$message);
					 
					/*if (preg_match_all('|<a>(.*)<\/a>|isU', $message, $arr)) { 

					  foreach ($arr[1] as $value){ 
					  //$i=0;
					 // $value[$i]=$value;
					
					 echo $value;
					 // $message=str_replace_once("&lt;a&gt;", "<a href=\'\'>",$message);
					  //$i+=1;				  
					  }
					} 				
					$message=str_replace("&lt;/a&gt;", "</a>",$message);*/
					$result = mysqli_query($db,"INSERT INTO message (id_users,message,image,file,date,uniqmes) VALUES('".$EFabc->user->getId()."','".$message."','','',now(),'".$uniqmes."')") or die(mysql_error());
					$result = mysqli_query($db,"SELECT * FROM message WHERE id_users='".$EFabc->user->getId()."' and uniqmes='".$uniqmes."'")or die(mysql_error());
					$myrow1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$EFabc->user->getId()."'")or die(mysql_error());
					$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
							if (!empty($myrow['id'])){
								$ans = array(
									  "secondname" => $myrow['secondname'],
									  "name" => $myrow['name'],
									  "thirdname" => $myrow['thirdname'],
									  "login" => $myrow['nickname'],
									  "message" => $message,
									  "image" => "No",
									  "file" => "No",
									  "date" => $myrow1['date'],
									  "login" => $myrow['nickname'],
									  "addmess" => "Ok"
									);		 
									echo json_encode( $ans );
								}else{
									$ans = array(
									  "addmess" => "No"
									);
									 
									echo json_encode( $ans );
								}
			}	
			}
?>