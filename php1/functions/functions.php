<?php

function makeFile( $filename )
	{
		// if files doesn't exist, create the file and close it
		if ( ! is_file( $filename ) )
		{
			fclose( fopen($filename, 'x') );
			return true;
		}

		// file already exists
		return false;
	}
?>