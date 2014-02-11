<?php
	/*
		Plugin Name: MODx Integration
		Plugin URI: http://www.codeistry.com/
		Description: Provides some helper functions which you can use in templates to access the MODx API and seamlessly integrate your Wordpress and MODx frontends.
		Author: Duncan Lock
		Version: 0.3
		Author URI: http://www.codeistry.com/
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

	function modxRunSnippet($snippet_name, $param_array) {
		global $modx;

		// echo '<!-- '."\n";
		// echo '$snippet_name: '.var_export($snippet_name, true)."\n";
		// echo '$param_array: '.var_export($param_array, true)."\n";
		// echo '--!>'."\n";

		$snippet_content = $modx->runSnippet($snippet_name, $param_array);

		// echo '<!-- '."\n";
		// echo '$snippet_content: '.var_export($snippet_content, true)."\n";
		// echo '--!>'."\n";

		echo $modx->parseDocumentSource($snippet_content);
	}

?>
