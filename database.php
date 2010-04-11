
<?php/*
	database.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net
	
	MySQL database functions.
	
	Released under GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991 -  or later
*/?>

<?php
    function database_connect ()
    {
        require ( "config.php" );
        $sql_connection = @mysql_connect ( $mysql_server, $mysql_username, $mysql_password );
        if ( !$sql_connection ) {
            die ( "Could not connect to MySQL server!" );
        }
        if ( !mysql_select_db ( $mysql_dbname ) ) {
            if ( !mysql_query ( "CREATE DATABASE " . $mysql_dbname ) ) {
                die ( "Unable to create database: " . mysql_error () );
            }
            if ( !mysql_select_db ( $mysql_dbname ) ) {
                die ( "Database creation error: " . mysql_error () );
            }
        }
        $query  = "CREATE TABLE IF NOT EXISTS";
        $query .= " Entries ( ID TINYBLOB, Date DATETIME, Language TINYBLOB, Text BLOB )";
        if ( !mysql_query ( $query ) ) {
            die ( "Unable to create table: " . mysql_error () . "<br>" );
        }
    }

    function database_insert ( $id, $language, $text )
    {
        $query  = "INSERT INTO Entries ( ID, Date, Language, Text )";
        $query .= " VALUES ( '$id', CURRENT_TIMESTAMP(), '$language', '$text' )";
        if ( !mysql_query ( $query ) ) {
            die ( "Unable to perform insertion query: " . mysql_error () );
        }
    }

    function database_retrieve ( $id )
    {
        $entry = mysql_query ( "SELECT * FROM Entries WHERE ID = '$id'" );
        if ( !$entry ) {
            die ( "Query error: " . mysql_error () );
        }
        $array = mysql_fetch_assoc ( $entry );
        if ( !$array ) {
            die ( "Entry does not exist!" );
        }
        return $array;
    }

    function database_entries ()
    {
        $entries = mysql_query ( "SELECT * FROM Entries" );
        if ( !$entries ) {
            die ( "Unable to get number of entries: " . mysql_error () );
        }
        return mysql_num_rows ( $entries );
    }

?>
