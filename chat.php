<?php
	session_start();
	if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
		header("Location: log_in.php");
	}  
	if(isset($_GET['logout'])){	
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
	function drawMessege (messege, from, time) {
		if (from == convesetion_user_id) {
			from = 'interlocutor';
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


	$(document).ready(function(){
	
	goToServer("getUsers=true&id="+user_id, drawUsers ,'json');

	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if (exit==true) {
			goToServer("exit=true&id="+user_id);
			window.location = 'chat.php?logout=true';
		}		
	});

	$('div.user').live('click', function() {
		clearChatbox();		
		convesetion_user_id = $(this).attr('id');
		convesetion_user_name = $(this).html();
		goToServer("getLog=true&from="+user_id+"&to="+convesetion_user_id, DrawMesseges ,'json');
		$('div.user').css('background-color','white');
	    $(this).css('background-color','orange');
	});

	$("#submitmsg").click(function(){
		drawMessege ( clearMessege( $('#usermsg').val()) , 'ME'  );
		goToServer("message="+ clearMessege( $('#usermsg').val()) +"&from="+user_id+"&to="+convesetion_user_id );
		// console.log("message="+ clearMessege( $('#usermsg').val()) +"&from="+user_id+"&to="+convesetion_user_id );
		$('#usermsg').val('');
	});

	clearChatbox();
	clearUsers();

	// устанавливаем ообновление сообщений и юзеров
	var timer = setInterval(function() {
		clearChatbox();
		clearUsers();
		goToServer("getUsers=true&id="+user_id, drawUsers ,'json');
		goToServer("getLog=true&from="+user_id+"&to="+convesetion_user_id, DrawMesseges ,'json');

	}, 3000)

	});

	$(window).bind('beforeunload', function() {
        var exit = confirm("Are you sure you want to end the session?");
		if (exit==true) {
			window.location = 'chat.php?logout=true';
		}	    
	});
</script>
</head>
<body>
	<div id="wrapper">
		<div id="menu">
			<p class="welcome">Welcome, <b><?php echo $_SESSION['username'] ?></b></p>
			<p class="exit"><a href="#" id="exit">Exit</a> </p> 
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
