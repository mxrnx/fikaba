<?php

// General settings
define(TITLE, 'Fikaba-powered image board');	//Name of this image board
define(SHOWTITLETXT, 1);			//Show TITLE at top (1: yes  0: no)
define(SHOWTITLEIMG, 0);			//Show image at top (0: no, 1: single, 2: rotating)
define(TITLEIMG, 'title.jpg');			//Title image (point to php file if rotating)
define(LANGUAGE, 'en');				//Language. Fikaba provides en and jp by default

// Database settings
define(POSTTABLE, 'CHANGEME');			//Post table (NOT DATABASE) used by image board
define(BANTABLE, 'CHANGEME');			//Bans table (NOT DATABASE) used by fikaba
define(SQLHOST, 'localhost');			//MySQL server address, usually localhost
define(SQLUSER, 'CHANGEME');			//MySQL user (must be changed)
define(SQLPASS, 'CHANGEME');			//MySQL user's password (must be changed)
define(SQLDB, 'CHANGEME');			//Database used by image board

define(ADMIN_PASS, 'CHANGEME');			//Admin password  (CHANGE THIS YO)

// File-related settings
define(IMG_DIR, 'src/');			//Image directory (needs to be 777)
define(THUMB_DIR,'thumb/');			//Thumbnail directory (needs to be 777)
define(HOME,  '../');				//Site home directory (up one level by default
define(MAX_KB, 2000);				//Maximum upload size in KB
define(MAX_W,  250);				//Images exceeding this width will be thumbnailed
define(MAX_H,  250);				//Images exceeding this height will be thumbnailed
define(PAGE_DEF, 5);				//Threads per page
define(THREADLIMIT,  40);			//Maxium number of entries
define(BUMPLIMIT, 50);				//Maximum topic bumps
define(PHP_SELF, 'imgboard.php');		//Name of main script file
define(PHP_SELF2, 'index.html');		//Name of main htm file
define(PHP_EXT, '.html');			//Extension used for board pages after first
define(USE_THUMB, true);			//Use thumbnails

// Spam/flood protection
define(RENZOKU, 5);				//Seconds between posts (floodcheck)
define(RENZOKU2, 10);				//Seconds between image posts (floodcheck)
define(PROXY_CHECK, false);			//Enable proxy check
define(DISP_ID, false);				//Display user IDs
define(BR_CHECK, 15);				//Max lines per post (0 = no limit)

// Look and behavior
define(COLLAPSENUM, 3);				//Number of replies to show in the index
define(TRIPKEY, '#');				//this character is displayed before tripcodes
define(CSSDEFAULT, 'Futaba');			// The name of the stylesheet to be used by default

// CSS
define(CSSHTML, '<link rel="alternate stylesheet" type="text/css" href="css/futaba.css" title="Futaba" />
<link rel="alternate stylesheet" type="text/css" href="css/burichan.css" title="Burichan" />
<link rel="alternate stylesheet" type="text/css" href="css/heavy_ice.css" title="heavy_ice" />');
define(STYLESHTML, '[<a href="#" onClick="changeStyle(\'Futaba\')">Futaba</a>]
[<a href="#" onClick="changeStyle(\'Burichan\')">Burichan</a>]
[<a href="#" onClick="changeStyle(\'heavy_ice\')">heavy_ice</a>]');
