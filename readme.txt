
Open Pastebin
An open-source pastebin
Version: 0.2-development

Ville Särkkälä - villeveikko@users.sourceforge.net

Released under GNU GENERAL PUBLIC LICENSE
Version 2, June 1991 -  or later

NOTE! THIS IS EXPERIMENTAL SOFTWARE MEANT ONLY FOR TESTING! USE AT YOUR OWN RISK!
I assume no responsibility for any damages if you choose to ignore the abovementioned warning.

Files:
pastebin.php		- the main/index file
submit.php		- the script that submits an entry
view.php		- displays the entries

config.php		- configuration file
database.php		- database subroutines
rule.php		- subroutines for parsing the rules and applying them
highlight.php		- function for highlighting syntax
xmlparser.php		- a relatively generic XML parser

empty.php		- remove OPB entries (and the OPB database).
SECURITY WARNING: DON'T KEEP THIS WHERE IT CAN BE ACCESSED!

gpl.txt			- the GNU General Public License.
readme.txt		- this file.

Setting up:
Open Pastebin, as a collection of PHP scripts, requires a web server and PHP. You can get a good open-source web server at http://www.apache.org/ and PHP from http://www.php.net/. Also, if you want to use a MySQL database, you need MySQL from http://www.mysql.com/

Changes:
Version 0.1-development:
- First version; basic framework up and running.
Version 0.2-development:
- Improved and cleaned all parts
- Added customizable syntax highlighting configured through XML
- Added the option to modify and resubmit an entry
- Added highlighting support for 'None' (no highlighting) and 'C/C++'
