
<?php/*
	rule.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net
	
	Helper classes for syntax highlighting rules.
	- CToken - represents tokens and ranges
	- CClass - represents classes
	- CRule - represents the entire rule

	A 'rule' corresponds with a language (C/C++, Visual Basic)
	A 'class' corresponds with a class of related entities (keywords, comments)
	A 'token' corresponds with a certain entity (multiline comment, string, do, for, printf, &, <<)
	
	A class specifies most style and functionality rules for it's entities; the
	entities themselves may contain certain entity-specific rules (link, link title, etc)

	Released under GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991 -  or later
*/?>

<?php
    function preg_escape ( $text )
    {
        $spec  = "\"\'^$.[]|()?*+{}/!";
        $text = str_replace ( "\\n", "\n", $text );
        $text = addcslashes ( $text, $spec );
        $text = htmlentities ( $text );
        return $text;
    }

    class CToken {
        var $start_tag = "";
        var $end_tag = "";
        var $pattern;
        var $link = "";
        var $tip = "";
        var $token = "";

        function CToken ( $token )
        {

            if ( $token ['attributes']['START'] ) {
                $start = preg_escape ( $token ['attributes']['START'] );
                $end = preg_escape ( $token ['attributes']['END'] );
                if ( $token ['attributes']['ESCAPE'] ) {
                    $esc = preg_escape ( $token ['attributes']['ESCAPE'] );
                    $this->pattern  = "/" . $start . "(.*?)" . "(?<!$esc)$end" . "/s";
                } else $this->pattern  = "/" . $start . "(.*?)" . $end . "/s";
            } else if ( $token ['attributes']['ANY'] ) {
                $this->pattern = "/" . preg_escape ( $token ['value'] ) . "/";
            } else {
                $this->pattern = "/\b(" . preg_escape ( $token ['value'] ) . ")\b/";
            }
            if ( $token ['attributes']['LINK'] ) {
                $this->link = $token ['attributes']['LINK'];
            }
            if ( $token ['attributes']['TIP'] ) {
                $this->tip = $token ['attributes']['TIP'];
            }
            $this->token = $token ['value'];
        }

        function apply ( $text )
        {
            return ( $this->start_tag . $text . $this->end_tag );
        }

        function get_matches ( $text )
        {
            $raw_matches = array ();
            $matches = array ();
            preg_match_all ( $this->pattern, $text, &$raw_matches, PREG_OFFSET_CAPTURE );
            $raw_matches = $raw_matches [0];
            for ( $i = 0; $i < count ( $raw_matches ); $i++ ) {
                $ret ['strlen'] = strlen ( $raw_matches [$i][0] );
                $ret ['replace'] = $this->apply ( $raw_matches [$i][0] );
                $ret ['offset'] = $raw_matches [$i][1];

                $ret ['link'] = $this->link;
                $ret ['token'] = $this->token;
                $ret ['tip'] = $this->tip;

                $matches [] = $ret;
            }
            return $matches;
        }

    };

    class CClass {
        var $start_tag;
        var $end_tag;
        var $tokens = array ();
        var $linkbase = false;

        function CClass ( $class )
        {
            $this->start_tag = "<font";
            $this->end_tag = "";
            if ( $class ['attributes']['COLOR'] ) {
                $this->start_tag .= " color=\"";
                $this->start_tag .= $class ['attributes']['COLOR'];
                $this->start_tag .= "\"";
            }
            $this->start_tag .= ">";
            if ( $class ['attributes']['STYLE'] == 'bold' ) {
                $this->start_tag .= "<b>";
                $this->end_tag .= "</b>";
            }
            if ( $class ['attributes']['STYLE'] == 'italic' ) {
                $this->start_tag .= "<i>";
                $this->end_tag .= "</i>";
            }
            if ( $class ['attributes']['LINKBASE'] ) {
                $this->linkbase = $class ['attributes']['LINKBASE'];
            } else $this->linkbase = false;
            $this->end_tag .= "</font>";
            if ( $class ['TOKEN'] ) {
                foreach ( $class ['TOKEN'] as $tok ) {
                    $this->tokens [] = new CToken ( $tok, $linkbase );
                }
            }
            if ( $class ['RANGE'] ) {
                foreach ( $class ['RANGE'] as $ran ) {
                    $this->tokens [] = new CToken ( $ran );
                }
            }
        }

        function get_matches ( $text )
        {
            $matches = array ();
            for ( $i = 0; $i < count ( $this->tokens ); $i++ ) {
                $matches = array_merge ( $matches, $this->tokens [$i]->get_matches ( $text ) );
            }
            for ( $i = 0; $i < count ( $matches ); $i++ ) {
                $matches [$i] = $this->apply ( $matches [$i] );
            }
            return $matches;
        }

        function apply ( $match )
        {
            $start_tag = $this->start_tag;
            $end_tag = $this->end_tag;
            if ( ( $this->linkbase !== false ) || ( $match ['link'] ) ) {
                if ( $this->linkbase !== false ) {
                    $link = $this->linkbase;
                    $link = str_replace ( "TOKEN", $match ['token'], $link );
                    $link = str_replace ( "LINK", $match ['link'], $link );
                } else if ( $match ['link'] ) {
                    $link = $match ['link'];
                }
                $start_tag .= "<a href=\"" . $link . "\"";
                if ( $match ['tip'] ) {
                    $start_tag .= " title=\"" . $match ['tip'] . "\"";
                }
                $start_tag .= ">";
                $end_tag .= "</a>";
            }
            $match ['replace'] = $start_tag . $match ['replace'] . $end_tag;
            return $match;
        }

    };

    class CRule {
        var $classes = array ();

        function CRule ( $rule )
        {
            if ( !$rule ['CLASS'] ) return;
            foreach ( $rule ['CLASS'] as $class ) {
                $this->classes [] = new CClass ( $class );
            }
        }

        function get_matches ( $text )
        {
            if ( !$this->classes ) return array ();
            $matches = array ();
            for ( $i = 0; $i < count ( $this->classes ); $i++ ) {
                $matches = array_merge ( $matches, $this->classes[$i]->get_matches ( $text ) );
            }
            for ( $i = 0; $i < count ( $matches ); $i++ ) { 
                $matches [$i]['index'] = $i;
            }
            return $matches;
        }
    };
    
?>
