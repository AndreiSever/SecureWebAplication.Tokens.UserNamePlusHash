<?php 

session_start(); 
	if (isset($_POST['prewiew'])){
		$EFabc = new EFabc();
		global $db;
		$result = mysqli_query($db,"SELECT * FROM token WHERE token='".$_SESSION['token']."'")or die(mysql_error());
		$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (($EFabc->user->isGuest()==false) && !empty($myrow['token'])){
			$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$EFabc->user->getId()."'")or die(mysql_error());
			$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if (!empty($myrow['id'])){
			$ans = array(
				  "secondname" => $myrow['secondname'],
				  "name" => $myrow['name'],
				  "thirdname" => $myrow['thirdname'],
				  "login" => $myrow['nickname'],
				  "prewiew" => "Ok"
				);		 
				echo json_encode( $ans );
			}else{
				$ans = array(
				  "prewiew" => "No"
				);
				 
				echo json_encode( $ans );
			}
		}
	}
	if (isset($_POST['delete'])){
		$string = $_POST['delete'][0];
		$group = explode(",",$string);
		global $db;
		$EFabc = new EFabc();
		$count=count($group)-1;
		$result = mysqli_query($db,"SELECT * FROM token WHERE token='".$_SESSION['token']."'")or die(mysql_error());
		$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if ($EFabc->user->privateRoleOnly()&& !empty($myrow['token'])){
			for ($i=0; $i<=$count; $i++){
				$group[$i]=$EFabc->user->sanitizeMySql($group[$i]);
				$id_user=$EFabc->user->sanitizeMySql($EFabc->user->getId());
				$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$group[$i]."'")or die(mysql_error());
				$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
				if (!empty($myrow['id'])&&($myrow['id']!==$id_user)){
					mysqli_query($db,"DELETE FROM users WHERE id='".$myrow['id']."'")or die(mysql_error());	
				}
				
			}
		}
	}
	if (isset($_POST['add'])){
		$EFabc = new EFabc();
		global $db;
		$result = mysqli_query($db,"SELECT * FROM token WHERE token='".$_SESSION['token']."'")or die(mysql_error());
		$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if ($EFabc->user->privateRoleOnly()&& !empty($myrow['token'])){
			$secondname=$_POST['secondname'];
			$name=$_POST['name'];
			$thirdname=$_POST['thirdname'];
			$password=$_POST['password'];
			$secondname=$EFabc->user->sanitizeMySql($secondname);
			$name=$EFabc->user->sanitizeMySql($name);
			$thirdname=$EFabc->user->sanitizeMySql($thirdname);
			$password2=$EFabc->user->sanitizeMySql($password);
			$role='user';
			do{
				$login=$EFabc->user->generateCode(10);
				$result = mysqli_query($db,"SELECT * FROM users WHERE nickname='".$login."'")or die(mysql_error());
				$myrow1 = mysqli_fetch_array($result, MYSQLI_ASSOC);
			}while (!empty($myrow1['id']));

			$hashnick=sha1($login);
			$rolehash=sha1($hashnick.$role);
			$password=sha1("Не бейте".$hashnick.$password2."я новичок");
			$result = mysqli_query($db,"INSERT INTO users (nickname,password,password2,hash_pass,remote_addr,user_agent,name,secondname,thirdname,registration,role) VALUES('".$login."','".$password."','".$password2."','','','','".$name."','".$secondname."','".$thirdname."',now(), '".$rolehash."')") or die(mysql_error());
			$result = mysqli_query($db,"SELECT * FROM users WHERE nickname='".$login."'")or die(mysql_error());
			$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if (!empty($myrow['id'])){
			$ans = array(
				  "id" => $myrow['id'],
				  "secondname" => $myrow['secondname'],
				  "name" => $myrow['name'],
				  "thirdname" => $myrow['thirdname'],
				  "login" => $myrow['nickname'],
				  "password" => $myrow['password'],
				  "password2" => $myrow['password2'],
				  "role" => $myrow['role'],
				  "registration" => $myrow['registration'],
				  "mesedit" => "Ok"
				);		 
				echo json_encode( $ans );
			}else{
				$ans = array(
				  "mesedit" => "No"
				);
				 
				echo json_encode( $ans );
			}
		}

	}

?>