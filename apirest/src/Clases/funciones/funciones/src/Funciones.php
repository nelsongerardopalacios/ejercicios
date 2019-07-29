<?php

/** 
 * 
**/
namespace Funciones;

class Funciones {
	
	public function __construct(){

	}

	/*
	 * SE SACAN LOS TAGS
	 * Y SE QUITAN LOS ESPACIOS ANTERIORES Y POSTERIORES
	 */


	public function filtrar($valor) {
		if (is_array($valor)) {
			return false;
		} else if (is_string($valor)) {
			$valor = strip_tags($valor);
			$valor = trim($valor);
			return $valor;
		}else{
			return false;
		};	
	}
        
        //static function encode($var)
        public function encode($var)
        {
            //return html_entity_decode(json_encode(array_map('htmlentities', $var)));
            return html_entity_decode(json_encode(self::recursiveHtmlEntities($var)));
        }

        private static final function recursiveHtmlEntities($var)
        {
            foreach($var as $k => $v)
            {
                $var[$k] = (is_array($v))? self::recursiveHtmlEntities($v): htmlentities($v, ENT_QUOTES,'UTF-8');// htmlentities($v);
            }

             return $var;
        }

        public function decode($var)
        {
            return (object)array_map('utf8_decode', (array)json_decode($var));
        }
	
}
?>