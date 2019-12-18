/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/



Pagebuilder framework application

/.htaccess	filters url and forwards to index.php
/index.php	instantiates site and echos page
/includes 	contains controller pagebuilder.inc.php
			contains config.inc.php including paths and site TITLE
			contains page subpart object files

/js			contains required JS scripts
/images		contains graphic (images)
/pages		contains the content ofeach page

To add/delete/modify a new page:
- add page to mainmenu.inc.php
- add page to pages folder
- add page type selection in secure.inc.php

To change page TITLE
- modify mainmenu.inc.php

To add JS scripts
- add this script in /js/functions.js

CRUD in a page i.e. gebruikers
	+ getHtml() checks if there's an ACTION (ie: add, detail)
		if so process ACTION return the method (ie: add)
		if not show as normal page (overview of users)
	- (C) add() processes the ACTION add. 
		first check hidden parameter since this is returned form
			if parameter existing, run processform
		if parameter not present show form with the hidden parameter
	- (R) details()....etc
	- (U) update()....etc
	- (D) delete()....etc


