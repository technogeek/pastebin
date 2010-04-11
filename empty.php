
<?php/*
	empty.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net

	This file removes (from the specified server) the databases
	put there by Open Pastebin.
	SECURITY WARNING: DON'T KEEP THIS WHERE IT CAN BE ACCESSED!

	Released under GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991 -  or later
*/?>

<?php
    die ( "Remove this line and execute this script to empty the database." );
    require ( "config.php" );
    mysql_connect ( $mysql_server, $mysql_username, $mysql_password );
    mysql_query ( "DROP DATABASE " . $mysql_dbname );
    print ( "Done!" );
?>
