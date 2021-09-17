<?php 
$EFabc = new EFabc();
global $db;
//session_start();
$result = mysqli_query($db,"SELECT * FROM token WHERE token='".$_SESSION['token']."'")or die(mysql_error());
$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$EFabc->user->isGuest()&& !empty($myrow['token'])){?>
		<div class='container' style='padding-top:60px;padding-bottom:100px'>
			<div style="padding-top:20px;">
				<table id="tableformes"  class="table table-striped table-bordered jambo_table bulk_action">
					<tbody >
					
					<?php	
						$result = mysqli_query($db,"SELECT * FROM message")or die(mysql_error());
						if (mysqli_num_rows ($result) !== 0){
							while ($group=mysqli_fetch_array($result,MYSQLI_ASSOC)){
							$result1 = mysqli_query($db,"SELECT * FROM users WHERE id='".$group['id_users']."'")or die(mysql_error());
							$group1=mysqli_fetch_array($result1,MYSQLI_ASSOC);
							echo '
							<tr class="trforpage">
							<td>
							<table class="table table-striped table-bordered jambo_table bulk_action">
								<tbody>
								<tr>
									<td colspan="2" id="postDateLine">'.$group['date']. '  |  '. $group1['nickname'].'  |  ' .$group1['secondname'].' '.$group1['name'].' '.$group1['thirdname'].'
									</td>
								</tr>
								<tr>
									<td id="postComment">
										<div id="postCommentDiv">'.$group['message'].'</div>
									</td>
								</tr>';
								if (!empty($group['image'])){
								echo '
								<tr>
									<td>
										<img style="display: block; max-width:320px;  max-height:240px;  width: auto; height: auto;" id="postImg" src="'.$siteName.'/image/'.$group['image'].'">
									</td>
								</tr>';
								}
								if (!empty($group['image'])){
								echo '
								<tr>
									<td>
										<a href="'.$siteName.'/docs/'.$group['file'].'">'.$group['file'].'</a>
									</td>
								</tr>';
								}
								echo'
								</tbody>
							</table>
							</td>
							</tr>';
								}
							}else{
								echo '<tr class="default">
									<td>		
									Данные отсутствуют!
									</td>
								</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div style="margin-top:20px;padding-top:20px;padding-left:20px;" class="table table-striped table-bordered jambo_table bulk_action">
			<div id="previewDiv" class="commentMessage">
			</div>
				<div class="logged">	
				<form method="post" id="postMessageForm" name="postMessageForm" enctype="multipart/form-data">
					<table width="100%" class="table table-striped table-bordered jambo_table bulk_action">
						

						<tr>
							<td colspan="3">
								<font class="fieldHeader"><span>*</span>Сообщение:</font><br/>
								<textarea id="comment" name="comment" placeholder="*Введите сообщение..." maxlength="5000"></textarea><br />
							</td>
						</tr>
						<tr>
							<td colspan="100%">
								<div style="padding-top: 10px; 	display: table;	margin : 0 auto;" >
									<input id="commentItalicButton" class="btn btn-default" type="button" name="commentItalic" value="Italic">
									<input id="commentStrikeButton" class="btn btn-default" type="button" name="commentStrike" value="Strike">
									<input id="commentBoldButton" class="btn btn-default" type="button" name="commentStrong" value="Bold">
									<input id="commentCodeButton" class="btn btn-default" type="button" name="commentCode" value="Code">
								</div>
							</td>
						</tr>
					</table>

					<div class="form-wrap">
						Прикрепить изображение<input name="imageFile" type="file" id="imgInputId" accept="image/jpeg,image/png,image/gif"/><br/>
						Прикрепить текстовый файл<input name="txtFile" type="file" id="txtInputId" accept="text/plain"/>
						<div id="output"><!-- error or success results --></div>
						<br/>
					</div>

					<div id="postFormErrorDiv"></div>

					<input id="previewButton" type="button" name="preview"  class="btn btn-default" value="Предпросмотр">
					<input id="submitButton" type="submit" name="submit" class="btn btn-default" value="Оставить сообщение">
				</form>
			</div>
		</div>
<?php 
}
?>
