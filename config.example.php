<?php

define(LOCKDOWN, false);			//Set to true to disable page viewing for users (not for managers)

// General settings
define(TITLE, 'Fikaba-powered image board');	//Name of this image board
define(SHOWTITLETXT, 1);			//Show TITLE at top (1: yes  0: no)
define(SHOWTITLEIMG, 0);			//Show image at top (0: no, 1: single, 2: rotating)
define(TITLEIMG, 'title.jpg');			//Title image (point to php file if rotating)
define(LANGUAGE, 'en');				//Language. Fikaba provides en and jp by default
define(FOOTTEXT, '');				//Extra footer text, for disclaimers and such. Can be left empty

// Database settings
define(POSTTABLE, 'CHANGEME');			//Post table (NOT DATABASE) used by image board
define(BANTABLE, 'CHANGEME');			//Bans table (NOT DATABASE) used by fikaba
define(MANATABLE, 'CHANGEME');			//Manager (admin, mod, janitor) table
define(SQLHOST, 'localhost');			//MySQL server address, usually localhost
define(SQLUSER, 'CHANGEME');			//MySQL user (must be changed)
define(SQLPASS, 'CHANGEME');			//MySQL user's password (must be changed)
define(SQLDB, 'CHANGEME');			//Database used by image board

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
define(ECHOALL, false);				//Do not write index to file, but echo directly (4chan-style)
define(PHP_SELF, 'imgboard.php');		//Name of main script file (if ECHOALL, you probably want 'index.php')
define(PHP_SELF2, 'index.html');		//Name of main html file (point to PHP_SELF if ECHOALL)
define(PHP_EXT, '.html');			//Extension used for board pages after first
define(USE_THUMB, true);			//Use thumbnails
define(FORCEIMAGE, false);			//Whether or not threads must start with an image
define(OEKAKI_ENABLED, false);			//Whether or not oekaki is enabled, duh
$THUMBBACK = array(255,255,238);		//Thumbnail background for transp. images. Usually the background of your body element.

// Spam/flood protection
define(RENZOKU, 5);				//Seconds between posts (floodcheck)
define(RENZOKU2, 10);				//Seconds between image posts (floodcheck)
define(PROXY_CHECK, false);			//Enable proxy check
define(DISP_ID, false);				//Display user IDs
define(BR_CHECK, 15);				//Max lines per post (0 = no limit)
define(FORCED_ANON, false);			//Force anonymous posting (except for managers)

// Look and behavior
define(COLLAPSENUM, 3);				//Number of replies to show in the index
define(TRIPKEY, '#');				//this character is displayed before tripcodes

// CSS
define(CSSDEFAULT, 'Futaba');			// The name of the stylesheet to be used by default
$STYLES = array(				// Array containing NAME => FILE of stylesheets
	"Futaba"	=>	'css/futaba.css',
	"Burichan"	=>	'css/burichan.css',
	"heavy_ice"	=>	'css/heavy_ice.css'
);
