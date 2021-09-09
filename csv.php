<!DOCTYPE html>
<html>
<head>
    <title>CSV | RVTML | Raster to Vector Tracing Using Machine Learning</title>
    <link rel="stylesheet" href="https://cdn.darlic.com/assets/css/aionefull.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        html {
            font-size: 15px;
        }


    </style>
</head>
<body>
    <div id="aione_wrapper" class="aione-wrapper page-home layout-header-top aione-layout-wide aione-theme-arcane">
        <div class="wrapper">
            <div id="aione_header" class="aione-header light load-template">
            </div>
            <!--aione-header-->
            <div id="aione_main" class="aione-main fullwidth p-0">
                <div class="wrapper">
                    <div id="aione_content" class="aione-content">
                        <div class="wrapper">
                            <div id="aione_page_content" class="aione-page-content m-0">
                                <div class="wrapper">
                                    <section class="bg-blue-grey bg-lighten-5 ph-10p pv-40">

                                        <div class="bg-white p-30">
                                        <div class="ar">




<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$path = 'assets/images/icons/png';
$index = 0;

unlink('data/data.csv');
$data_file = fopen('data/data.csv', 'w');

$headers = array( 'id', 'png_path', 'svg_path','svg_code');

fputcsv( $data_file, $headers );

// Is there actually such a folder/file?
if( file_exists( $path ) ) {


	foreach( scandir( $path ) as $file ) {

        if( !$file || $file[0] == '.' ) {

            continue; // Ignore hidden system files

        }

        if( is_dir( $path . '/' . $file ) ) {

            // The path is a folder

        } else {

            $index++;

        	$output = '';

	        $file_name 		= pathinfo($file, PATHINFO_FILENAME);
	        $extension 		= pathinfo($file, PATHINFO_EXTENSION);

	        $file_path 		= $path . '/' . $file_name . '.' . $extension;

	        $svg_file_path = str_replace( "png","svg",$file_path );
            // $svg_file_name = $file_name;
            // $svg_file_extension = str_replace( "png","svg",$file_name );

            // $svfile_path      = $path . '/' . $file_name . '.' . $extension;
	        // echo '<img src="'. $file_path . "' . $svg_file_name;

            $svg_file_code = file_get_contents($svg_file_path);

            $svg_file_code  = str_replace( array("\n","\r"), '', $svg_file_code );

            $data = array( $index, $file_path, $svg_file_path, $svg_file_code);

            fputcsv( $data_file, $data );



        } //if
		

	} //foreach

} //if

fclose($data_file);
?>

                                        </div>
                                        </div>
                                            
                                    </section>

                                </div>
                            </div>
                            <!--aione-page-content-->
                        </div>
                        <!--wrapper-->
                    </div>
                    <!--aione_content-->
                </div>
                <!--wrapper-->
            </div>
            <!--aione-main-->
            <div id="aione_footer" class="aione-footer">
            </div>
            <!-- .aione-footer -->
            <div id="aione_copyright" class="aione-copyright dark load-template">
            </div>
            <!-- .aione-copyright -->
        </div>
        <!--wrapper-->
    </div>
    <script src="https://cdn.aioneframework.com/assets/js/vendor.min.js"></script>
    <script src="https://cdn.aioneframework.com/assets/js/aione.min.js"></script>

</body>
</html>