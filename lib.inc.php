<?php 
	function CheckData($db, $field, $data)
	{
		$sql = "SELECT NOT EXISTS (SELECT * FROM users WHERE $field = '{$data}')";

		if ($result = mysqli_query($db, $sql)) {

		    $obj = mysqli_fetch_array($result);

		    mysqli_free_result($result);
		}
		// АДОВ БЫДЛОКОД!
		return $obj[0];
	}

	function CheckUserData($db, $login, $password, $email)
	{
		$sql = "SELECT EXISTS (SELECT * FROM users WHERE (login='{$login}' AND password='{$password}' AND email='{$email}'))";

		if ($result = mysqli_query($db, $sql)) {

		    $obj = mysqli_fetch_array($result);

		    mysqli_free_result($result);
		}
		return $obj[0];
	}

	function CheckUserStatus($db, $login, $password, $email)
	{
		$sql = "SELECT status FROM users WHERE (login='{$login}' AND password='{$password}' AND email='{$email}')";

		if ($result = mysqli_query($db, $sql)) {

		    $obj = mysqli_fetch_array($result);

		    mysqli_free_result($result);
		}
		return $obj[0];
	}


	function getID($db, $login, $password, $email)
	{
		// АДОВ БЫДЛОКОД!
		$sql = "SELECT id FROM users WHERE login='{$login}' AND password='{$password}' AND email='{$email}'  ";
		if ($result = mysqli_query($db, $sql)) {

		    $obj = mysqli_fetch_array($result);

		    mysqli_free_result($result);
		}	
		return $obj[0];	
	}

	function AddSatl($password)
	{
		return md5($password . SALT); 
	}

	function clearData($data, $type="s")
	{
		switch ($type) {
			case 's':
				$data = mysql_real_escape_string(trim(strip_tags($data))); 
				break;
			case 'i':
				$data = (int) $data;
				break;	
		}
		return $data;
	}

	function UserLogIN($db, $id)
	{		
		$sql = "UPDATE users SET status=1 WHERE id=$id";
		mysqli_query($db, $sql) or die(mysqli_error());
	}	

 	function CreateUser($db, $login, $password, $email)
	{
		// раз регистритуется значит он в системе
		$sql = "INSERT INTO users(login, password, email, status) VALUES('$login', '$password', '$email', 1)";		
		mysqli_query($db, $sql) or die(mysqli_error());
		return true;
	}

 ?>