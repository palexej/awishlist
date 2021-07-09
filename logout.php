<?php
	require 'db.php';
	unset($_SESSION['logged_user']);
	header('Location: https://awishlist.herokuapp.com/cataloge.php');
?>
