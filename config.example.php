<?php

const LOCKDOWN = false;			//Set to true to disable page viewing for users (not for managers)

// General settings
const TITLE = 'Fikaba-powered image board';	//Name of this image board
const SHOWTITLETXT = 1;				//Show TITLE at top (1: yes  0: no)
const SHOWTITLEIMG = 0;				//Show image at top (0: no, 1: single, 2: rotating)
const BANNERS = array('title1.png', 'title2.png');	// Banners for SHOWTITLEIMG=2
const TITLEIMG = 'title.png';			//Title image
const LANGUAGE = 'en';				//Language. Fikaba provides en and jp by default
const FOOTTEXT = '';				//Extra footer text, for disclaimers and such. Can be left empty

// Database settings
const POSTTABLE = 'CHANGEME';			//Post table (NOT DATABASE) used by image board
const BANTABLE = 'CHANGEME';			//Bans table (NOT DATABASE) used by fikaba
const MANATABLE = 'CHANGEME';			//Manager (admin, mod, janitor) table
const SQLHOST = 'localhost';			//MySQL server address, usually localhost
const SQLUSER = 'CHANGEME';			//MySQL user (must be changed)
const SQLPASS = 'CHANGEME';			//MySQL user's password (must be changed)
const SQLDB = 'CHANGEME';			//Database used by image board

// File-related settings
const IMG_DIR = 'src/';				//Image directory (needs to be 777)
const THUMB_DIR ='thumb/';			//Thumbnail directory (needs to be 777)
const HOME =  '../';				//Site home directory (up one level by default
const MAX_KB = 2000;				//Maximum upload size in KB
const MAX_W =  250;				//Images exceeding this width will be thumbnailed
const MAX_H =  250;				//Images exceeding this height will be thumbnailed
const SWF_ENABLED = false;			//Whether or not upload of Flash files is allowed
const OEKAKI_ENABLED = false;			//Whether or not oekaki is enabled, duh
const FORCEIMAGE = false;			//Whether or not threads must start with an image
const USE_THUMB = true;				//Use thumbnails
const PHP_SELF = 'imgboard.php';		//Name of main script file (if ECHOALL, you probably want 'index.php')
const PHP_SELF2 = 'index.html';			//Name of main html file (point to PHP_SELF if ECHOALL)
const PHP_EXT = '.html';			//Extension used for board pages after first
$THUMBBACK = array(255,255,238);		//Thumbnail background for transp. images. Usually the background of your body element.

// Look and behavior
const ECHOALL = false;				//Do not write index to file, but echo directly (4chan-style)
const PAGE_DEF = 5;				//Threads per page
const THREADLIMIT =  40;			//Maxium number of entries
const BUMPLIMIT = 50;				//Maximum topic bumps
const COLLAPSENUM = 3;				//Number of replies to show in the index
const TRIPKEY = '#';				//this character is displayed before tripcodes

// Spam/flood protection
const RENZOKU = 5;				//Seconds between posts (floodcheck)
const RENZOKU2 = 10;				//Seconds between image posts (floodcheck)
const BR_CHECK = 15;				//Max lines per post (0 = no limit)
const PROXY_CHECK = false;			//Enable proxy check
const DISP_ID = false;				//Display user IDs
const FORCED_ANON = false;			//Force anonymous posting (except for managers)

// CSS
const CSSDEFAULT = 'Futaba';			// The name of the stylesheet to be used by default
$STYLES = array(				// Array containing NAME => FILE of stylesheets
	"Fikaba"	=>	'css/fikaba.css',
	"Futaba"	=>	'css/futaba.css',
	"Burichan"	=>	'css/burichan.css',
	"heavy_ice"	=>	'css/heavy_ice.css'
);

$FILTERS = array(				// Filters, in the format of IN => OUT
	"test"			=>	'tosti',
	"fikaba is awful"	=>	'I am awful'
);
