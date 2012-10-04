<?php 
	$error = '';
	include 'data.inc.php';
	if (   	( isset($_POST['name']) && !empty($_POST['name']) ) &&
			( isset($_POST['password']) && !empty($_POST['password']) )	&&
			( isset($_POST['email']) && !empty($_POST['email']) )
	  ) {

		$base = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME) or die(mysqli_error());
		include 'lib.inc.php';

		$login = clearData($_POST['name']);
		$password = AddSatl(clearData($_POST['password']));
		$email = clearData($_POST['email']);
		if ( CheckUserData($base, $login, $password, $email) ) {
			session_start();
			$_SESSION['user_id'] = getID($base, $login, $password, $email);
			$_SESSION['username'] = $login;
			UserLogIN($base, $_SESSION['user_id']);
			header("Location: chat.php");
		} else {
			$error = '<b>User with this data doesn\'t  is exist.</b>';
		}
		mysqli_close($base);
	}

 ?>


<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Chat - Customer Module</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
	function displayResult(el, val) {
		if (val) {
			el.next().attr('class', 'form-td reg accept');
		} else {
			el.next().attr('class', 'form-td reg error');
		}
		
	}
 
	function conditionForEmail(el) { 
		var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
		if (email_regex.test($(el).val())) {
			return true;
		} else 
			return false;
	}

	function conditionForPassword(el) {
		
		if ($(el).val().length >= 8) {
			return true;
		} else {
			return false;
		}
	}

	function conditionForName(el) {
		console.log('work');
		if ($(el).val().length <= 25) {
			return true;
		} else {
			return false;
		}
	}

	function isValidateField(el, condition) {
		if (condition) {
				if ( ($(el).val() !== '') && condition(el) ) {
					return true;
				} else {
					return false;
				} 
			}
			else {
				if ($(el).val() == '') {
					return false;
				} else {
					return true;
				}
		}
	}


	$('#name').blur(function() {
		displayResult($('#name'), isValidateField('#name', conditionForName));
	});

	$('#password').blur(function() {
		displayResult($('#password'), isValidateField('#password', conditionForPassword));
	});

	$('#email').blur(function() {
		displayResult($('#email'), isValidateField('#email', conditionForEmail ));
	});

	$('#form').submit(function() {
		if ( isValidateField('#name', conditionForName) && 
			 isValidateField('#password', conditionForPassword) && 
			 isValidateField('#email', conditionForEmail) ) {
			// some code
		} else {
			console.log ("ERROR");
			$('#info_block').html('<b>Please, fill out all fields correctly</b>');
			displayResult($('#name'), isValidateField('#name', conditionForName));
			displayResult($('#password'), isValidateField('#password', conditionForPassword));
			displayResult($('#email'), isValidateField('#email', conditionForEmail ));
			return false;
		}
	});
	

});
</script>
</head>
	<div id="loginform">
		<h3>Log in</h3>
		<p id="info_block">Please enter your name and password to continue:</p>	
		<p><?php echo $error ?></p>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form-table" id="form">
		<div class="row">
			<label for="name" class="form-td">Name:</label>
			<input type="text" name="name" id="name" class="form-td"/>
			<div class="form-td reg"></div> 
		</div>
		<div class="row">
			<label for="email" class="form-td">E-mail:</label>
			<input type="text" name="email" id="email" class="form-td" />
			<div class="form-td reg"></div> 
		</div>
		<div class="row">
			<label for="password" class="form-td">Password:</label>
			<input type="password" name="password" id="password" class="form-td" />
			<div class="form-td reg"></div> 
		</div>
		<div class="row">
			<div class="form-td"></div>
				<div class="form-td">
				<input type="submit" name="enter" id="enter" value="Enter" /> || <a href="registration.php">Registration</a>	
				</div>
		</div>
	</form>
	</div>
	</body>
</html>