# Fikaba 
Version 000007

Fikaba is a fork of futallaby aiming to be more standards-compatible, readable, usable, and generally an updated version of futallaby, since futallaby has become abandonware.

So far, fikaba is database-compatible with futallaby 040103. I cannot promise it will stay this way, however.

## Instances
I want to list some examples of sites running fikaba. If you are using fikaba, please make a pull request or otherwise contact me to add your site to the (for now empty) list below.

* none yet

## Features
### Features inherited from futallaby
* MySQL database
* Highly configurable
* Image posting, automatic thumbnailing
* Manager panel
* Raw HTML posts for managers
* Citations
* User post deletion

### Features that fikaba does, but futallaby does not have
* Valid html5 instead of xhtml
* Style picker
* Capcodes
* Image replies
* The possibility to reply to any thread as a manager
* Post references (>>###)
* IP bans

### Planned features
In order of importance:

1. Unbanning
2. Fancy bans (USER WAS BANNED FOR THIS POST)
3. WebM support
4. Reporting

## Installation
1. Place the files from this repository in a folder on your webserver and set permissions of that folder to 777
2. Edit/move config.example.php to config.php and edit it
3. Make sure IMG\_DIR and THUMB\_DIR exist and chmod them to 777
4. Navigate to imgboard.php in your browser
5. Pour yourself some puerh and admire your professional adminship

## License
See the file LICENSE.
