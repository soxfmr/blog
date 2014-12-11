<?php
	class Security
	{
		static function validate($var) {
			if(get_magic_quotes_gpc())
				$var = stripslashes($var)
			return mysql_real_escape_string($var);
		}
	}
?>