<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once( 'include/functions.php' );

$path = 'assets/images/icons/png';


// Is there actually such a folder/file?
if( file_exists( $path ) ) {

	foreach( scandir( $path ) as $file ) {

        if( !$file || $file[0] == '.' ) {

            continue; // Ignore hidden system files

        }

        if( is_dir( $path . '/' . $file ) ) {

            // The path is a folder

        } else {

	        $file_name 		= pathinfo($file, PATHINFO_FILENAME);
	        $extension 		= pathinfo($file, PATHINFO_EXTENSION);

	        $file_path 		= $path . '/' . $file_name . '.' . $extension;

	        $svg_file_name = str_replace( "png","svg",$file_name );
	        echo "<br> ". $file_name . " ---> " . $svg_file_name;

	        #Remove non 
	        // $file_name = preg_replace('/[^0-9_]/', '', $file_name );
	        // $file_name = str_replace( "__","_",$file_name );
	        // $file_name = str_replace( "__","_",$file_name );
	        // $file_name = str_replace( "__","_",$file_name );
	        // $file_name = str_replace( "__","_",$file_name );
	        // $file_name = trim( $file_name, '_' );

	        $new_file_path 	= $path . '/' . $file_name . '.' . $extension;

	        // echo "<br> ". $new_file_path;


	        // rename( $file_path,  $new_file_path);


        } //if
		

	} //foreach

} //if