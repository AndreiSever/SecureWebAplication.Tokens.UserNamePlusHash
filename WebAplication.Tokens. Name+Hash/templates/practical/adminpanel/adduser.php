<?php 
$EFabc = new EFabc();
global $db;
//session_start();
$result = mysqli_query($db,"SELECT * FROM token WHERE token='".$_SESSION['token']."'")or die(mysql_error());
$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);

if ($EFabc->user->privateRoleOnly()&& !empty($myrow['token'])){?>
		<div class='container' style='padding-top:60px;padding-bottom:100px'>
			<div style="margin-top:20px;padding-top:20px;padding-left:20px;" class="table table-striped table-bordered jambo_table bulk_action">
			
			<?php 
			$id_user=$EFabc->user->sanitizeMySql($EFabc->user->getId());
			$result = mysqli_query($db,"SELECT * FROM users WHERE id='".$id_user."'")or die(mysql_error());
			$group=mysqli_fetch_array($result,MYSQLI_ASSOC);
			echo "Ваши данные:";
			echo "<p>Id -  ".$group['id'];
			echo "<p>Login -  ".$group['nickname'];
			echo "<p>Password -  ".$group['password'];
			echo "<p>Password (без шифрования)-  ".$group['password2'];
			echo "<p>Hash_pass -  ".$group['hash_pass'];
			echo "<p>Remote_Addr -  ".$group['remote_addr'];
			echo "<p>User Agent -  ".$group['user_agent'];
			echo "<p>Фамилия -  ".$group['secondname'];
			echo "<p>Имя -  ".$group['name'];
			echo "<p>Отчество -  ".$group['thirdname'];
			echo "<p>Роль -  ".$group['role'];
			echo "<p>Дата регистрации -  ".$group['registration'];
			?>
			</div>
			<div class="form-inline">
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="forename" placeholder = "Фамилия" />
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="name" placeholder="Имя" />
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="thirdname" placeholder="Отчество" />
				<button class="btn btn-primary" onclick="addRow('datatable1', this);return false;">Добавить</button>
				<button type="button" class="btn btn-default" onclick="deleteRow('datatable1', this);return false;">Удалить</button>	
			</div>
			<div style="padding-top:20px;">
				<table id="datatable1"  class="table table-striped table-bordered jambo_table bulk_action">
					<thead>
						<tr>
							<th class="thCheckbox"></th>
							<th class="thCheckbox" style="display: none;"></th>
							<th class="thStyleForListStudent">Фамилия</th>
							<th class="thStyleForListStudent">Имя</th>
							<th class="thStyleForListStudent">Отчество</th>
							<th class="thStyleForListStudent">Логин</th>
							<th class="thStyleForListStudent">Пароль|Пароль без шифрования</th>
							<th class="thStyleForListStudent">Роль</th>
							<th class="thStyleForListStudent">Дата регистрации</th>
						</tr>
					</thead>
					<tbody >
					<?php	
						$result = mysqli_query($db,"SELECT * FROM users WHERE id<>'".$id_user."'")or die(mysql_error());
						if (mysqli_num_rows ($result) !== 0){
							while ($group=mysqli_fetch_array($result,MYSQLI_ASSOC)){
							echo    '<tr class="even pointer">
										  <td class="a-center ">
											  <input type="checkbox" class="flat" name="table_records">
										  </td>
										  <td style="display: none;">
											<span class="" style="display: inline;">'.$group['id'].'</span>
										  </td>
										  <td >
											<span class="" style="display: inline;">'.$group['secondname'].'</span>
										  </td>
										  <td>
											<span class="" style="display: inline;">'.$group['name'].'</span>
										  </td>
										  <td>
											<span class="" style="display: inline;">'.$group['thirdname'].'</span>
										  </td>
										  <td>
											<span class="" style="display: inline;">'.$group['nickname'].'</span>
										  </td>
										  <td>
											<span class="" style="display: inline;">'.$group['password'].'  |  '.$group['password2'].'</span>
										  </td>
										  <td>
											<span class="" style="display: inline;">'.$group['role'].'</span>
										  </td>
										  <td>
											<span class="" style="display: inline;">'.$group['registration'].'</span>
										  </td>
									</tr>';
								}
							}else{
								echo '<tr class="default">
									<td>		
									</td>
									<td>		
									</td>
									<td>		
									</td>
									<td>
										Данные отсутствуют!
									</td>
									<td>		
									</td>
									<td>		
									</td>
									<td>		
									</td>
									<td>		
									</td>
								</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
<?php 
}else if ($EFabc->user->GetRole()==sha1(sha1($EFabc->user->getNickname()).'user') &&!empty($myrow['token'])){
	echo "<div class='container' style='padding-top:60px;'><h2>Вы пользователь, у вас нет функционала!</h2></div>";
}
?>
