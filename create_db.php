<?php
	define("DB_HOST", "localhost");
	define("DB_LOGIN", "root");
	define("DB_PASSWORD", "10251994");
	define("DB_NAME", "chat_euro_ats");

mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die(mysql_error());

$sql = 'CREATE DATABASE ' . DB_NAME;
mysql_query($sql) or die(mysql_error());

mysql_select_db(DB_NAME) or die(mysql_error());

$sql = "
CREATE TABLE users (
	id int NOT NULL auto_increment,
	status bool default false,
	login varchar(25) default '',
	password char(32) default '',
	email varchar(25) default '', 
	PRIMARY KEY (id)
)";

mysql_query($sql) or die(mysql_error());

$sql = "
CREATE TABLE messeges (
	from_user int NOT NULL,
	to_user int  NOT NULL,
	messege varchar(255) default '',
	date DATETIME NOT NULL
)";

mysql_query($sql) or die(mysql_error());
mysql_close();

print '<p>DB was create sucsessful</p>';
?>
