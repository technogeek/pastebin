
<?php/*
	xmlparser.php
	Part of the Open Pastebin project - version 0.2-development
	10/8/2004
	Ville Särkkälä - villeveikko@users.sourceforge.net
	
	A relatively generic XML parser.
	
	Released under GNU GENERAL PUBLIC LICENSE
	Version 2, June 1991 -  or later
*/?>

<?php
    class CXmlParser {
        var $current;
        var $top;
        var $stack = array ();

        function parse ( $filename )
        {
            if ( !$text = file_get_contents ( $filename ) )
                return 0;

            $xml_parser = xml_parser_create ();
            xml_set_object ( $xml_parser, $this );
            xml_set_element_handler ( $xml_parser, "start_handler", "end_handler");
            xml_set_character_data_handler ( $xml_parser, "cdata_handler");
            if ( !xml_parse ( $xml_parser, $text ) ) {
                die ( "XML parsing error: " . xml_error_string ( xml_get_error_code ( $xml_parser ) ) );
            }
            xml_parser_free ( $xml_parser );
            return $this->current ['ROOT'][0];
        }

        function start_handler ( $parser, $name, $attributes )
        {
            array_push ( $this->stack, $this->top );
            $this->top = $this->current;
            $this->current = array ();
            $this->current ['attributes'] = $attributes;
        }
        
        function end_handler ( $parser, $name )
        {
            $this->top [$name][] = $this->current;
            $this->current = $this->top;
            $this->top = array_pop ( $this->stack );
        }

        function cdata_handler ( $parser, $data )
        {
            $this->current ['value'] = $data;
        }
    }
?>
