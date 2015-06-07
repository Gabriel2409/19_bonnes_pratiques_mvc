<?php 

function crypt_pass($pass)
{

	$salt1 = "79j@!a?sD";
	$salt2 = "=nPf2N(3";
	$pass_hache = sha1($salt1. sha1($pass). $salt2);
	return $pass_hache;
}