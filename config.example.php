<?php
define(TITLE, 'Fikaba-powered image board');		//Name of this image board
define(SQLLOG, 'CHANGEME');		//Table (NOT DATABASE) used by image board
define(SQLHOST, 'localhost');		//MySQL server address, usually localhost
define(SQLUSER, 'CHANGEME');		//MySQL user (must be changed)
define(SQLPASS, 'CHANGEME');		//MySQL user's password (must be changed)
define(SQLDB, 'CHANGEME');		//Database used by image board
define(ADMIN_PASS, 'CHANGEME');	//Janitor password  (CHANGE THIS YO)
define(SHOWTITLETXT, '1');		//Show TITLE at top (1: yes  0: no)
define(SHOWTITLEIMG, '0');		//Show image at top (0: no, 1: single, 2: rotating)
define(TITLEIMG, 'title.jpg');	//Title image (point to php file if rotating)
define(IMG_DIR, 'src/');		//Image directory (needs to be 777)
define(THUMB_DIR,'thumb/');		//Thumbnail directory (needs to be 777)
define(HOME,  '../');			//Site home directory (up one level by default
define(MAX_KB, '2000');			//Maximum upload size in KB
define(MAX_W,  '250');			//Images exceeding this width will be thumbnailed
define(MAX_H,  '250');			//Images exceeding this height will be thumbnailed
define(PAGE_DEF, '5');			//Images per page
define(LOG_MAX,  '500');		//Maxium number of entries
define(PHP_SELF, 'imgboard.php');	//Name of main script file
define(PHP_SELF2, 'index.html');	//Name of main htm file
define(PHP_EXT, '.html');		//Extension used for board pages after first
define(RENZOKU, '5');			//Seconds between posts (floodcheck)
define(RENZOKU2, '10');		//Seconds between image posts (floodcheck)
define(MAX_RES, '30');		//Maximum topic bumps
define(USE_THUMB, 1);		//Use thumbnails (1: yes  0: no)
define(PROXY_CHECK, 0);		//Enable proxy check (1: yes  0: no)
define(DISP_ID, 0);		//Display user IDs (1: yes  0: no)
define(BR_CHECK, 15);		//Max lines per post (0 = no limit)
define(TRIPKEY, '#');		//this character is displayed before tripcodes
//define(CSSFILE, 'futaba.css');	//location of the css file (DEPRECATED as of Fikaba 000002)
define(CSSDEFAULT, 'Futaba');		// The name of the stylesheet to be used by default
define(CSSHTML, '<link rel="alternate stylesheet" type="text/css" href="css/futaba.css" title="Futaba" />
<link rel="alternate stylesheet" type="text/css" href="css/burichan.css" title="Burichan" />');
define(STYLESHTML, '[<a href="#" onClick="changeStyle(\'Futaba\')">Futaba</a>]
[<a href="#" onClick="changeStyle(\'Burichan\')">Burichan</a>]');
?>
