
<?php/*
	submit.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net

	This is the script that submits the text to the database.
	It then gives the user a link to the entry.

	Released under GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991 -  or later
*/?>

<html>
    <head>
        <title>Open Pastebin</title>
    </head>
    <body>
        <?php
            require ( "database.php" );
            require ( "highlight.php" );
            if ( !isset ( $_POST ['input_text'] ) ) die ( "Input text is not set!" );
            if ( !isset ( $_POST ['input_language'] ) ) die ( "Input language is not set!" );
            $text = $_POST ['input_text'];

            database_connect ();
            $id = database_entries ();
            database_insert ( $id, $_POST['input_language'], $text );
            print ( "Entry added.<br>" );
            $url  = "http://" . $_SERVER['HTTP_HOST'] . dirname ( $_SERVER['PHP_SELF'] );
            $url .= "/view.php?id=" . $id;
            print ( "Link:<br><a href=\"" . $url . "\">" . $url . "</a>" );
        ?>
    </body>
</html>
