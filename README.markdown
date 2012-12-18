Downloads
=========

**__REQUIRES Core 1.3.3+ in the 1.3.x series__**

Upgrade of original (PostNuke) Downloads module for Zikula 1.3.x with
significantly reduced functionality.

Really, this work is only intended as a 'bridge' to allow sites currently using
Downloads to quickly upgrade to Zikula 1.3 while we all await the monolithic
'EMR' module that will replace all things media related.

This only has basic functionality including:
    - upgrade from v2.4 
    - admin (only) creation of new entries including upload of files
    - user and admin view with filter by category

This is a 'blind' rewrite with no actual base in the old code*. It just begins
with the data and seeks to manage it with the new tools available in
Zikula 1.3.x

*The original license is upgraded from LGPLv2 to LGPLv3+ as there is a few bits
of code retained.

###Version 3.1.3
_24 September 2012_

- correct bugs
    - download deleted on editing entry
    - cannot use storage directory above doc root
    - problems in upgrade routine (to 3.1.0) that some items didn't get upgraded
    - redirect on saving admin settings caused problem
    - cache issue when fetching CID
- Added check to prevent overwrite of same filenames

###Version 3.1.2
_17 August 2012_

Fix search function

###Version 3.1.1
_29 April 2012_

Adds enforcement of higher core version requirement.


###Version 3.1.0

_22 April 2012_

    - Corrections to caching (contributed by nmpetkov)
    - Addition of List block (supported by M. Doucha [mdee28])
    - Conversion from Doctrine 1.2 to Doctrine 2.1


###Version 3.0.0

_21 July 2011_

Initial release as stated above.
