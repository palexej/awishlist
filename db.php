<?php
require 'libs/rb.php';
R::setup( 'mysql:host=us-cdbr-east-06.cleardb.net; dbname=heroku_8577067324d828a','be0fd66079ab9f', 'ed9d44bd' );

if ( !R::testconnection() )
{
		exit ('Нет соединения с базой данных');
}
R::freeze(true);
session_start();
