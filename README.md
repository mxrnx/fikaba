# Fikaba 
Version 000012

Fikaba is an imageboard engine forked from futallaby aiming to be more standards-compatible, readable, usable, and generally an updated version of futallaby, since futallaby has become abandonware.

[See here for info on migrating from futallaby](https://github.com/knarka/fikaba/blob/master/docs/migrate.md)

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
* Flood detection
* Proxy detection
* User IDs

### Features that fikaba does, but futallaby does not have
* Valid html5 instead of xhtml
* Style picker
* Capcodes
* Image replies
* The possibility to reply to any thread as a manager
* Post references (>>###)
* IP bans
* noko/nonoko (noko by default)
* Catalogs

### Planned features
In order of importance:

1. WebM support
2. json API
3. Reporting
4. Inline image expansion

## Installation
1. Place the files from this repository in a folder on your webserver and set permissions of that folder to 777
2. Edit/move config.example.php to config.php and edit it
3. Make sure IMG\_DIR and THUMB\_DIR exist and chmod them to 777
4. Navigate to imgboard.php in your browser
5. Pour yourself some puerh and admire your professional adminship

## Contributing
I'll accept useful shit, I guess. The only thing I'm really looking for is someone that understands moonspeak, to keep jp.php updated as more strings get added.

## License
See the file LICENSE.
