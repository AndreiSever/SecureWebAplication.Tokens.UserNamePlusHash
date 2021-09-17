$("#postMessageForm").submit(function(e)
    {
		e.preventDefault();
            var formData = new FormData($(this)[0]);
			$.ajax({
				url: "/adminpanel/message/",
				type: 'POST',
				data: formData,
				async: true,
				dataType: "text",
				success: function (received) 
				{
					datas = JSON.parse(received);
					if (datas.addmess == 'Ok' )
					{
						$("#postFormErrorDiv").html('');
						$("#postMessageForm")[0].reset();
						$("#previewDiv").html('');
						window.imgPreview = '';
						window.txtLink = '';
						window.txtPreview = '';
						var result = "";
						result += '<table class="table table-striped table-bordered jambo_table bulk_action">';

						result += '<tr>';
							result += '<td colspan="2" id="postDateLine">';
							result += datas.date+"  |  " + datas.login+"  |  "+ datas.secondname+" "+ datas.name+" "+ datas.thirdname;
							result += "</td>";
						result += '</tr>';

						result += "<tr>";
							result += "<td id='postComment'>";
							result += "<div id='postCommentDiv'>" + datas.message + "</div>";
							result += "</td>";
						result += "</tr>";

						if (datas.image!="No")
						{
							result += "<tr>";
								result += "<td>";
								result += "<img style='display: block; max-width:320px;  max-height:240px;  width: auto; height: auto;' id='postImg' src='http://example1.ru.host1582112.serv11.hostland.pro/image/" + datas.image + "' />";	
								result += "</td>";
							result += "</tr>";
						}

						if (datas.file!="No")
						{
							result += "<tr>";
								result += "<td>";
								result += "<a href='http://example1.ru.host1582112.serv11.hostland.pro/docs/" + datas.file + "'>" + datas.file + "</a>";	
								result += "</td>";
							result += "</tr>";
						}

						result += "</table><br/>";
						var tbody = d.getElementById('tableformes').getElementsByTagName('TBODY')[0];
						var row = d.createElement("TR");
						tbody.appendChild(row);
						row.className = "trforpage";
						var td = d.createElement("TD");
						row.appendChild(td);
						td.innerHTML = result;
						changePageForMes('tableformes');
					}else
					{
						var text;
						if (datas.both == 'No'){
							text = "Ошибка! Вы пытаетесь загрузить картинку или тектовый файл(или все сразу) в блоке не предназначенном для этого!";
						}
						if (datas.img == 'No'){
							text="Ошибка! Вы пытаетесь загрузить файл не являющийся картинкой!";
						}
						if (datas.txt != 'Ok'){
							text="Ошибка! Вы пытаетесь загрузить файл не являющийся текстовым (формат txt)!";
						}
						$("#postFormErrorDiv").html('<div style="color:red; font-size:22px">' + text + '</div>');
						
					}
				},
				cache: false,
				contentType: false,
				processData: false
			});
	});
	function changePageForMes(r){
		var i=document.getElementById(r);
		var countTable = i.rows.length;
		var countPage= i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a").length-4;
		for (x=0;x<i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a").length;x++)
		{
			if (i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a")[x].hasAttribute('data-selected')){
				var page = i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a")[x].innerHTML;
			}
		}
		if (countTable>0){
			if (page>Math.ceil(countTable/10)){
				i.parentNode.getElementsByClassName("page-navigation")[0].remove();
				paginationForMes(r,Math.ceil(countTable/10)-1);
			}else{
				i.parentNode.getElementsByClassName("page-navigation")[0].remove();
				paginationForMes(r,page-1);
			}
		}else{
			i.parentNode.getElementsByClassName("page-navigation")[0].remove();
			paginationForMes(r,0);
		}
	}
	// Функция для постраничной навигации для таблиц
	function paginationForMes(r,startPage){
		$('#'+r).paginate({
			initialPage: startPage,
			optional: false,
			limit: 10,
			childrenSelector: 'tbody > tr.trforpage',
			onSelect: function(obj, page) {
			  console.log('Page ' + page + ' selected!' );
			}
			});
	}
	function wrapText(elementID, openTag, closeTag) 
{
    var textArea = $('#' + elementID);
    var len = textArea.val().length;
    var start = textArea[0].selectionStart;
    var end = textArea[0].selectionEnd;
    var selectedText = textArea.val().substring(start, end);
    var replacement = openTag + selectedText + closeTag;
    textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
}


$(document).ready(function() 
{
    $("#commentItalicButton").unbind().click(function(){
        wrapText("comment", "<i>", "</i>");
    }); 

    $("#commentStrikeButton").unbind().click(function(){
        wrapText("comment", "<strike>", "</strike>");
    }); 

   	$("#commentBoldButton").unbind().click(function(){
        wrapText("comment", "<strong>", "</strong>");
    }); 

   	$("#commentCodeButton").unbind().click(function(){
        wrapText("comment", "<code>", "</code>");
    }); 

   	//$("#commentUrlButton").unbind().click(function(){
    //    wrapText("comment", "<a>", "</a>");
    //}); 
});
function getHtmlPost(secondname, name, thirdname, login, comment, datetime, imageLink, txtLink, txtName)
{
	var result = "";

	comment = comment.replace(/\n/g, "<br />");

	result += '<table class="table table-striped table-bordered jambo_table bulk_action">';

	result += '<tr>';
		result += '<td colspan="2" id="postDateLine">';
		result += datetime+"  |  " + login+"  |  "+ secondname+" "+ name+" "+ thirdname;
		result += "</td>";
	result += '</tr>';

	result += "<tr>";
		result += "<td id='postComment'>";
		result += "<div id='postCommentDiv'>" + comment + "</div>";
		result += "</td>";
	result += "</tr>";

	if (imageLink)
	{
		result += "<tr>";
			result += "<td>";
			result += "<img style='display: block; max-width:320px;  max-height:240px;  width: auto; height: auto;' id='postImg' src=" + imageLink + " />";	
			result += "</td>";
		result += "</tr>";
	}

	if (txtLink && txtName)
	{
		result += "<tr>";
			result += "<td>";
			result += "<a href=" + txtLink + ">" + txtName + "</a>";	
			result += "</td>";
		result += "</tr>";
	}

	result += "</table><br/>";
	return result;
}

$(document).ready(function() 
{
    $("#previewButton").unbind().click(function()
    {

    	var comment = $("#comment").val();
    	var datetime = (new Date()).toString('yyyy-MM-dd HH:mm:ss');
    	var imageLink = window.imgPreview;
    	var txtLink = window.txtLink
    	var txtName = window.txtPreview;
		uri = "/adminpanel/create_users_sql/";
		var params="prewiew=Ok";
		sqlPrewiewWithCallback(params,uri,calback, comment, datetime, imageLink, txtLink, txtName);	
  		
    }); 
	function callbackprewiew(request, comment, datetime, imageLink, txtLink, txtName){
		var	mes=JSON.parse(request.responseText);
		if (mes.prewiew=="Ok"){
			$("#previewDiv").html("<h3>Предпросмотр</h3>");
			$("#previewDiv").append(getHtmlPost(mes.secondname,mes.name,mes.thirdname,mes.login, comment, datetime, imageLink, txtLink, txtName));
		}else{
			alert("Что-то пошло не так!");
		}
	}
	function sqlPrewiewWithCallback(params,uri,callback,comment, datetime, imageLink, txtLink, txtName){
		var request = new ajaxRequest();

		request.onreadystatechange = function()
		{
			if (request.readyState==4)
			{
				if (request.status==200)
				{
					if (request.responseText != null)
					{
						 callbackprewiew(request, comment, datetime, imageLink, txtLink, txtName);
					}
					else alert ("Данные не полученны");
				}
				else alert ("Ошибка Ajax"+this.statusText);
			}
		}
		request.open("POST", uri, true);
		request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		request.send(params);
	}
    function readImgURL(input) 
    {
	    if (input.files && input.files[0]) 
	    {
	        var reader = new FileReader();
	        reader.onload = function (e) 
	        {
	            window.imgPreview = e.target.result;
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$("#imgInputId").change(function()
	{
	    readImgURL(this);
	});

	function readTxtURL(input) 
    {
	    if (input.files && input.files[0]) 
	    {
	        var reader = new FileReader();
	        reader.onload = function (e) 
	        {
	        	window.txtLink = e.target.result;
	            window.txtPreview = input.files[0].name;
	        }
	        reader.readAsDataURL(input.files[0]);
	   	}
	}
	$("#txtInputId").change(function()
	{
	    readTxtURL(this);
	});
});

//Функция для создания постраничной навигации при открытии страницы
		$('#tableformes').paginate({
			optional: false,
			limit: 10,
			childrenSelector: 'tbody > tr.trforpage',
			onSelect: function(obj, page) {
			  console.log('Page ' + page + ' selected!' );
			}
		});
		
var d = document;
	/////////
	////// Функции для редактирования пользователей
	//Выводит сообщение что нет данных, на странице пользователей
	function rowAmountForDefault(r){
		var table=d.getElementById(r).rows.length-1;
		if (table<=0){
			var tbody = d.getElementById(r).getElementsByTagName('TBODY')[0];
			var row = d.createElement("TR");
			tbody.appendChild(row);
			row.className = "default";
			var td1 = d.createElement("TD");
			var td1 = d.createElement("TD");
			var td2 = d.createElement("TD");
			var td3 = d.createElement("TD");
			var td4 = d.createElement("TD");
			var td5 = d.createElement("TD");
			var td6 = d.createElement("TD");
			var td7 = d.createElement("TD");
			var td8 = d.createElement("TD");
			td1.className= "a-center";
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			row.appendChild(td4);
			row.appendChild(td5);
			row.appendChild(td6);
			row.appendChild(td7);
			row.appendChild(td8);
			td4.innerHTML = 'Данные отсутствуют!';
		}
	}
	/////
	//Добавление пользователей
	function addRow(r, inp){
		var input = inp.parentNode.getElementsByTagName('input');
		for (var x=0;x<=d.getElementById(r).rows.length-1; x++){
			if (d.getElementById(r).getElementsByTagName('tr')[x].classList.contains('default')){
				d.getElementById(r).getElementsByClassName('default')[0].remove();
			}
		}
		var forename = input[0].value;
		var	name =	input[1].value;
		var thirdname = input[2].value;
		var password= str_rand();
		addRowToDb(r,forename,name,thirdname,password);
	}
	//Удаление пользователей
	function deleteRow(r){
		var a=$('#'+r+'>tbody > tr'); //выбираем все отмеченные checkbox
		var out=[];
		for (var x=0; x<a.length;x++){ //перебераем все объекты 
			var tdCount=a[x].getElementsByTagName('td');
			if (a[x].getElementsByTagName('td')[0].getElementsByTagName('input')[0].checked){
					out.push(tdCount[1].getElementsByTagName('span')[0].innerHTML);
				a[x].remove();
			}
		
		}
		uri = "/adminpanel/create_users_sql/";
		params='delete[]='+out;
		sql(params,uri);
		rowAmountForDefault(r);
		changePage(r);
	}
	//Калбек функция для добавления пользователей
	function calback(tableId,request){
		var	mes=JSON.parse(request.responseText);
		if (mes.mesedit=="Ok"){
			var tbody = d.getElementById(tableId).getElementsByTagName('TBODY')[0];
			var row = d.createElement("TR");
			tbody.appendChild(row);
			row.className = "even pointer";
			var td1 = d.createElement("TD");
			var td2 = d.createElement("TD");
			var td3 = d.createElement("TD");
			var td4 = d.createElement("TD");
			var td5 = d.createElement("TD");
			var td6 = d.createElement("TD");
			var td7 = d.createElement("TD");
			var td8 = d.createElement("TD");
			var td9 = d.createElement("TD");
			td1.className= "a-center";
			td2.style.display = "none";
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			row.appendChild(td4);
			row.appendChild(td5);
			row.appendChild(td6);
			row.appendChild(td7);
			row.appendChild(td8);
			row.appendChild(td9);
			
			// Наполняем ячейки
			td1.innerHTML = "<input type='checkbox' class='flat' name='table_records' />";
			td2.innerHTML = '<span class="" style="display: inline;">'+mes.id+'</span>';
			td3.innerHTML = '<span class="" style="display: inline;">'+mes.secondname+'</span>';
			td4.innerHTML = '<span class="" style="display: inline;">'+mes.name+'</span>';
			td5.innerHTML = '<span class="" style="display: inline;">'+mes.thirdname+'</span>';
			td6.innerHTML = '<span class="" style="display: inline;">'+mes.login+'</span>';
			td7.innerHTML = '<span class="" style="display: inline;">'+mes.password+'  |  '+mes.password2+'</span>';
			td8.innerHTML = '<span class="" style="display: inline;">'+mes.role+'</span>';
			td9.innerHTML =	'<span class="" style="display: inline;">'+mes.registration+'</span>';
			changePage(tableId);
		}else{
			alert("Что-то пошло не так!");
		}
	}
 

	function addRowToDb(tableId,forename,name,thirdname,password){
		uri = "/adminpanel/create_users_sql/";
		params='add=ok';
		params+='&secondname='+forename;
		params+='&name='+name;
		params+='&thirdname='+thirdname;
		params+='&password='+password;
		sqlAddWithCallback(tableId,params,uri,calback);	
	}
	function sqlAddWithCallback(tableId,params,uri,callback){
		var request = new ajaxRequest();

		request.onreadystatechange = function()
		{
			if (request.readyState==4)
			{
				if (request.status==200)
				{
					if (request.responseText != null)
					{
						 callback(tableId,request);
					}
					else alert ("Данные не полученны");
				}
				else alert ("Ошибка Ajax"+this.statusText);
			}
		}
		request.open("POST", uri, true);
		request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		request.send(params);
	}
	
	///////////
	//Функция для обновления постраничной нафигации таблицы при удалении и добавлении
	function changePage(r){
		var i=document.getElementById(r);
		var countTable = i.rows.length-1;
		var countPage= i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a").length-4;
		for (x=0;x<i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a").length;x++)
		{
			if (i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a")[x].hasAttribute('data-selected')){
				var page = i.parentNode.getElementsByClassName("page-navigation")[0].getElementsByTagName("a")[x].innerHTML;
			}
		}
		if (countTable>0){
			if (page>Math.ceil(countTable/10)){
				i.parentNode.getElementsByClassName("page-navigation")[0].remove();
				pagination(r,Math.ceil(countTable/10)-1);
			}else{
				i.parentNode.getElementsByClassName("page-navigation")[0].remove();
				pagination(r,page-1);
			}
		}else{
			i.parentNode.getElementsByClassName("page-navigation")[0].remove();
			pagination(r,0);
		}
	}
	// Функция для постраничной навигации для таблиц
	function pagination(r,startPage){
		$('#'+r).paginate({
			initialPage: startPage,
			optional: false,
			limit: 10,
			onSelect: function(obj, page) {
			  console.log('Page ' + page + ' selected!' );
			}
			});
	}
	//Функция для создания постраничной навигации при открытии страницы
	for (var x=1;x<=$('table').length;x++){
		$('#datatable'+x).paginate({
			optional: false,
			limit: 10,
			onSelect: function(obj, page) {
			  console.log('Page ' + page + ' selected!' );
			}
		});
	}

	function sql(params,uri){
		var request = new ajaxRequest();

		request.onreadystatechange = function()
		{
			if (request.readyState==4)
			{
				if (request.status==200)
				{
					if (request.responseText != null)
					{
					}
					else alert ("Данные не полученны");
				}
				else alert ("Ошибка Ajax"+this.statusText);
			}
		}
		request.open("POST", uri, true);
		request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		request.send(params);
	}
	function ajaxRequest()
	{
		try // IE
		{
			var request = new XMLHttpRequest()
		}
		catch(e1)
		{
			try//This IE 6+?
			{
				request = new ActiveXObject("Msxml2.XMLHTTP")
			}
			catch(e2)
			{
				try // This IE 5?
				{
					request = new ActiveXObject("Microsoft.XMLHTTP")
				}
				catch(e3)// This brouser not supported Ajax
				{
					request = false
				}
			}
		}
		return request
	}
	////////

	//Формирует рандомно пароль
	function str_rand() {
        var result       = '';
        var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        var max_position = words.length - 1;
            for( i = 0; i < 10; ++i ) {
                position = Math.floor ( Math.random() * max_position );
                result = result + words.substring(position, position + 1);
            }
        return result;
    }