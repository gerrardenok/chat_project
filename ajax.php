<?php
	include 'data.inc.php';
	$base = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME) or die(mysqli_error());
	include 'lib.inc.php'; 

	function getUsersJSON($db, $id)
	{
		$sql = "SELECT login,status,id FROM users WHERE id<>$id";
		$arr = array();		
		if ($result = mysqli_query($db, $sql)) {

		   	while ($row = mysqli_fetch_assoc($result)) {
			    array_push($arr, $row);
			}

		    mysqli_free_result($result);
		}	
		return json_encode($arr);	
	}	

	function UserExit($db, $id)
	{
		$sql = "UPDATE users SET status=0 WHERE id=$id";
		mysqli_query($db, $sql) or die(mysqli_error()); 
	}
	
	function GetUserIDforLogin($db, $login)
	{
		$sql ="SELECT id FROM users WHERE login='" . $login . "'";
		if ($result = mysqli_query($db, $sql)) {

		    $obj = mysqli_fetch_array($result);

		    mysqli_free_result($result);
		}
		// АДОВ БЫДЛОКОД!
		return $obj[0];

	}

	function LodLOG($db, $from, $to)
	{
		$sql = "SELECT messege,date,from_user FROM messeges WHERE (from_user=$from AND to_user=$to) OR (from_user=$to AND to_user=$from) ORDER BY date";		
		$arr = array();		
		if ($result = mysqli_query($db, $sql)) {

		   	while ($row = mysqli_fetch_assoc($result)) {
			    array_push($arr, $row);
			}

		    mysqli_free_result($result);
		}	
		return json_encode($arr);	
	}

	function SendMSG($db, $from, $to, $messege)
	{
		$sql="INSERT INTO messeges(from_user, to_user, messege, date) VALUES($from, $to,'$messege', '". date("Y-m-d H:i:s") ."')";
		mysqli_query($db, $sql) or die(mysqli_error()); 	
	}
	
	if ( (isset($_POST['getUsers']) && ($_POST['getUsers'] == 'true')) 
		&& (isset($_POST['id']) && !empty($_POST['id'])) )
	{
		echo getUsersJSON($base, $_POST['id']);
	}

	if ( (isset($_POST['getLog']) && ($_POST['getLog'] == 'true')) && 
		(isset($_POST['from']) && !empty($_POST['from'])) && 
		(isset($_POST['to']) && !empty($_POST['to'])) )
	{
		echo LodLOG($base, $_POST['from'], $_POST['to']);
	}  

	if ( (isset($_POST['message']) && !empty($_POST['message'])) && 
		(isset($_POST['from']) && !empty($_POST['from'])) && 
		(isset($_POST['to']) && !empty($_POST['to'])) )
	{
		SendMSG($base, $_POST['from'], $_POST['to'] , $_POST['message']);
	}

	if ( isset($_POST['exit']) && !empty($_POST['id']) ) {
		UserExit($base, $_POST['id']);
	}

	mysqli_close($base);
?>