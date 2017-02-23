<?php
/* 
 * Fikaba JSON API
 * Written by Knarka
 *
 * See docs/API.md for more information
 */

include('config.php');
include "strings/".LANGUAGE.".php";

if(!ENABLEAPI) die(S_NOAPI);

if(!isset($_GET['mode'])) die('Malformed request');
else $mode = $_GET['mode'];

if(!$con=mysql_connect(SQLHOST,SQLUSER,SQLPASS)){
	echo S_SQLCONF;	//unable to connect to DB (wrong user/pass?)
	exit;
}

$db_id=mysql_select_db(SQLDB,$con);
if(!$db_id){echo S_SQLDBSF;}

function mysql_call($query){ // XXX update this if updated in imgboard.php XXX
	$ret=mysql_query($query) or die(mysql_error());
	if(!$ret){
		echo $query."<br />";
	}
	return $ret;
}

function jsoncatalog() {
	$posts = array();
	$result = mysql_call("select * from ".POSTTABLE." order by root desc");
	while($row=mysql_fetch_row($result)){
		list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fname,$fsize,$root,$resto,$ip,$id)=$row;
		if((int)$resto!=0) continue;
		if(!DISP_ID) $id='';
		$posts[] = array("no" => $no, "now" => $now, "name" => $name, "email" => $email, "sub" => $sub, "com" => $com, "ext" => $ext, "w" => $w, "h" => $h, "tim" => $tim, "time" => $time, "md5" => $md5, "fname" => $fname, "fsize" => $fsize, "root" => $root, "resto" => $resto, "id" => $id);
	}
	mysql_free_result($result);
	return $posts;
}

function jsonres($res) {
	$posts = array();
	if(!isset($res) || $res==0) die('Malformed request');
	$result = mysql_call("select * from ".POSTTABLE." where root>0 and no=".$res." order by root desc");
	while($row=mysql_fetch_row($result)){
		list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fname,$fsize,$root,$resto,$ip,$id)=$row;
		if(!DISP_ID) $id='';
		$posts[] = array("no" => $no, "now" => $now, "name" => $name, "email" => $email, "sub" => $sub, "com" => $com, "ext" => $ext, "w" => $w, "h" => $h, "tim" => $tim, "time" => $time, "md5" => $md5, "fname" => $fname, "fsize" => $fsize, "root" => $root, "resto" => $resto, "id" => $id);
	}
	mysql_free_result($result);
	$result = mysql_call("select * from ".POSTTABLE." where resto=".$res." order by root desc");
	while($row=mysql_fetch_row($result)){
		list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fname,$fsize,$root,$resto,$ip,$id)=$row;
		if(!DISP_ID) $id='';
		$posts[] = array("no" => $no, "now" => $now, "name" => $name, "email" => $email, "sub" => $sub, "com" => $com, "ext" => $ext, "w" => $w, "h" => $h, "tim" => $tim, "time" => $time, "md5" => $md5, "fname" => $fname, "fsize" => $fsize, "root" => $root, "resto" => $resto, "id" => $id);
	}
	mysql_free_result($result);
	return $posts;
}

switch($mode){
	case 'catalog':
		$jsonout = jsoncatalog();
		break;
	case 'res':
		$jsonout = jsonres($_GET['res']);
		break;
	default:
		break;
}

$jsonout = json_encode($jsonout);
header('Content-Type: application/json');
die($jsonout);
