<?php
	/*
		Plugin Name: MODx Integration
		Plugin URI: http://www.codeistry.com/
		Description: Provides some helper functions which you can use in templates to access the MODx API and seamlessly integrate your Wordpress and MODx frontends.
		Author: Duncan Lock
		Version: 0.2
		Author URI: http://www.codeistry.com/
	*/

	/*  Copyright 2008  Duncan Lock  (email : dunc@codeistry.com)
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

	// Initialize MODx stuff
	/*
		Figure out if we're inside wp-admin. I'm sure there's a better way to 
		do this; WP must have some built in function or varaible that you can check.
	*/
	$inWPAdmin = strpos($_SERVER['PHP_SELF'], 'wp-admin');
	
	if ($inWPAdmin === false) {
		/*
			Including this when using the wp-admin interface borks everything for some reason, so don't.
			None of the functions that need this file will be used in the wp-admin interface, so this
			doesn't matter, although when they are used (on the public facing pages), they need it globally
			scoped, so this can't go inside the functions.
		*/
		include_once('modx_init.php');
	}


	function modxGetChunk($chunk_name) {
		global $modx;

		$chunk_content = $modx->getChunk($chunk_name);
		// check for non-cached snippet output
		if (strpos($chunk_content, '[!') > -1) {
			$chunk_content = str_replace('[!', '[[', $chunk_content);
			$chunk_content = str_replace('!]', ']]', $chunk_content);
		}
		$chunk_content = $modx->rewriteUrls($modx->parseDocumentSource($chunk_content));
		echo $chunk_content;
	}

	function modxRunSnippet($snippet_name, $param_string) {
		global $modx;

		$snippet_content = $modx->runSnippet($snippet_name, parseParamString($param_string));
		echo $modx->parseDocumentSource($snippet_content);
	}
	
	function parseParamString($paramString) {
		$parameter = array ();
		if (!empty ($paramString)) {
			$tmpParams = explode("&", $paramString);
			for ($x = 0; $x < count($tmpParams); $x++) {
				if (strpos($tmpParams[$x], '=', 0)) {
					$pTmp = explode("=", $tmpParams[$x]);
					$parameter[$pTmp[0]] = trim(str_replace('`', '', $pTmp[1]));
				}
			}
		}
		return $parameter;
	}
?>
