
<?php/*
	view.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net

	The ID is given as a query string, for example:
	http://domain.com/pastebin/view.php?id=349
	view.php then connects to the database, fetches
	the data, and outputs it.
	The viewer is also given an option to (re)submit
	a modified version of the code.	

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
            require ( "xmlparser.php" );
            if ( !isset ( $_REQUEST['id'] ) ) die ( "ID not specified!" );
            database_connect ();
            $array = database_retrieve ( $_REQUEST['id'] );

            $text = htmlentities ( $array ['Text'] );

            $xml_parser = new CXmlParser;
            $rules = $xml_parser->parse ( "rules.xml" );
            $highlighted_text = apply_rule ( $rules ['RULE'][$array ['Language']], $text );
            
            $lines = explode ( "\n", $highlighted_text );
        ?>
        <table border="1" cellpadding="2">
            <tr>
                <td>
                    <?php
                        print ( "Language: ". $rules ['RULE'][$array['Language']]['attributes']['NAME'] );
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                        print ( "ID: " . $array['ID'] );
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                        print ( "Date: " . $array['Date'] ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0">
                        <td align="right">
                            <?php
                                print ( "<pre>" );
                                for ( $i = 0; $i < count ( $lines ); $i++ ) {
                                    print ( $i . "\n" );
                                }
                                print ( "</pre>" );
                            ?>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td nowrap align="left">
                            <?php
                                print ( "<pre>\n" . $highlighted_text . "\n</pre>" );
                            ?>
                        </td>
                    </table>
                </td>
            </tr>
        </table>
        <form method="post" action="submit.php">
            <input type="hidden" name="input_language" value="<?php
                print ( $array ['Language'] );
            ?>">
            Make changes:<br>
            <textarea name="input_text" rows="25" cols="80"><?php
                    print ( "\n" . $text );
            ?></textarea>
            <br><br>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
