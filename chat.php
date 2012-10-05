<?php
	include 'config.php';
	session_start();
	if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
		header("Location: log_in.php");
	}  
	if(isset($_GET['logout'])){	
		unset($_SESSION['user_id']);
		unset($_SESSION['username']);
		session_destroy();
		header("Location: log_in.php"); 
	}
?>

<!DOCTYPE html>
<head>
<meta charset="UTF-8">	
<title>Chat - Customer Module</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="chat.js"></script>
<script type="text/javascript">
	var convesetion_user_id;
	var conversation_user_name;
	var user_id = <?php echo json_encode($_SESSION['user_id']); ?>;
	var username = <?php echo json_encode($_SESSION['username']); ?>;
	var timeInterval = <?php echo "'".TIME_PER_CICLE."'" ?>;
	function drawMessege (messege, from, time) {
		if (from == convesetion_user_id) {
			from = convesetion_user_name;
		} else if (from == user_id) {
			from = 'ME'; 
		}
		
		if (time) {
			$('<div class="msgln">('+time+') <b>'+from+'</b>: '+messege+' <br></div>').appendTo('#chatbox');
		} else {
			$('<div class="msgln">('+getCurrentTime()+') <b>'+from+'</b>: '+messege+' <br></div>').appendTo('#chatbox');	
		}
	}
	function DrawMesseges(messeges) {
		for(i=0; i<messeges.length; i++) {
			drawMessege(messeges[i].messege, messeges[i].from_user, messeges[i].date ); 
		}
	}

	function drawUser (user) {
		var tmp = '';
		if (user.id == convesetion_user_id) {
			tmp = 'active';
		}
		if (user.status == 1) {
			$('<div class="user online '+tmp+'" title="'+user.login+'" id="'+user.id+'">'+userShorterName(user.login)+'</div>').appendTo('#peoplebox');	
		} else {
			$('<div class="user offline '+tmp+'" title="'+user.login+'" id="'+user.id+'">'+userShorterName(user.login)+'</div>').appendTo('#peoplebox');
		}
	}

	function drawUsers (users) {
		for(i = 0; i < users.length; i++) {
			drawUser(users[i]);
		}
	}


	$(document).ready(function(){
	var timer;
	goToServer("getUsers=true&id="+user_id, drawUsers ,'json');

	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if (exit==true) {
			goToServer("exit=true&id="+user_id);
			window.location = 'chat.php?logout=true';
		}		
	});

	$("#Log").click(function(){
		if (timer) {
	    	clearInterval(timer);
	    	timer = null;
	    }
	    if (convesetion_user_id) {
			goToServer("getFullLog=true&from="+user_id+"&to="+convesetion_user_id, DrawMesseges ,'json');		
		}
	});

	$('div.user').live('click', function() {
		clearChatbox();		
		convesetion_user_id = $(this).attr('id');
		convesetion_user_name = $(this).html();
		goToServer("getLog=true&from="+user_id+"&to="+convesetion_user_id, DrawMesseges ,'json');
		$('div.user').removeClass('active');

	    var tmp = $(this).attr('class');
	    $(this).addClass(tmp+' '+'active');

	    // устанавливаем обновление сообщений и юзеров
	    if (timer) {
	    	clearInterval(timer);
	    	timer = null;
	    }
		timer = setInterval(function() {
			clearChatbox();
			clearUsers();
			goToServer("getUsers=true&id="+user_id, drawUsers ,'json');
			goToServer("getLog=true&from="+user_id+"&to="+convesetion_user_id, DrawMesseges ,'json');

		}, timeInterval)
	});

	$("#submitmsg").click(function() {
		if (convesetion_user_id) {
			drawMessege ( clearMessege( $('#usermsg').val()) , 'ME'  );
			goToServer("message="+ clearMessege( $('#usermsg').val()) +"&from="+user_id+"&to="+convesetion_user_id );
			console.log("message="+ clearMessege( $('#usermsg').val()) +"&from="+user_id+"&to="+convesetion_user_id );
		}
		$('#usermsg').val('');
	});

	clearChatbox();
	clearUsers();

	});

	// -------------------- FACEPALM --------------
	$(window).unload(function() { // работает только для обновления страницы (F5)
	  	goToServer("exit=true&id="+user_id);
		window.location = 'chat.php?logout=true';
	});

	$(window).bind('beforeunload', function() // работает только для закрытия вкладки
        { 
           	goToServer("exit=true&id="+user_id);
			window.location = 'chat.php?logout=true';
        } 
    );
	// ---------------------------------------------
</script>
</head>
<body>
	<div id="wrapper">
		<div id="menu">
			<p class="welcome">Welcome, <b><?php echo $_SESSION['username'] ?></b></p>
			<p class="exit"> <a href="#" id="Log">Log</a> &nbsp; <a href="#" id="exit">Exit</a> </p> 
		</div>
	<div>	
		<div id="chatbox">
		</div>
		<div id="peoplebox">
		</div>
	</div>
	<form name="message" action="">
		<input name="usermsg" type="text" id="usermsg" size="63" />
		<input name="submitmsg" type="button"  id="submitmsg" value="Send" />
	</form>
	</div>
</body>
</html>
