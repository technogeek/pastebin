
<?php/*
	highlight.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net
	
	Function to highlight syntax.
	
	Released under GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991 -  or later
*/?>

<?php
    require ( "rule.php" );

    function compare_offset ( $a, $b )
    {
        if ( $a ['offset'] > $b ['offset'] ) return 1;
        else if ( $a ['offset'] < $b ['offset'] ) return -1;
        else {
            if ( $a ['index'] > $b ['index'] ) return 1;
            if ( $a ['index'] < $b ['index'] ) return -1;
        }
    }

    function apply_rule ( $rdata, $text )
    {
        $rule = new CRule ( $rdata );
        $matches = $rule->get_matches ( $text );
        usort ( $matches, compare_offset );
        $pos = 0;
        $adjust = 0;
        for ( $i = 0; $i < count ( $matches ); $i++ ) {
            if ( $matches [$i]['offset'] >= $pos ) {
                $pos = $matches [$i]['offset'];
                $text = substr_replace ( $text, $matches [$i]['replace'], $pos+$adjust, $matches [$i]['strlen'] );
                $pos += $matches [$i]['strlen'];
                $adjust += strlen ( $matches [$i]['replace'] ) - $matches [$i]['strlen'] ;
            }
        }
        

        return $text;
    }
   
?>
