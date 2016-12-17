<?php
# Fikaba 000035
#
# For setup instructions and latest version, please visit:
# https://github.com/knarka/fikaba
#
# Based on GazouBBS, Futaba, and Futallaby

define(VERSION, '000035');

if(!file_exists('config.php')){
	include "strings/en.php";
	die(S_NOTCONFIGURED);
}

include "config.php";
include "strings/".LANGUAGE.".php";		//String resource file

if(LOCKDOWN){
	session_start();
	unlink(PHP_SELF2);			//remove static index file

	// if not trying to do something other than managing, die
	if(!isset($_SESSION['capcode']) && !($_GET['mode'] == 'admin' || $_POST['mode'] == 'admin')){
		die(S_LOCKDOWN);
	}
}

extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);
extract($_COOKIE, EXTR_SKIP);
$upfile_name=$_FILES["upfile"]["name"];
$upfile=$_FILES["upfile"]["tmp_name"];

$path = realpath("./").'/'.IMG_DIR;
ignore_user_abort(TRUE);

$badstring = array("dummy_string","dummy_string2"); // Refused text
$badfile = array("dummy","dummy2"); //Refused files (md5 hashes)
$badip = array("addr1\\.dummy\\.com","addr2\\.dummy\\.com"); //Refused hosts (IP bans)

if(!$con=mysql_connect(SQLHOST,SQLUSER,SQLPASS)){
	echo S_SQLCONF;	//unable to connect to DB (wrong user/pass?)
	exit;
}

if (!file_exists(IMG_DIR) && !is_dir(IMG_DIR)){
	mkdir(IMG_DIR, 0777);
	echo(IMG_DIR.': '.S_FCREATE);
}
if (!file_exists(THUMB_DIR) && !is_dir(THUMB_DIR)){
	mkdir(THUMB_DIR, 0777);
	echo(THUMB_DIR.': '.S_FCREATE);
}

$db_id=mysql_select_db(SQLDB,$con);
if(!$db_id){echo S_SQLDBSF;}

if (!table_exist(POSTTABLE)) {
	echo (POSTTABLE.': '.S_TCREATE);
	$result = mysql_call("create table ".POSTTABLE." (primary key(no),
		no    int not null auto_increment,
		now   text,
		name  text,
		email text,
		sub   text,
		com   text,
		host  text,
		pwd   text,
		ext   text,
		w     int,
		h     int,
		tim   text,
		time  int,
		md5   text,
		fsize int,
		root  timestamp,
		resto int,
		ip    text,
		id    text)");
	if(!$result){echo S_TCREATEF;}
	updatelog(); // in case of a database wipe or something
}

if (!table_exist(BANTABLE)){
	echo (BANTABLE.': '.S_TCREATE);
	$result = mysql_call("create table ".BANTABLE." (ip text not null,
		start int,
		expires int,
		reason text)");
	if(!$result){echo S_TCREATEF;}
}

if (!table_exist(MANATABLE)){
	echo (MANATABLE.': '.S_TCREATE);
	$result = mysql_call("create table ".MANATABLE." (name text not null,
		password text not null,
		capcode text not null,
		candel int not null,
		canban int not null,
		cancap int not null,
		canacc int not null)");
	if(!$result){echo S_TCREATEF;}
	$query="insert into ".MANATABLE." (name,password,capcode,candel,canban,cancap,canacc) values ('DUMMY', 'REPLACEME','',0,0,0,1)";
	if(!$result=mysql_call($query)){echo S_SQLFAIL;}  //post registration
	mysql_free_result($result);
}

function humantime($time){
	$youbi = array(S_SUN, S_MON, S_TUE, S_WED, S_THU, S_FRI, S_SAT);
	$yd = $youbi[gmdate("w", $time+9*60*60)];
	return gmdate("y/m/d",$time+9*60*60)."(".(string)$yd.")".gmdate("H:i",$time+9*60*60);
}

function updatelog($resno=0){
	global $path;

	$find = false;
	$resno=(int)$resno;
	if($resno){
		$result = mysql_call("select * from ".POSTTABLE." where root>0 and no=$resno");
		if($result){
			$find = mysql_fetch_row($result);
			mysql_free_result($result);
		}
		if(!$find) error(S_REPORTERR);
	}
	if($resno){
		if(!$treeline=mysql_call("select * from ".POSTTABLE." where root>0 and no=".$resno." order by root desc")){echo S_SQLFAIL;}
	}else{
		if(!$treeline=mysql_call("select * from ".POSTTABLE." where root>0 and resto=0 order by root desc")){echo S_SQLFAIL;}
	}

	//Finding the last entry number
	if(!$result=mysql_call("select max(no) from ".POSTTABLE)){echo S_SQLFAIL;}
	$row=mysql_fetch_array($result);
	$lastno=(int)$row[0];
	mysql_free_result($result);

	$counttree=mysql_num_rows($treeline);
	if(!$counttree){
		$logfilename=PHP_SELF2;
		$dat='';
		head($dat);
		form($dat,$resno);
		$fp = fopen($logfilename, "w");
		set_file_buffer($fp, 0);
		rewind($fp);
		fputs($fp, $dat);
		fclose($fp);
		chmod($logfilename,0666);
	}
	for($page=0;$page<$counttree;$page+=PAGE_DEF){
		$dat='';
		head($dat);
		form($dat,$resno);
		if(!$resno){
			$st = $page;
		}
		$dat.='<form action="'.PHP_SELF.'" method="post">';
	
		for($i = $st; $i < $st+PAGE_DEF; $i++){
			list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fsize,$root,$resto,$ip,$id)=mysql_fetch_row($treeline);
			if(!$no) break;
	
			// URL and link
			if($email) $name = "<a href=\"mailto:$email\">$name</a>";
			$com = auto_link($com);
			$com = eregi_replace("&gt;", ">", $com);
			$com = eregi_replace("\>\>([0-9]+)", "<a href='".$_SERVER['REQUEST_URI']."#r\\1'>&gt;&gt;\\1</a>", $com);
			$com = eregi_replace("(^|>)(\>[^<]*)", "\\1<div class=\"unkfunc\">\\2</div>", $com);
			// Picture file name
			$img = $path.$tim.$ext;
			$src = IMG_DIR.$tim.$ext;
			// img tag creation
			$imgsrc = "";
			if ($ext && $ext == ".swf") {
				$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"file.png\" alt=\"".$size." B\" /></a><br /><br />";
				$dat.="<span class=\"filesize\">".S_PICNAME."<a href=\"$src\" target=\"_blank\">$tim$ext</a>-($size B)</span>$imgsrc";
			} else if($ext){
				$size = $fsize;//file size displayed in alt text
				if($w && $h){//when there is size...
					if(@is_file(THUMB_DIR.$tim.'s.jpg')){
						$imgsrc = "    <span class=\"thumbnailmsg\">".S_THUMB."</span><br /><a href=\"".$src."\" target=\"_blank\"><img src=\"".THUMB_DIR.$tim.'s.jpg'."\" width=\"$w\" height=\"$h\" alt=\"".$size." B\" /></a><br />";
					}else{
						$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"$src\" width=\"$w\" height=\"$h\" alt=\"".$size." B\" /></a><br />";
					}
				}else{
					$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"$src\" alt=\"".$size." B\" /></a><br />";
				}
				$dat.="<span class=\"filesize\">".S_PICNAME."<a href=\"$src\" target=\"_blank\">$tim$ext</a>-($size B)</span>$imgsrc";
			}
			if(DISP_ID){ $userid = "ID:$id"; }
			else{ $userid = ""; }
			//  Main creation
			$dat.="<input type=\"checkbox\" name=\"$no\" value=\"delete\" /><span class=\"filetitle\">$sub</span>   \n";
			$dat.="<span class=\"postername\">$name</span> $now $userid <a class=\"reflink\" href=\"#r$no\">No.</a> <a class=\"reflink\" href=\"#\" onClick=\"addref('$no');\">$no</a> &nbsp; \n";
			if(!$resno) $dat.="[<a href=\"".PHP_SELF."?res=$no\">".S_REPLY."</a>]";
			$dat.="\n<blockquote>$com</blockquote>";
	
			if(!$resline=mysql_call("select * from ".POSTTABLE." where resto=".$no." order by no")) echo S_SQLFAIL;
			$countres=mysql_num_rows($resline);
	
			if(!$resno){
				$s=$countres - COLLAPSENUM;
				if($s<0){$s=0;}
				elseif($s>0){
					$dat.="<span class=\"omittedposts\">".$s.S_ABBR."</span><br />\n";
				}
			}else{$s=0;}
	
			while($resrow=mysql_fetch_row($resline)){
				if($s>0){$s--;continue;}
				list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fsize,$root,$resto,$ip,$id)=$resrow;
				if(!$no){break;}
	
				if($sub)
					$replytitle = "<span class=\"replytitle\">$sub</span>";
	
				// URL and e-mail
				if($email) $name = "<a href=\"mailto:$email\">$name</a>";
				$com = auto_link($com);
				$com = eregi_replace("&gt;", ">", $com);
				$com = eregi_replace("\>\>([0-9]+)", "<a href='".$_SERVER['REQUEST_URI']."#r\\1'>&gt;&gt;\\1</a>", $com);
				$com = eregi_replace("(^|>)(\>[^<]*)", "\\1<div class=\"unkfunc\">\\2</div>", $com);
				if(DISP_ID){ $userid = "ID:$id"; }
				else{ $userid = ""; }
				// Main creation
				$dat.="<table id='r$no'><tr><td class=\"doubledash\">&gt;&gt;</td><td class=\"reply\">\n";
				$dat.="<span class='intro'><input type=\"checkbox\" name=\"$no\" value=\"delete\" />$replytitle \n";
				$dat.="<span class=\"commentpostername\">$name</span> $now $userid <a class=\"reflink\" href=\"#r$no\">No.</a><a class=\"reflink\" href=\"#\" onClick=\"addref('$no');\">$no</a> &nbsp;<br /></span> \n";
				if ($ext && $ext == ".swf") {
					$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"file.png\" alt=\"".$size." B\" /></a><br /><br />";
					$dat.="<span class=\"filesize commentfile\">".S_PICNAME."<a href=\"$src\" target=\"_blank\">$tim$ext</a>-($size B)</span> <br />$imgsrc";
				} else if($ext){
					$src = IMG_DIR.$tim.$ext;
					$size = $fsize;//file size displayed in alt text
					if($w && $h){//when there is size...
						if(@is_file(THUMB_DIR.$tim.'s.jpg')){
							$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"".THUMB_DIR.$tim.'s.jpg'."\" width=\"$w\" height=\"$h\" alt=\"".$size." B\" /></a>";
						}else{
							$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"".$src."\" width=\"$w\" height=\"$h\" alt=\"".$size." B\" /></a><br />";
						}
					}else{
						$imgsrc = "<a href=\"".$src."\" target=\"_blank\"><img src=\"".$src."\" alt=\"".$size." B\" /></a><br /><br />";
					}
					if(@is_file(THUMB_DIR.$tim.'s.jpg')){
						$dat.="<span class=\"filesize commentfile\">".S_PICNAME."<a href=\"$src\" target=\"_blank\">$tim$ext</a>-($size B)</span> <span class=\"thumbnailmsg\">".S_THUMB."</span><br />$imgsrc";
					}else{
						$dat.="<span class=\"filesize commentfile\">".S_PICNAME."<a href=\"$src\" target=\"_blank\">$tim$ext</a>-($size B)</span> <br />$imgsrc";
					}
				}
				$dat.="<blockquote>$com</blockquote>";
				$dat.="</td></tr></table>\n";
			}
		$dat.="<br class=\"leftclear\" /><hr />\n";
		clearstatcache();//clear stat cache of a file
		mysql_free_result($resline);
		$p++;
		if($resno){break;} //only one tree line at time of res
	}
	$dat.='<table class="righted"><tr><td class="nowrap righted">
		<input type="hidden" name="mode" value="usrdel" />'.S_REPDEL.'[<input type="checkbox" name="onlyimgdel" value="on" />'.S_DELPICONLY.']<br />
		'.S_DELKEY.'<input type="password" name="pwd" size="8" maxlength="8" value="" />
		<input type="submit" value="'.S_DELETE.'" /></td></tr></table></form>
		<script type="text/javascript"><!--
	l();
	//--></script>';
	
	if(!$resno){ // if not in res display mode
		$prev = $st - PAGE_DEF;
		$next = $st + PAGE_DEF;
		//  Page processing
		$dat.="<table><tr>";
		if($prev >= 0){
			if($prev==0){
				$dat.="<form action=\"".PHP_SELF2."\" method=\"get\"><td>";
			}else{
				$dat.="<form action=\"".$prev/PAGE_DEF.PHP_EXT."\" method=\"get\"><td>";
			}
			$dat.="<input type=\"submit\" value=\"".S_PREV."\" />";
			$dat.="</td></form>";
		}
	
		$dat.="<td>";
		for($i = 0; $i < $counttree ; $i+=PAGE_DEF){
			if($i&&!($i%(PAGE_DEF*2))){$dat.=" ";}
			if($st==$i){$dat.="[".($i/PAGE_DEF)."] ";}
			else{
				if($i==0){$dat.="[<a href=\"".PHP_SELF2."\">0</a>] ";}
				else{$dat.="[<a href=\"".($i/PAGE_DEF).PHP_EXT."\">".($i/PAGE_DEF)."</a>] ";}
			}
		}
		$dat.="</td>";
	
		if($p >= PAGE_DEF && $counttree > $next){
			$dat.="<form action=\"".$next/PAGE_DEF.PHP_EXT."\" method=\"get\"><td>";
			$dat.="<input type=\"submit\" value=\"".S_NEXT."\" />";
			$dat.="</form></td>";
		}
		$dat.="</tr></table><br class=\"allclear\" />\n";
		} else { // in res display mode
			$dat.="<table></table><br class=\"allclear\" />\n";
		}
		foot($dat);
		if(ECHOALL){echo $dat;break;}
		if($resno){echo $dat;break;}
		if($page==0){$logfilename=PHP_SELF2;}
		else{$logfilename=$page/PAGE_DEF.PHP_EXT;}
		$fp = fopen($logfilename, "w");
		set_file_buffer($fp, 0);
		rewind($fp);
		fputs($fp, $dat);
		fclose($fp);
		chmod($logfilename,0666);
	}
	mysql_free_result($treeline);
}

function mysql_call($query){
	$ret=mysql_query($query) or die(mysql_error());
	if(!$ret){
		echo $query."<br />";
	}
	return $ret;
}

function head(&$dat){
	global $STYLES;
	$titlepart = '';
	if (SHOWTITLEIMG == 1) {
		$titlepart.= '<img src="'.TITLEIMG.'" alt="'.TITLE.'" />';
		if (SHOWTITLETXT == 1) {$titlepart.= '<br />';}
	} else if (SHOWTITLEIMG == 2) {
		$titlepart.= '<img src="'.(include(TITLEIMG)).'" onclick="this.src=this.src;" alt="'.TITLE.'" />';
		if (SHOWTITLETXT == 1) {$titlepart.= '<br />';}
	}
	if (SHOWTITLETXT == 1) {
		$titlepart.= ''.TITLE.'';
	}
	$dat.='<!doctype html>
<html><head>
<meta http-equiv="content-type"  content="text/html;charset=utf-8" />
<!-- meta HTTP-EQUIV="pragma" CONTENT="no-cache" -->
<script type="text/javascript" src="js/style.js"></script>';
	foreach($STYLES as $stylename => $stylefile){
		$dat.='<link rel="alternate stylesheet" type="text/css" href="'.$stylefile.'" title="'.$stylename.'" />';
	}
	$dat.='<script type="text/javascript">getUserFav("'.CSSDEFAULT.'");</script>
<title>'.TITLE.'</title>
<script type="text/javascript"><!--
function l(e){var P=getCookie("pwdc"),N=getCookie("namec"),i;with(document){for(i=0;i<forms.length;i++){if(forms[i].pwd)with(forms[i]){if(!pwd.value)pwd.value=P;}if(forms[i].name)with(forms[i]){if(!name.value)name.value=N;}}}};function getCookie(key, tmp1, tmp2, xx1, xx2, xx3) {tmp1 = " " + document.cookie + ";";xx1 = xx2 = 0;len = tmp1.length;	while (xx1 < len) {xx2 = tmp1.indexOf(";", xx1);tmp2 = tmp1.substring(xx1 + 1, xx2);xx3 = tmp2.indexOf("=");if (tmp2.substring(0, xx3) == key) {return(unescape(tmp2.substring(xx3 + 1, xx2 - xx1 - 1)));}xx1 = xx2 + 1;}return("");}
//--></script>
<script type="text/javascript">function addref(postid) {document.getElementById("com").value += ">>" + postid + "\n";}</script>';
	if(OEKAKI_ENABLED){$dat.='<script type="text/javascript" src="js/ritare/ritare.js"></script><link rel="stylesheet" type="text/css" href="js/ritare/ritare.css" />';}
	$dat.='</head>
	<body>
	'.$titlebar.'
	<div class="styles"><select>
	<option disabled selected value>---</option>';
	foreach($STYLES as $stylename => $stylefile){
		$dat.='<option onClick="changeStyle(\''.$stylename.'\')">'.$stylename.'</option> ';
	}
	$dat.='</select></div>
	<div class="adminbar">
	[<a href="'.HOME.'" target="_top">'.S_HOME.'</a>]
	[<a href="'.PHP_SELF.'?mode=catalog" target="_top">'.S_CATALOGBUTTON.'</a>]
	[<a href="'.PHP_SELF.'?mode=admin">'.S_ADMIN.'</a>]
	</div>
	<div class="logo">'.$titlepart.'</div><hr /><br /><br />';
}
/* Contribution form */
function form(&$dat,$resno,$admin=""){
	session_start();
	$maxbyte = MAX_KB * 1024;
	$no=$resno;
	if($admin){
		$msg = "<em>".S_NOTAGS."</em>";
	}
	$dat.=$msg.'<div class="centered"><div class="postarea">
		<form id="postform" action="'.PHP_SELF.'" method="post" enctype="multipart/form-data" style="display: inline-block;">
		<input type="hidden" name="mode" value="regist" />
		'.$hidden.'
		<input type="hidden" name="MAX_FILE_SIZE" value="'.$maxbyte.'" />';
	if($no){$dat.='<input type="hidden" name="resto" value="'.$no.'" />';}
	$dat.='<table>';
	if(!$admin){
		if(!$resno) { $dat.='<tr><td class="postblocktitle" colspan=2>'.S_NEWTHREAD.'</td></tr>'; }
		else { $dat.='<tr><td class="postblocktitle" colspan=2>'.S_POSTING." <a href=\"".PHP_SELF2."\">[".S_RETURN."]</a></td></tr>"; }
	}
	if(!FORCED_ANON||$admin)
		$dat.='<tr><td class="postblock">'.S_NAME.'</td><td><input type="text" name="name" value="'.$_SESSION['name'].'" size="35" /></td></tr>';
	if($admin && $_SESSION['cancap']){
		$dat.='<tr><td class="postblock">'.S_CAPCODE.'</td><td><input type="checkbox" name="capcode" value="on" checked="checked" size="35" /> ('.$_SESSION['capcode'].')</td></tr>
		<tr><td class="postblock">'.S_REPLYTO.'</td><td><input type="text" name="resto" size="35" value="0" /></td></tr>';
	}
	$dat.='<tr><td class="postblock">'.S_EMAIL.'</td><td><input type="text" name="email" size="35" /></td></tr>
	<tr><td class="postblock">'.S_SUBJECT.'</td><td><input type="text" name="sub" size="35" />
	<input type="submit" value="'.S_SUBMIT.'" /></td></tr>
	<tr><td class="postblock">'.S_COMMENT.'</td><td><textarea id="com" name="com" cols="50" rows="4"></textarea></td></tr>';
	if(OEKAKI_ENABLED){$dat.='<tr><td class="postblock">'.S_OEKAKI.'</td><td id="oekakiparent"><script type="text/javascript">function startoekaki(){Ritare.start({parentel:"oekakiparent",onFinish:function(e){newfield=document.createElement("input");newfield.type="hidden";newfield.name="oekaki";newfield.id="oekakifile";newfield.value=(Ritare.canvas.toDataURL(\'image/png\')); document.getElementById("postform").appendChild(newfield);alert(\'Oekaki saved!\');},width:370,height:300});};</script><p id="oekakistarter" onclick="startoekaki();var el = document.getElementById(\'oekakistarter\');el.parentNode.removeChild(el);var el = document.getElementById(\'filerow\');el.parentNode.removeChild(el)">'.S_OEKAKILOAD.'</p></td></tr>';}
	$dat.='<tr id="filerow"><td class="postblock">'.S_UPLOADFILE.'</td>
<td><input type="file" name="upfile" size="35" />';
	if(!$resno && !FORCEIMAGE){$dat.='[<label><input type="checkbox" name="textonly" value="on" />'.S_NOFILE.'</label>]';}
	$dat.='</td></tr><tr><td class="postblock">'.S_DELPASS.'</td><td><input type="password" name="pwd" size="18" maxlength="8" value="" /> '.S_DELEXPL.'</td></tr>
<tr><td colspan="2">
<div class="rules lefted">';
	if (SWF_ENABLED) $dat .= S_RULES_SWF.'</div></td></tr></table></form></div></div><hr />';
	else $dat .= S_RULES.'</div></td></tr></table></form></div></div><hr />';
}

function fakefoot(){
	$dat = '';
	foot($dat);
	return $dat;
}

/* Footer */
function foot(&$dat){
	$dat.="
<div class=\"footer\">".S_FOOT."<br />
Fikaba ".S_VERSION." ".VERSION."<br />
".FOOTTEXT."
</div>
</body></html>\n";
}

function error($mes,$dest=''){ /* Basically a fancy die() */
	global $upfile_name,$path;
	if(is_file($dest)) unlink($dest);
	head($dat);
	echo $dat;
	echo "<br /><br /><hr size=1><br /><br />
		<center><font color=blue size=5>$mes<br /><br /><a href=".PHP_SELF2.">".S_RELOAD."</a></b></font></center>
		<br /><br /><hr size=1>";
	die("</body></html>\n");
}

function auto_link($proto){
	$proto = ereg_replace("(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)","<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>",$proto);
	return $proto;
}

function  proxy_connect($port) {
	$fp = @fsockopen ($_SERVER["REMOTE_ADDR"], $port,$a,$b,2);
	if(!$fp){return 0;}else{return 1;}
}

function regist($ip,$name,$capcode,$email,$sub,$com,$oekaki,$url,$pwd,$upfile,$upfile_name,$resto){
	global $path,$badstring,$badfile,$badip,$pwdc,$textonly,$admin,$FILTERS;

	if(isbanned($ip)) error(S_BANRENZOKU);
	
	// time
	$time = time();
	$tim = $time.substr(microtime(),2,3);

	// upload processing
	if($oekaki||$upfile&&file_exists($upfile)){
		$dest = $path.$tim.'.tmp';
		if($oekaki){
			$oekaki = str_replace('data:image/png;base64,', '', $oekaki);
			$oekaki = str_replace(' ', '+', $oekaki);
			$imgdata = base64_decode($oekaki);
			file_put_contents($dest, $imgdata);
			$upfile_name = 'oekaki.png';
		} else {
			move_uploaded_file($upfile, $dest);
		}
		//if an error in up, it changes to down (what?)
		//copy($upfile, $dest);
		$upfile_name = CleanStr($upfile_name);
		if(!is_file($dest)) error(S_UPFAIL,$dest);
		$size = getimagesize($dest);
		if(!is_array($size)) error(S_NOREC,$dest);
		$md5 = md5_of_file($dest);
		foreach($badfile as $value){
			if(ereg("^$value",$md5)){
				error(S_SAMEPIC,$dest); //Refuse this image
			}
		}
		chmod($dest,0666);
		$W = $size[0];
		$H = $size[1];
		$fsize = filesize($dest);
		if($fsize>MAX_KB * 1024) error(S_TOOBIG,$dest);
		switch ($size[2]) {
			case 1 : $ext=".gif";break;
			case 2 : $ext=".jpg";break;
			case 3 : $ext=".png";break;
			case 4 : $ext=".swf";break; // flash files are defintely images, thanks php
			case 13 : $ext=".swf";break;
			//case 5 : $ext=".psd";break;
			//case 6 : $ext=".bmp";break;
			default : error(S_NODETECT);break;
		}
		if ($ext==".swf" && !SWF_ENABLED) error(S_SWF_DISABLED);

		// Picture reduction
		if($W > MAX_W || $H > MAX_H){
			$W2 = MAX_W / $W;
			$H2 = MAX_H / $H;
			($W2 < $H2) ? $key = $W2 : $key = $H2;
			$W = ceil($W * $key);
			$H = ceil($H * $key);
		}
		$mes = S_UPGOOD;
	}

	if($_FILES["upfile"]["error"]==2){
		error(S_TOOBIG,$dest);
	}
	if(!isset($oekaki)&&$upfile_name&&$_FILES["upfile"]["size"]==0){
		if ($_FILES["upfile"]["error"] == 1) {
			error(S_TOOBIG."<br />$upfile_name",$dest);
		}
		error(S_TOOBIGORNONE."<br />$upfile_name",$dest);
	}

	//The last result number
	if(!$result=mysql_call("select max(no) from ".POSTTABLE)){echo S_SQLFAIL;}
	$row=mysql_fetch_array($result);
	$lastno=(int)$row[0];
	mysql_free_result($result);

	// Number of log lines
	$result=mysql_call("select * from ".POSTTABLE." where resto=0");
	if(!$resto){$threadcount = 1;}
	else{$threadcount = 0;}
	while($resrow=mysql_fetch_row($result)){
		$threadcount++;
	}
	mysql_free_result($result);

	/* Purge old threads */
	$result=mysql_call("select no,ext,tim from ".POSTTABLE." where resto=0 order by root asc");
	while($threadcount>THREADLIMIT){
		list($dno,$dext,$dtim)=mysql_fetch_row($result);
		if(!mysql_call("delete from ".POSTTABLE." where no=".$dno)){echo S_SQLFAIL;}
		if($dext){
			if(is_file($path.$dtim.$dext)) unlink($path.$dtim.$dext);
			if(is_file(THUMB_DIR.$dtim.'s.jpg')) unlink(THUMB_DIR.$dtim.'s.jpg');
		}
		$threadcount--;
	}
	mysql_free_result($result);

	$find = false;
	$resto=(int)$resto;
	if($resto){
		if(!$result = mysql_call("select * from ".POSTTABLE." where root>0 and no=$resto")){echo S_SQLFAIL;}
		else{
			$find = mysql_fetch_row($result);
			mysql_free_result($result);
	}
	if(!$find) error(S_NOTHREADERR,$dest);
	}

	foreach($badstring as $value){if(ereg($value,$com)||ereg($value,$sub)||ereg($value,$name)||ereg($value,$email)){
		error(S_STRREF,$dest);};}
	if($_SERVER["REQUEST_METHOD"] != "POST") error(S_UNJUST,$dest);
	// Form content check
	if(!$name||ereg("^[ |@|]*$",$name)) $name="";
	if(!$com||ereg("^[ |@|\t]*$",$com)) $com="";
	if(!$sub||ereg("^[ |@|]*$",$sub))   $sub="";

	if(!isset($oekaki)&&!$resto&&!is_file($dest)){
		if(FORCEIMAGE||!$textonly) error(S_NOPIC,$dest);
		//else $textonly = "on";
	}
	if(!$com&&!is_file($dest)) error(S_NOTEXT,$dest);

	$name=ereg_replace(S_MANAGEMENT,"\"".S_MANAGEMENT."\"",$name);
	$name=ereg_replace(S_DELETION,"\"".S_DELETION."\"",$name);

	if(strlen($com) > 1000) error(S_TOOLONG,$dest);
	if(strlen($name) > 100) error(S_TOOLONG,$dest);
	if(strlen($email) > 100) error(S_TOOLONG,$dest);
	if(strlen($sub) > 100) error(S_TOOLONG,$dest);
	if(strlen($resto) > 10) error(S_UNUSUAL,$dest);
	if(strlen($url) > 10) error(S_UNUSUAL,$dest);

	//host check
	$host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);

	foreach($badip as $value){ //Refusal hosts
		if(eregi("$value$",$host)){
			error(S_BADHOST,$dest);
	}}
		if(eregi("^mail",$host)
			|| eregi("^ns",$host)
			|| eregi("^dns",$host)
			|| eregi("^ftp",$host)
			|| eregi("^prox",$host)
			|| eregi("^pc",$host)
			|| eregi("^[^\.]\.[^\.]$",$host)){
			$pxck = "on";
	}
	if(eregi("ne\\.jp$",$host)||
		eregi("ad\\.jp$",$host)||
		eregi("bbtec\\.net$",$host)||
		eregi("aol\\.com$",$host)||
		eregi("uu\\.net$",$host)||
		eregi("asahi-net\\.or\\.jp$",$host)||
		eregi("rim\\.or\\.jp$",$host)
	){$pxck = "off";}
	else{$pxck = "on";}

	if($pxck=="on" && PROXY_CHECK){
		if(proxy_connect('80') == 1){
			error(S_PROXY80,$dest);
		} elseif(proxy_connect('8080') == 1){
			error(S_PROXY8080,$dest);
		}
	}

	// No, path, time, and url format
	srand((double)microtime()*1000000);
	if($pwd==""){
		if($pwdc==""){
			$pwd=rand();$pwd=substr($pwd,0,8);
		}else{
			$pwd=$pwdc;
		}
	}

	$c_pass = $pwd;
	$pass = ($pwd) ? substr(md5($pwd),2,8) : "*";
	$youbi = array(S_SUN, S_MON, S_TUE, S_WED, S_THU, S_FRI, S_SAT);
	$yd = $youbi[gmdate("w", $time+9*60*60)] ;
	$now = gmdate("y/m/d",$time+9*60*60)."(".(string)$yd.")".gmdate("H:i",$time+9*60*60);
	$posterid = substr(crypt(md5($_SERVER["REMOTE_ADDR"].'id'.gmdate("Ymd", $time+9*60*60)),'id'),-8);
	//Text plastic surgery (rorororor)
	$email= CleanStr($email);  $email=ereg_replace("[\r\n]","",$email);
	$sub  = CleanStr($sub);    $sub  =ereg_replace("[\r\n]","",$sub);
	$url  = CleanStr($url);    $url  =ereg_replace("[\r\n]","",$url);
	$resto= CleanStr($resto);  $resto=ereg_replace("[\r\n]","",$resto);
	$com  = CleanStr($com);
	// Standardize new character lines
	$com = str_replace( "\r\n",  "\n", $com);
	$com = str_replace( "\r",  "\n", $com);
	// Continuous lines
	$com = ereg_replace("\n((!@| )*\n){3,}","\n",$com);
	$com = nl2br($com);		//newlines get substituted by br tags
	$com = str_replace("\n",  "", $com);	//\n is erased (is this necessary?)

	foreach ($FILTERS as $filterin => $filterout){
		$com = str_replace($filterin, $filterout, $com);
	}

	$name=ereg_replace("[\r\n]","",$name);
	$names=$name;
	$name = trim($name);//blankspace removal
	if (get_magic_quotes_gpc()) {//magic quotes is deleted (?)
		$name = stripslashes($name);
	}
	$name = htmlspecialchars($name);//remove html special chars
	$name = str_replace("&amp;", "&", $name);//remove ampersands
	$name = str_replace(",", "&#44;", $name);//remove commas

	if(ereg("(#|!)(.*)",$names,$regs)){
		$cap = $regs[2];
		$cap=strtr($cap,"&amp;", "&");
		$cap=strtr($cap,"&#44;", ",");
		$name=ereg_replace("(#|!)(.*)","",$name);
		$salt=substr($cap."H.",1,2);
		$salt=ereg_replace("[^\.-z]",".",$salt);
		$salt=strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef");
		$name.=TRIPKEY.substr(crypt($cap,$salt),-10)."";
	}

	session_start();
	if(!$name||(FORCED_ANON&&!$_SESSION['name'])) $name=S_ANONAME; // manas can post with name when forced anon is on
	if(!$com) $com=S_ANOTEXT;
	if(!$sub) $sub=S_ANOTITLE;

	// Add capcode
	if($capcode){
		session_start();
		if(isset($_SESSION['capcode'])){
			if($_SESSION['cancap'])
				$name.=' '.$_SESSION['capcode'];
		}else{
			stopsession();
		}
	}

	// Read the log
	$query="select time from ".POSTTABLE." where com='".mysql_escape_string($com)."' ".
		"and host='".mysql_escape_string($host)."' ".
		"and no>".($lastno-20);  //the same
	if(!$result=mysql_call($query)){echo S_SQLFAIL;}
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	if($row&&!$upfile_name)error(S_RENZOKU3,$dest);

	$query="select time from ".POSTTABLE." where time>".($time - RENZOKU)." ".
		"and host='".mysql_escape_string($host)."' ";  //from precontribution
	if(!$result=mysql_call($query)){echo S_SQLFAIL;}
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	if($row&&!$upfile_name)error(S_RENZOKU3, $dest);

	// Upload processing
	if($dest&&file_exists($dest)){

		$query="select time from ".POSTTABLE." where time>".($time - RENZOKU2)." ".
			"and host='".mysql_escape_string($host)."' ";  //from precontribution
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	if($row&&$upfile_name)error(S_RENZOKU2,$dest);

	//Duplicate image check
	$result = mysql_call("select tim,ext,md5 from ".POSTTABLE." where md5='".$md5."'");
	if($result){
		list($timp,$extp,$md5p) = mysql_fetch_row($result);
		mysql_free_result($result);
		#      if($timp&&file_exists($path.$timp.$extp)){ #}
		if($timp){
			error(S_DUPE,$dest);
		}
	}
	}

	$restoqu=(int)$resto;
	if($resto){ //res,root processing
		$rootqu="0";
		if(!$resline=mysql_call("select * from ".POSTTABLE." where resto=".$resto)){echo S_SQLFAIL;}
		$countres=mysql_num_rows($resline);
	mysql_free_result($resline);
	if(!stristr($email,'sage') && $countres < BUMPLIMIT){
		$query="update ".POSTTABLE." set root=now() where no=$resto"; //age
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
	}
	}else{$rootqu="now()";} //now it is root

	$query="insert into ".POSTTABLE." (now,name,email,sub,com,host,pwd,ext,w,h,tim,time,md5,fsize,root,resto,ip,id) values (".
		"'".$now."',".
		"'".mysql_escape_string($name)."',".
		"'".mysql_escape_string($email)."',".
		"'".mysql_escape_string($sub)."',".
		"'".mysql_escape_string($com)."',".
		"'".mysql_escape_string($host)."',".
		"'".mysql_escape_string($pass)."',".
		"'".$ext."',".
		(int)$W.",".
		(int)$H.",".
		"'".$tim."',".
		(int)$time.",".
		"'".$md5."',".
		(int)$fsize.",".
		$rootqu.",".
		(int)$resto.",
		\"".$_SERVER['REMOTE_ADDR']."\",
		'$posterid')";
	if(!$result=mysql_call($query)){echo S_SQLFAIL;}  //post registration

	//Cookies
	setcookie ("pwdc", $c_pass,time()+7*24*3600);  /* 1 week cookie expiration */
	if(function_exists("mb_internal_encoding")&&function_exists("mb_convert_encoding")
		&&function_exists("mb_substr")){
		if(ereg("MSIE|Opera",$_SERVER["HTTP_USER_AGENT"])){
			$i=0;$c_name='';
			mb_internal_encoding("SJIS");
			while($j=mb_substr($names,$i,1)){
				$j = mb_convert_encoding($j, "UTF-16", "SJIS");
				$c_name.="%u".bin2hex($j);
				$i++;
		}
		header("Set-Cookie: namec=$c_name; expires=".gmdate("D, d-M-Y H:i:s",time()+7*24*3600)." GMT",false);
	}else{
		$c_name=$names;
		setcookie ("namec", $c_name,time()+7*24*3600);  /* 1 week cookie expiration */
	}
	}

	if($dest&&file_exists($dest)){
		rename($dest,$path.$tim.$ext);
		if(USE_THUMB){thumb($path,$tim,$ext);}
	}
	updatelog();

	
	if(stristr($email,'nonoko') || !$resto){
		echo "<html><head><meta http-equiv=\"refresh\" content=\"1;URL=".PHP_SELF2."\" /></head>";
	}else{
		echo "<html><head><meta http-equiv=\"refresh\" content=\"1;URL=".PHP_SELF."?res=$resto\" /></head>";
	}
	echo "<body>$mes ".S_SCRCHANGE."</body></html>\n";
}

//thumbnails
function thumb($path,$tim,$ext){
	global $THUMBBACK;
	if(!function_exists("ImageCreate")||!function_exists("ImageCreateFromJPEG"))return;
	$fname=$path.$tim.$ext;
	$thumb_dir = THUMB_DIR;     //thumbnail directory
	$width     = MAX_W;            //output width
	$height    = MAX_H;            //output height
	// width, height, and type are aquired
	$size = GetImageSize($fname);
	switch ($size[2]) {
	case 1:
		if(function_exists("ImageCreateFromGIF")){
			$im_in = @ImageCreateFromGIF($fname);
			if($im_in){break;}
		}
		if(!file_exists($path.$tim.'.png'))return;
		$im_in = @ImageCreateFromPNG($path.$tim.'.png');
		unlink($path.$tim.'.png');
		if(!$im_in)return;
		break;
	case 2: $im_in = @ImageCreateFromJPEG($fname);
		if(!$im_in){return;} break;
	case 3:
		if(!function_exists("ImageCreateFromPNG"))return;
		$im_in = @ImageCreateFromPNG($fname);
		if(!$im_in){return;}
		break;
	default : return;
	}
	// Resizing
	if ($size[0] > $width || $size[1] >$height) {
		$key_w = $width / $size[0];
		$key_h = $height / $size[1];
		($key_w < $key_h) ? $keys = $key_w : $keys = $key_h;
		$out_w = ceil($size[0] * $keys) +1;
		$out_h = ceil($size[1] * $keys) +1;
	} else {
		$out_w = $size[0];
		$out_h = $size[1];
	}
	// the thumbnail is created
	if(function_exists("ImageCreateTrueColor")&&get_gd_ver()=="2"){
		$im_out = ImageCreateTrueColor($out_w, $out_h);
	}else{$im_out = ImageCreate($out_w, $out_h);}
	// change background color
	$backing = imagecolorallocate($im_out,...$THUMBBACK);
	imagefill($im_out, 0, 0, $backing);
	// copy resized original
	ImageCopyResized($im_out, $im_in, 0, 0, 0, 0, $out_w, $out_h, $size[0], $size[1]);
	// thumbnail saved
	ImageJPEG($im_out, $thumb_dir.$tim.'s.jpg',60);
	chmod($thumb_dir.$tim.'s.jpg',0666);
	// created image is destroyed
	ImageDestroy($im_in);
	ImageDestroy($im_out);
}

//check version of gd
function get_gd_ver(){
	if(function_exists("gd_info")){
		$gdver=gd_info();
		$phpinfo=$gdver["GD Version"];
	}else{ //earlier than php4.3.0
		ob_start();
		phpinfo(8);
		$phpinfo=ob_get_contents();
		ob_end_clean();
		$phpinfo=strip_tags($phpinfo);
		$phpinfo=stristr($phpinfo,"gd version");
		$phpinfo=stristr($phpinfo,"version");
	}
	$end=strpos($phpinfo,".");
	$phpinfo=substr($phpinfo,0,$end);
	$length = strlen($phpinfo)-1;
	$phpinfo=substr($phpinfo,$length);
	return $phpinfo;
}

//md5 calculation for earlier than php4.2.0
function md5_of_file($inFile) {
	if (file_exists($inFile)){
		if(function_exists('md5_file')){
			return md5_file($inFile);
	}else{
		$fd = fopen($inFile, 'r');
		$fileContents = fread($fd, filesize($inFile));
		fclose ($fd);
		return md5($fileContents);
	}
 }else{
	 return false;
}}

/* text plastic surgery */
function CleanStr($str){
	global $admin;
	session_start();
	$str = trim($str);//blankspace removal
	if (get_magic_quotes_gpc()) {//magic quotes is deleted (?)
		$str = stripslashes($str);
	}
	if(!(isset($_SESSION['cancap']) && ((int)$_SESSION['cancap'])!=0)){
		$str = htmlspecialchars($str);//remove html special chars
		$str = str_replace("&amp;", "&", $str);//remove ampersands
	}
	return str_replace(",", "&#44;", $str);//remove commas
}

//check for table existance
function table_exist($table){
	$result = mysql_call("show tables like '$table'");
	if(!$result){return 0;}
	$a = mysql_fetch_row($result);
	mysql_free_result($result);
	return $a;
}

/* user image deletion */
function usrdel($no,$pwd){
	global $path,$pwdc,$onlyimgdel;
	$host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
	$delno = array();
	$delflag = FALSE;
	reset($_POST);
	while ($item = each($_POST)){
		if($item[1]=='delete'){array_push($delno,$item[0]);$delflag=TRUE;}
	}
	if($pwd==""&&$pwdc!="") $pwd=$pwdc;
	$countdel=count($delno);

	$flag = FALSE;
	for($i = 0; $i<$countdel; $i++){
		if(!$result=mysql_call("select no,ext,tim,pwd,host from ".POSTTABLE." where no=".$delno[$i])){echo S_SQLFAIL;}
		else{
			while($resrow=mysql_fetch_row($result)){
				list($dno,$dext,$dtim,$dpass,$dhost)=$resrow;
				if(substr(md5($pwd),2,8) == $dpass || substr(md5($pwdc),2,8) == $dpass || $dhost == $host){
					$flag = TRUE;
					$delfile = $path.$dtim.$dext;	//path to delete
					if(!$onlyimgdel){
						if(!mysql_call("delete from ".POSTTABLE." where no=".$dno)){echo S_SQLFAIL;} //sql is broke
					}
					if(is_file($delfile)) unlink($delfile);//Deletion
					if(is_file(THUMB_DIR.$dtim.'s.jpg')) unlink(THUMB_DIR.$dtim.'s.jpg');//Deletion
				}
			}
			mysql_free_result($result);
		}
	}
	if(!$flag) error(S_BADDELPASS);
}

function stopsession(){
	session_unset();
	session_destroy(); 
}

function adminhead(){
	global $admin;
	head($dat);
	echo $dat;
	echo("<div class='passvalid'>".S_MANAMODE." <a href=\"".PHP_SELF2."\">[".S_RETURNS."]</a></div>\n");
	echo("<div class='manabuttons'>[<a href='".PHP_SELF."'>".S_LOGUPD."</a>] ");
	echo("[<a class='admd$admin' href='".PHP_SELF."?mode=admin&admin=del'>".S_MANAREPDEL."</a>] ");
	echo("[<a class='admb$admin' href='".PHP_SELF."?mode=admin&admin=ban'>".S_MANABAN."</a>] ");
	echo("[<a class='admp$admin' href='".PHP_SELF."?mode=admin&admin=post'>".S_MANAPOST."</a>] ");
	echo("[<a class='adma$admin' href='".PHP_SELF."?mode=admin&admin=acc'>".S_MANAACCS."</a>] ");
	echo("[<a href='".PHP_SELF."?mode=admin&admin=logout'>".S_LOGOUT."</a>]</div>");
}

/*password validation */
function valid($pass){
	session_start();
	if(isset($_SESSION['capcode'])) return;
	head($dat);
	echo $dat;
	echo "<div class='passvalid'>".S_MANAMODE." <a href='".PHP_SELF2."'>[".S_RETURNS."]</a> \n</div>";
	if($pass){
		$result = mysql_call("select name,password,capcode,candel,canban,cancap,canacc from ".MANATABLE);
		while($row=mysql_fetch_row($result)){
			list($adminname,$password,$capcode,$candel,$canban,$cancap,$canacc)=$row;
			if($pass == $password){
				$_SESSION["name"] = $adminname;
				$_SESSION["capcode"] = $capcode;
				$_SESSION["candel"] = $candel;
				$_SESSION["canban"] = $canban;
				$_SESSION["cancap"] = $cancap;
				$_SESSION["canacc"] = $canacc;
				echo("<div class='passvalid'>".S_MANALOGGEDIN."</div>");
				echo("<meta http-equiv=\"refresh\" content=\"2;URL=".PHP_SELF."?mode=admin\" />");
				die(fakefoot());
			}
		}
		die(S_WRONGPASS);
		mysql_free_result($result);
	}

	// Get rid of unnecessary session
	stopsession();

	// Mana login form
	if(!$pass){
		echo "<br /><div class='centered'><form action=\"".PHP_SELF."\" method=\"post\">\n";
		echo "<input type=hidden name=mode value=admin />\n";
		echo "<input type=password name=pass size=8>";
		echo "<input type=submit value=\"".S_MANASUB."\"></form></div>\n";
		die(fakefoot());
	}
}

function adminacc($accname,$accpassword,$acccapcode,$accdel,$accban,$acccap,$accacc){
	session_start();
	if(!$_SESSION['canacc']) die(S_NOPERMISSION);
	if(!$accname){
		echo('<div class="centered">'."\n");
		echo "<p><form style='display: inline-block;' action=\"".PHP_SELF."\" method=\"post\">\n";
		echo "<input type=hidden name=mode value=admin />\n";
		echo "<input type=hidden name=admin value=acc />\n";
		echo "\n<table><tbody>";
		echo('<tr><td class="postblock">'.S_NAME.'</td><td><input type="text" size="28" name="accname" />');
		echo(" <input type=submit value=\"".S_MANASUB."\" /></td></tr>");
		echo('<tr><td class="postblock">'.S_DELPASS.'</td><td><input type="password" size="28" name="accpassword" /></td></tr>');
		echo('<tr><td class="postblock">'.S_CAPCODE.'</td><td><input type="text" size="28" name="acccapcode" value="## Moderator" /></td></tr>');
		echo('<tr><td class="postblock">'.S_ACCDEL.'</td><td><input type="checkbox" name="accdel" value=1 /></td></tr>');
		echo('<tr><td class="postblock">'.S_ACCBAN.'</td><td><input type="checkbox" name="accban" value=1 /></td></tr>');
		echo('<tr><td class="postblock">'.S_ACCCAP.'</td><td><input type="checkbox" name="acccap" value=1 /></td></tr>');
		echo('<tr><td class="postblock">'.S_ACCACC.'</td><td><input type="checkbox" name="accacc" value=1 /></td></tr>');
		echo("</tbody></table></form></div>\n");
		die(fakefoot());
	}
	if(!$accdel) $accdel=0;
	if(!$accban) $accban=0;
	if(!$acccap) $acccap=0;
	if(!$accacc) $accacc=0;
	$query="insert into ".MANATABLE." (name,password,capcode,candel,canban,cancap,canacc) values (
		'$accname',
		'$accpassword',
		'$acccapcode',
		$accdel,
		$accban,
		$acccap,
		$accacc)";
	if(!$result=mysql_call($query)){echo S_SQLFAIL;}
	mysql_free_result($result);
	$query="delete from ".MANATABLE." where name='DUMMY' and password='REPLACEME'";
	if(!$result=mysql_call($query)){echo S_SQLFAIL;}
	mysql_free_result($result);
	die("<p>".S_ACCCREATED."</p></body></html>\n");
}

function adminban(){
	global $banip,$banexp,$banpubmsg,$banprivmsg,$rmp,$rmallp,$unban;
	session_start();
	if(!$_SESSION['canban']) die(S_NOPERMISSION);
	if($banip!=''){
		if($banexp=='') error(S_BANEXPERROR);
		if(strpos($banip, '.')){
			$banmode = 1;
		}else{
			$banexp=(int)$banexp;
			$banmode = 0;
		}
		insertban($banip,$banexp,$banpubmsg,$banprivmsg,$banmode,$rmp,$rmallp,$unban); // 0 is IP mode, 1 is post no. mode
		if ($unban) { die(S_UNBANSUCCESS); }
		else { die(S_BANSUCCESS); }
	}
	echo('<div class="centered">'."\n");
	echo "<p><form style='display: inline-block;' action=\"".PHP_SELF."\" method=\"post\">\n";
	echo "<input type=hidden name=mode value=admin />\n";
	echo "<input type=hidden name=admin value=ban />\n";
	echo "\n<table><tbody>";
	echo('<tr><td class="postblock">'.S_MANABANIP.'</td><td><input type="text" size="28" name="banip" />');
	echo(" <input type=submit value=\"".S_MANASUB."\" /></td></tr>");
	echo('<tr><td class="postblock">'.S_MANABANEXP.'</td><td><input value="7" type="number" size="5" name="banexp" /></td></tr>');
	echo('<tr><td class="postblock">'.S_MANABANPUBMSG.'</td><td><textarea rows="3" cols="33" name="banpubmsg">'.S_BANNEDMSG.'</textarea></td></tr>');
	echo('<tr><td class="postblock">'.S_MANABANPRIVMSG.'</td><td><textarea rows="3" cols="33" name="banprivmsg"></textarea></td></tr>');
	echo('<tr><td class="postblock">'.S_MANARMP.'</td><td><input value="7" type="checkbox" name="rmp" value="on" /></td></tr>');
	echo('<tr><td class="postblock">'.S_MANARMALLP.'</td><td><input value="7" type="checkbox" name="rmallp" value="on" /></td></tr>');
	echo('<tr><td class="postblock">'.S_MANAUNBAN.'</td><td><input value="7" type="checkbox" name="unban" value="on" /></td></tr>');
	echo("</tbody></table></form></div>\n");
	die(fakefoot());
}

/* Admin deletion */
function admindel(){
	global $path,$onlyimgdel;
	session_start();
	if(!$_SESSION['candel']) die(S_NOPERMISSION);
	$delno = array(dummy);
	$delflag = FALSE;
	reset($_POST);
	while ($item = each($_POST)){
		if($item[1]=='delete'){array_push($delno,$item[0]);$delflag=TRUE;}
	}
	if($delflag){
		if(!$result=mysql_call("select * from ".POSTTABLE."")){echo S_SQLFAIL;}
	$find = FALSE;
	while($row=mysql_fetch_row($result)){
		list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fsize,$root,$resto,$ip)=$row;
		if($onlyimgdel==on){
			if(array_search($no,$delno)){//only a picture is deleted
				$delfile = $path.$tim.$ext;	//only a picture is deleted
				if(is_file($delfile)) unlink($delfile);//delete
				if(is_file(THUMB_DIR.$tim.'s.jpg')) unlink(THUMB_DIR.$tim.'s.jpg');//delete
	}
		}else{
			if(array_search($no,$delno)){//It is empty when deleting
				$find = TRUE;
				if(!mysql_call("delete from ".POSTTABLE." where no=".$no)){echo S_SQLFAIL;}
				$delfile = $path.$tim.$ext;	//Delete file
		if(is_file($delfile)) unlink($delfile);//Delete
		if(is_file(THUMB_DIR.$tim.'s.jpg')) unlink(THUMB_DIR.$tim.'s.jpg');//Delete
	}
		}
	}
	mysql_free_result($result);
	}
	// Deletion screen display
	echo "<p><form action=\"".PHP_SELF."\" method=\"post\">\n";
	echo "<input type=hidden name=mode value=admin>\n";
	echo "<input type=hidden name=admin value=del>\n";
	echo "<div class=\"delbuttons\"><input type=submit value=\"".S_ITDELETES."\">";
	echo "<input type=reset value=\"".S_MDRESET."\"> ";
	echo "[<input type=checkbox name=onlyimgdel value=on><!--checked-->".S_MDONLYPIC." ]</div>";
	echo "<table class=\"postlists\">\n";
	echo "<tr class=\"managehead\">".S_MDTABLE1;
	echo S_MDTABLE2;
	echo "</tr>\n";

	if(!$result=mysql_call("select * from ".POSTTABLE." order by no desc")){echo S_SQLFAIL;}
	$j=0;
	while($row=mysql_fetch_row($result)){
		$j++;
		$img_flag = FALSE;
		list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fsize,$root,$resto,$ip,$id)=$row;
		// Format
		$now=ereg_replace('.{2}/(.*)$','\1',$now);
		$now=ereg_replace('\(.*\)',' ',$now);
		if(strlen($name) > 10) $name = substr($name,0,9)."...";
		$name = htmlspecialchars($name);
		if(strlen($sub) > 10) $sub = substr($sub,0,9)."...";
		if($email) $name="<a href=\"mailto:$email\">$name</a>";
		$com = str_replace("<br />"," ",$com);
		$com = htmlspecialchars($com);
		if(strlen($com) > 20) $com = substr($com,0,18) . "...";
		// Link to the picture
		if($ext && is_file($path.$tim.$ext)){
			$img_flag = TRUE;
			$clip = "<a href=\"".IMG_DIR.$tim.$ext."\" target=\"_blank\">".$tim.$ext."</a><br />";
			$size = $fsize;
			$all += $size;
			$md5= substr($md5,0,10);
	}else{
		$clip = "";
		$size = 0;
		$md5= "";
	}
	$class = ($j % 2) ? "row1" : "row2";//BG color

	echo "<tr class=$class><td><input type=checkbox name=\"$no\" value=delete></td>";
	echo "<td>$no</td><td>$now</td><td>$sub</td>";
	echo "<td>$name</td><td>$ip</td><td>$com</td>";
	echo "<td>$host</td><td>$clip($size)</td><td>$md5</td><td>$resto</td><td>$tim</td><td>$time</td>\n";
	echo "</tr>\n";
	}
	mysql_free_result($result);

	echo "</table><input type=submit value=\"".S_ITDELETES."$msg\">";
	echo "<input type=reset value=\"".S_RESET."\"></form>";

	$all = (int)($all / 1024);
	echo "[ ".S_IMGSPACEUSAGE."<b>$all</b> KB ]";
	die(fakefoot());
}

function insertban($target,$days,$pubmsg,$privmsg,$bantype,$rmp,$rmallp,$unban){
	$time = time();
	$daylength = 60*60*24;
	$expires = $time + ($daylength * $days);
	if($bantype==0){
		$result = mysql_call("select * from ".POSTTABLE);
		while($row=mysql_fetch_row($result)){
			list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fsize,$root,$resto,$ip)=$row;
			if($target==(int)$no){
				$banip=$ip;
				break;
			}
		}
		if(!isset($banip)){die(S_NOSUCHPOST);}
	}else{
		$banip=$target;
	}
	mysql_free_result($result);

	if($pubmsg && !$unban){
		$pubmsg = strtoupper($pubmsg);
		$pubmsg = "<br /><br /><span style=\"color: red; font-weight: bold;\">($pubmsg)</span>";
		$query="update ".POSTTABLE."
			set com=concat(com,'$pubmsg')
			where no='$no'";
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
		mysql_free_result($result);
	}

	if(!$unban){
		$query="insert into ".BANTABLE." (ip,start,expires,reason) values (
			'$banip',
			'$time',
			'$expires',
			'$privmsg')";
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
		mysql_free_result($result);
	}else{
		$query="delete from ".BANTABLE." where `ip`='$banip'";
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
		mysql_free_result($result);
	}

	if($rmp && $bantype==0){
		$query="delete from ".POSTTABLE." where `no`='$target'";
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
		mysql_free_result($result);
	}
	if($rmallp){
		$query="delete from ".POSTTABLE." where `ip`='$banip'";
		if(!$result=mysql_call($query)){echo S_SQLFAIL;}
		mysql_free_result($result);
	}
}

function isbanned($ip){ // check ban, returning true or false
	$result = mysql_call("select * from ".BANTABLE);
	$banned=false;
	while($row=mysql_fetch_row($result)){
		list($bip,$time,$expires,$reason)=$row;
		if($ip==$bip){
			if((int)$expires<time()){ removeban($ip);}
			else{return true;}
		}
	}
	mysql_free_result($result);
	return false;
}

function checkban($ip) {
	$result = mysql_call("select * from ".BANTABLE);
	$banned=false;
	while($row=mysql_fetch_row($result)){
		list($bip,$time,$expires,$reason)=$row;
		if($ip==$bip){
			if((int)$expires<time()){
				removeban($ip);
				error(S_BANEXPIRED);
			}else{ error(S_BANNEDMESSAGE."<br />\n".S_BANTIME.humantime($time)."<br />\n".S_BANEXPIRE.humantime($expires)); }
		}
	}
	if(!$banned) error(S_NOTBANNED . $ip);
	mysql_free_result($result);
}

function removeban($ip) {
	$result = mysql_call("select * from ".BANTABLE);
	while($row=mysql_fetch_row($result)){
		list($bip,$time,$expires,$reason)=$row;
		if($ip==$bip){
			$result = mysql_call("delete from ".BANTABLE." where `ip` = '".$ip."'");
			break;
		}
	}
	mysql_free_result($result);
}

function catalog(){
	$dat = '';
	head($dat);
	$dat.="<div class=\"passvalid\">".S_CATALOG." <a href=\"".PHP_SELF2."\">[".S_RETURNS."]</a></div><br />\n";
	$dat.="<div class='cattable'>";
	$i=0;
	$result = mysql_call("select * from ".POSTTABLE." order by root desc");
	while($row=mysql_fetch_row($result)){
		list($no,$now,$name,$email,$sub,$com,$host,$pwd,$ext,$w,$h,$tim,$time,$md5,$fsize,$root,$resto,$ip)=$row;
		if((int)$resto==0){
			$dat.="<div class='catthread'>";
			if ($ext && $ext == ".swf") {
				$imgsrc = "<img class='catthumb' src=\"file.png\" width=\"200\" height=\"200\" alt=\"".$size." B\" /><br />";
				$dat.="<a href='".PHP_SELF."?res=$no'>$imgsrc</a>";
			} else if($ext){
				$size = $fsize;//file size displayed in alt text
				if($w && $h){//when there is size...
					if(@is_file(THUMB_DIR.$tim.'s.jpg')){
						$imgsrc = "<img class='catthumb' src=\"".THUMB_DIR.$tim.'s.jpg'."\" width=\"$w\" height=\"$h\" alt=\"".$size." B\" /><br />";
					}else{
						$imgsrc = "<img class='catthumb' src=\"$src\" width=\"$w\" height=\"$h\" alt=\"".$size." B\" /><br />";
					}
				}else{
					$imgsrc = "<img class='catthumb' src=\"$src\" alt=\"".$size." B\" /><br />";
				}
				$dat.="<a href='".PHP_SELF."?res=$no'>$imgsrc</a>";
			}
			if(strlen($com)>55) $com = substr($com,0,54)."...";
			$dat.="<a class='cata' href='".PHP_SELF."?res=$no'><span class='cattitle filetitle'>$sub</span><br /><span class='catcont'>$com</span></a></div>";
			$i++;
		}
	}
	mysql_free_result($result);
	$dat.="</div>";
	foot($dat);
	echo($dat);
}

/*-----------Main-------------*/
$ip = $_SERVER['REMOTE_ADDR'];
switch($mode){
case 'regist':
	regist($ip,$name,$capcode,$email,$sub,$com,$oekaki,'',$pwd,$upfile,$upfile_name,$resto);
	break;
case 'admin':
	valid($pass);
	session_start();
	if(!$admin) $admin='del';
	adminhead();
	if($admin=="del") admindel();
	if($admin=="ban") adminban();
	if($admin=="post"){
		if(!$_SESSION['cancap'])die(S_NOPERMISSION);
		form($post,$res,1);
		echo $post;
		die(fakefoot());
	}
	if($admin=="logout"){
		stopsession();
		echo("<meta http-equiv=\"refresh\" content=\"0;URL=".PHP_SELF2."\" />");
	}
	if($admin=="rban") removeban($ip);
	if($admin=="acc") adminacc($accname,$accpassword,$acccapcode,$accdel,$accban,$acccap,$accacc);
	break;
case 'banned':
	checkban($ip);
	break;
case 'catalog':
	catalog();
	break;
case 'usrdel':
	usrdel($no,$pwd);
default:
	if($res){
		updatelog($res);
	}else{
		updatelog();
		if(!ECHOALL)echo "<meta http-equiv=\"refresh\" content=\"0;URL=".PHP_SELF2."\" />";
	}
}
