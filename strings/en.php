<?php

define(S_HOME, 'Home');											//Forwards to home page
define(S_ADMIN, 'Manage');										//Forwards to Management Panel
define(S_RETURN, 'Return');										//Returns to image board
define(S_POSTING, 'Posting mode: Reply');								//Prints message in red bar atop the reply screen
define(S_NOTAGS, 'HTML tags are allowed.');								//Prints message on Management Board
define(S_NEWTHREAD, 'New thread');									//Header for new thread form
define(S_NAME, 'Name');											//Describes name field
define(S_CAPCODE, 'Capcode');										//Describes capcode field (admin)
define(S_REPLYTO, 'Reply to');										//Describes reply to field (admin)
define(S_EMAIL, 'E-mail');										//Describes e-mail field
define(S_SUBJECT, 'Subject');										//Describes subject field
define(S_SUBMIT, 'Submit');										//Describes submit button
define(S_COMMENT, 'Comment');										//Describes comment field
define(S_UPLOADFILE, 'File');										//Describes file field
define(S_NOFILE, 'No File');										//Describes file/no file checkbox
define(S_DELPASS, 'Password');										//Describes password field
define(S_DELEXPL, '(Password used for file deletion)');							//Prints explanation for password box (to the right)
define(S_RULES, '<ul><li>Supported file types are: GIF, JPG, PNG</li>
<li>Maximum file size allowed is '.MAX_KB.' KB.</li>
<li>Images greater than '.MAX_W.'x'.MAX_H.' pixels will be thumbnailed.</li></ul>');				//Prints rules under posting section
define(S_REPORTERR, 'Error: Cannot find reply.');							//Returns error when a reply (res) cannot be found
define(S_THUMB, 'Thumbnail displayed, click image for full size.');					//Prints instructions for viewing real source
define(S_PICNAME, 'File: ');										//Prints text before upload name/link
define(S_REPLY, 'Reply');										//Prints text for reply link
define(S_ABBR, ' posts omitted. Click Reply to view.');							//Prints text to be shown when replies are hidden
define(S_REPDEL, 'Delete Post ');									//Prints text next to S_DELPICONLY (left)
define(S_DELPICONLY, 'File Only');									//Prints text next to checkbox for file deletion (right)
define(S_DELKEY, 'Password ');										//Prints text next to password field for deletion (left)
define(S_DELETE, 'Delete');										//Defines deletion button's name
define(S_PREV, 'Previous');										//Defines previous button
define(S_FIRSTPG, 'Previous');										//Defines previous button
define(S_NEXT, 'Next');											//Defines next button
define(S_LASTPG, 'Next');										//Defines next button
define(S_FOOT, '- <a href="http://php.s3.to" target="_blank">GazouBBS</a> + <a href="http://www.2chan.net/" target="_blank">futaba</a> + <a href="http://www.1chan.net/futallaby/" target="_blank">futallaby</a> + <a href="https://github.com/knarka/fikaba" target="_blank">fikaba</a> -'); //Prints footer (leave these credits)
define(S_RELOAD, 'Return');										//Reloads the image board (refresh)
define(S_UPFAIL, 'Error: Upload failed.');								//Returns error for failed upload (reason: unknown?)
define(S_NOREC, 'Error: Cannot find record.');								//Returns error when record cannot be found
define(S_SAMEPIC, 'Error: Duplicate md5 checksum detected.');						//Returns error when a md5 checksum dupe is detected
define(S_TOOBIG, 'This image is too large!  Upload something smaller!');
define(S_TOOBIGORNONE, 'Either this image is too big or there is no image at all.  Yeah.');
define(S_UPGOOD, ' '.$upfile_name.' uploaded!<br><br>');						//Defines message to be displayed when file is successfully uploaded
define(S_STRREF, 'Error: String refused.');								//Returns error when a string is refused
define(S_UNJUST, 'Error: Unjust POST.');								//Returns error on an unjust POST - prevents floodbots or ways not using POST method?
define(S_NOPIC, 'Error: No file selected.');								//Returns error for no file selected and override unchecked
define(S_NOTEXT, 'Error: No text entered.');								//Returns error for no text entered in to subject/comment
define(S_MANAGEMENT, 'Manager: ');									//Defines prefix for Manager Post name
define(S_DELETION, 'Deletion');										//Prints deletion message with quotes?
define(S_TOOLONG, 'Error: Field too long.');								//Returns error for too many characters in a given field
define(S_UNUSUAL, 'Error: Abnormal reply.');								//Returns error for too long $resto or $url (should never happen)
define(S_BADHOST, 'Error: Host is banned.');								//Returns error for banned host ($badip string)
define(S_PROXY80, 'Error: Proxy detected on :80.');							//Returns error for proxy detection on port 80
define(S_PROXY8080, 'Error: Proxy detected on :8080.');							//Returns error for proxy detection on port 8080
define(S_SUN, 'Sun');											//Defines abbreviation used for "Sunday"
define(S_MON, 'Mon');											//Defines abbreviation used for "Monday"
define(S_TUE, 'Tue');											//Defines abbreviation used for "Tuesday"
define(S_WED, 'Wed');											//Defines abbreviation used for "Wednesday"
define(S_THU, 'Thu');											//Defines abbreviation used for "Thursday"
define(S_FRI, 'Fri');											//Defines abbreviation used for "Friday"
define(S_SAT, 'Sat');											//Defines abbreviation used for "Saturday"
define(S_ANONAME, 'Anonymous');										//Defines what to print if there is no text entered in the name field
define(S_ANOTEXT, '');											//Defines what to print if there is no text entered in the comment field
define(S_ANOTITLE, '');											//Defines what to print if there is no text entered into subject field
define(S_RENZOKU, 'Error: Flood detected, post discarded.');						//Returns error for $sec/post spam filter
define(S_RENZOKU2, 'Error: Flood detected, file discarded.');						//Returns error for $sec/upload spam filter
define(S_RENZOKU3, 'Error: Flood detected.');								//Returns error for flood? (don't know the specifics)
define(S_DUPE, 'Error: Duplicate file entry detected.');						//Returns error for a duped file (same upload name or same tim/time)
define(S_NOTHREADERR, 'Error: Thread specified does not exist.');					//Returns error when a non-existant thread is accessed
define(S_SCRCHANGE, 'Updating page.');									//Defines message to be displayed when post is successful									//
define(S_BADDELPASS, 'Error: Password incorrect.');							//Returns error for wrong password (when user tries to delete file)
define(S_WRONGPASS, 'Error: Management password incorrect.');						//Returns error for wrong password (when trying to access Manager modes)
define(S_MANALOGGEDIN, 'You are now logged in.');
define(S_RETURNS, 'Return');										//Returns to HTML file instead of PHP--thus no log/SQLDB update occurs
define(S_LOGUPD, 'Rebuild');										//Updates the log/SQLDB by accessing the PHP file
define(S_MANAMODE, 'Manager Mode');									//Prints heading on top of Manager page
define(S_LOGOUT, 'Logout');										//Logout button in management panel
define(S_MANAREPDEL, 'Deletion Panel');									//Defines Management Panel button--allows the user to view the management panel (overview of all posts)
define(S_MANABAN, 'Ban Panel');										//Defines Manager Ban button
define(S_MANAPOST, 'Manager Post');									//Defines Manager Post button--allows the user to post using HTML code in the comment box
define(S_MANAACCS, 'Account Management');								//Defines button for adding/removing manager accoutns
define(S_MANASUB, 'Submit');										//Defines name for submit button in Manager Mode
define(S_ITDELETES, 'Delete');										//Defines for deletion button in Management Panel
define(S_MDRESET, 'Reset');										//Defines name for field reset button in Management Panel
define(S_MDONLYPIC, 'File Only');									//Sets whether or not to delete only file, or entire post/thread
define(S_MDTABLE1, '<th>Delete?</th><th>Post No.</th><th>Time</th><th>Subject</th>');			//Explains field names for Management Panel (Delete?->Subject)
define(S_MDTABLE2, '<th>Name</th><th>IP</th><th>Comment</th><th>Host</th><th>Size<br>(Bytes)</th><th>md5</th><th>Reply #</th><th>Timestamp (s)</th><th>Timestamp (ms)</th>');	//Explains names for Management Panel (Name->md5)
define(S_RESET, 'Reset');										//Sets name for field reset button (global)
define(S_IMGSPACEUSAGE, 'Space used: ');								//Prints space used KB by the board under Management Panel
define(S_CANNOTWRITE, 'Error: Cannot write to directory.<br>');						//Returns error when the script cannot write to the directory, this is used on initial setup--check your chmod (777)
define(S_NOTWRITE, 'Error: Cannot write to directory.<br>');						//Returns error when the script cannot write to the directory, the chmod (777) is wrong
define(S_NOTREAD, 'Error: Cannot read from directory.<br>');						//Returns error when the script cannot read from the directory, the chmod (777) is wrong
define(S_NOTDIR, 'Error: Directory does not exist.<br>');						//Returns error when the script cannot find/read from the directory (does not exist/isn't directory), the chmod (777) is wrong
define(S_SQLCONF, 'MySQL connection failure');								//MySQL connection failure
define(S_SQLDBSF, 'Database error, check SQL settings<br>');						//Database select failure
define(S_TCREATE, "Creating table!<br>\n");								//Creating table
define(S_FCREATE, "Creating folder!<br>\n");								//Creating table
define(S_TCREATEF, 'Unable to create table!<br>');							//Table creation failed
define(S_SQLFAIL, 'Critical SQL problem!<br>');								//SQL Failure
define(S_BANRENZOKU, 'Error: you are banned. Post discarded. Check on the status of your ban <a href="?mode=banned">here</a>.');	//Error shown to banned user when they try to post
define(S_BANNEDMESSAGE, 'You are banned!');
define(S_BANTIME, 'You were banned on: ');
define(S_BANEXPIRE, 'Your ban expires on: ');
define(S_MANABANIP, 'IP or post no.: ');
define(S_MANABANEXP, 'Expires in (days): ');
define(S_MANABANPUBMSG, 'Public reason (only if no. ban): ');
define(S_MANABANPRIVMSG, 'Private reason: ');
define(S_MANARMP, 'Remove post? ');
define(S_MANARMALLP, 'Remove all posts by this IP? ');
define(S_MANAUNBAN, 'Unban instead of ban');
define(S_BANEXPERROR, 'Please give a number of days to ban this user for.');
define(S_NOSUCHPOST, 'The post you\'re trying to ban for does not exist.');
define(S_BANEXPIRED, 'Your ban has expired, and has been removed from the database.');
define(S_BANNEDMSG, 'user was banned for this post');							//Default public message for bans
define(S_CATALOG, "View mode: Catalog");
define(S_CATALOGBUTTON, "Catalog");
define(S_NOPERMISSION, 'You do not have the necessary permissions to do that.');			// text showed when illegally trying to access some part of the management panel
define(S_ACCCREATED, 'Account created succesfully!');
define(S_ACCDEL, 'Can delete posts? ');
define(S_ACCBAN, 'Can ban users? ');
define(S_ACCCAP, 'Can post with capcode? ');
define(S_ACCACC, 'Can create new accounts? ');
define(S_VERSION, 'version');
