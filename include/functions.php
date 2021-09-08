<?php 


/************************************************************
*   @function get_dns
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return output
************************************************************/
function get_dns($dns, $record = 'A', $array = false){
    $output = "";
    $dns_records = @json_decode($dns,true)[$record];
    if($array){
        return $dns_records;
    } else {
        return @implode('<br>',$dns_records);
    }
}



/************************************************************
*   @function get_server
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return output
************************************************************/
function get_server($ip, $servers){
    $ips = get_dns($ip,'A',true);
    // echo "<pre>";
    // print_r($ips);
    // echo "<pre>";
    $output = "";
    foreach($servers as $key => $server){
        $server_ips = explode(',',$server['server_ips']);
        if(in_array($ips[0],array_map('trim',$server_ips))){
            $output .= '<a class="action-button" href="servers.php?action=view&server_id='.$server['id'].'">'.$server['server_name'].'</a>';
        }
    }
    return $output;
}



/************************************************************
*   @function get_dns_provider
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return output
************************************************************/
function get_dns_provider($domain_ns, $dns_providers){  
    $explodedDns = get_dns($domain_ns,'NS',true);
    $output = "";
    foreach($dns_providers as $key => $dns){
        $explodedNameServers = explode(',',$dns['name_servers']);
        $dnsList = [];
        foreach($explodedDns as $k => $v){
            $dnsData = explode('.',$v);
            unset($dnsData[count($dnsData)-1]);
            $dnsList[] = implode('.',$dnsData);
        }
        if(count(array_intersect($dnsList, $explodedNameServers)) > 0){
            $output .= '<a class="action-button" href="servers.php?action=view&server_id='.$dns['id'].'">'.$dns['title'].'</a>';
        }
    }
    return $output;
}



    /************************************************************
    *   @function clean_class
    *   @description 
    *   @access public
    *   @since  1.0.0.0
    *   @author SGS Sandhu(sgssandhu.com)
    *   @return string
    ************************************************************/
    function clean_class($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
       $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
       $string = trim($string, '-'); // Remove first or last -
       $string = strtolower($string); // lowercase

       return $string;
    }



    /************************************************************
    *   @function checkValueExistsInArray
    *   @description 
    *   @access public
    *   @since  1.0.0.0
    *   @author SGS Sandhu(sgssandhu.com)
    *   @return 
    ************************************************************/
    function aione_data_table( $headers, $data, $id='aione-', $class = 'compact' ) {  

        $columns = array();

        foreach ($headers as $key => $header){
            $columns[] = clean_class($header);
        }

        $output = "";
        $output .= '<div class="aione-search aione-table" >';
        $output .= '<div class="field">';
        $output .= '<input autofocus type="text" class="aione-search-input" data-search="'.implode(' ',$columns).'" placeholder="Search">';
        $output .= '</div>';
        $output .= '<div class="clear"></div>';
        $output .= '<table class="'.$class.'">';
        $output .= '<thead>';
        $output .= '<tr>';

        foreach ($headers as $key => $header){
            $output .= '<th class="aione-sort-button" data-sort="'.$columns[$key].'">'.$header.'</th>';
        }
        
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody class="aione-search-list">';

        if(!empty($data)){
            foreach ($data as $record_key => $record){
                $output .= '<tr>';
                foreach ($record as $key => $value){
                    $output .= '<td class="'.$columns[$key].'">'.$value.'</td>';
                }
                $output .= '</tr>';
            }
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>';

        return $output;
    }



/************************************************************
*   @function checkValueExistsInArray
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return 
************************************************************/
function checkValueExistsInArray($data, $key, $valueToCompare, $loginId, $isJson = false){
    foreach($data as $k => $val){
        if($isJson){
            $val[$key] = json_decode($val[$key]);
            if(in_array($valueToCompare,$val[$key]) && $val['login_id'] == $loginId){
                return 'selected';
            }
        }else{
            if($val[$key] == $valueToCompare && $val['login_id'] == $loginId){
                return 'selected';
            }
        }
    }
    return '';
}



/************************************************************
*   @function role_id_to_role_name
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return 
************************************************************/
function role_id_to_role_name($roles, $id){
    $filterRole = array_filter($roles, function($value, $key) use ($id){
        return $value['role_id'] == $id;
    });
    
    $filterRole = array_values($filterRole);
    return $filterRole[0]['role'];
}



/************************************************************
*   @function user_ids_to_username
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return 
************************************************************/
function user_ids_to_username($users,Array $ids){
    $usersList = array_filter($users, function($value, $key) use ($ids){
        return in_array($value['user_id'],$ids);
    });
    $userNamesArray = [];
    foreach($usersList as $key => $user){
        $userNamesArray[] = $user['username'];
    }
    return implode(',',$userNamesArray);
}



/************************************************************
*   @function find
*   @description 
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return 
************************************************************/
function find($data,Array $keyCompares){
    
    $filteredData = array_filter($data, function($value) use ($keyCompares){
        $status = false;
        foreach($keyCompares as $k => $v){
            if($value[$k] == $v){
                $status = true;
            }else{
                $status = false;
            }
        }
        if($status == true){
            return $value;
        }
    });
    
    if(!empty($filteredData)){
        return @array_values($filteredData)[0];
    }else{
        return [];
    }
}



    /************************************************************
    *   @function scan
    *   @description scan the file and folder [Recursively]
    *   @access public
    *   @since  1.0.0.0
    *   @author SGS Sandhu(sgssandhu.com)
    *   @return files [array]
    ************************************************************/
    function scan( $dir ) {

        $files = array();

        // Is there actually such a folder/file?
        if( file_exists( $dir ) ) {

            foreach( scandir( $dir ) as $f ) {

                if( !$f || $f[0] == '.' ) {
                    continue; // Ignore hidden system files
                }

                if( is_dir( $dir . '/' . $f ) ) {

                    // The path is a folder
                    $files[] = array(
                        "name"  => $f,
                        "type"  => "folder",
                        "path"  => $dir . '/' . $f,
                        "items" => scan( $dir . '/' . $f ) // Recursively get the contents of the folder
                    );

                } else {

                    // It is a file
                    $files[] = array(
                        "name" => $f,
                        "type" => "file",
                        "path" => $dir . '/' . $f,
                        "size" => filesize( $dir . '/' . $f ) // Gets the size of this file
                    );

                } //if

            } // foreach

        } // if

        return $files;
    }



/************************************************************
*   @function clean_path
*   @description remove unnecessary thing from path
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @perm path    [string path of file/folder]
*   @return path [string]
************************************************************/
function clean_path($path) {

  $path = str_replace(' ', '_', $path); // Replaces all spaces with hyphens.
  $path = str_replace('_', '_', $path); // Replaces all spaces with hyphens.
  # $path = preg_replace('/[^A-Za-z0-9\_]/', '_', $path); // Removes special chars.
  $path = preg_replace('/[^0-9_]/', '_', $path); // Removes special chars.
  $path = preg_replace('/_+/', '_', $path); // Replaces multiple hyphens with single one.
  $path = trim($path, '_'); // Remove first or last -
  $path = strtolower($path); // lowercase

  return $path;
}



/************************************************************
*   @function generate_filename
*   @description generate new filename
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @perm length    [integer  optional  default 40]
*   @perm timestamp   [true/false optional  default true]
*   @return filename [string]
************************************************************/
function generate_filename($length = 30, $timestamp = true){  

  //Check if non integer value is provided for first argument
  if(!is_int($length)){
    $length = intval($length);
  }
  
  //inialize filename variable as empty string
  $filename = '';
  
  //prepend timestamp in filename
  if($timestamp){
    $datetime = date('Ymdhis');
    $microtime = substr(explode(".", explode(" ", microtime())[0])[1], 0, 6);
    $filename .= $datetime.$microtime;
  }
  
  //Check if filename length is achieved or exceeded
  if( strlen($filename) > $length){
    $filename = substr($filename, 0, $length);
  } else {
    $random_string_length = $length - strlen($filename);
    for($i = 0; $i < $random_string_length; $i++){
      $filename .= mt_rand(1,9);
    }
  }
  
  //Return generated filename
  return $filename;
}



/************************************************************
*   @function delete_file_folder
*   @description delete file and folder
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function delete_file_folder($fileName,$type){

    //Delete the Folder
     if($type == 'dir'){
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($fileName, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');//Delete the sub file and folder
            $todo($fileinfo->getRealPath());
        }

        rmdir($fileName);//delete the folder name
    }

    //Delete the File
    if($type == 'file'){
        unlink($fileName);
    }
}



/************************************************************
*   @function download_file_folder
*   @description download file or folder
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function download_file_folder($fileName,$type){

    //Download the File
     if($type == 'file'){
        $file = $fileName;
        $basename = basename($file);
        $length   = sprintf("%u", filesize($file));
        header('Content-Description: File Transfer');
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename="' . $basename . '"');
        header('Content-Transfer-Encoding: Binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $length);
        flush();
        ob_clean();
        readfile($file);
    }

    //Download the Folder
    if($type == 'dir'){        
        $rootPath = realpath($fileName);
        $basename = basename($fileName);
        $zip = new ZipArchive();
        $zip->open($basename.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file){
            if (!$file->isDir()){
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
        $length   = sprintf("%u", filesize($basename.'.zip'));
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=".$basename.".zip");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$length);
        flush();
        ob_clean();
        @readfile($basename.'.zip');
        ignore_user_abort(true);
        unlink($basename.'.zip');
    }

   }


/************************************************************
*   @function rename_files_Folders
*   @description rename file and folder [Recursively]
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function rename_folders($path){
  $output = "";
  
  $nodes = scan($path);//Scan the Directory

  if( !empty( $nodes ) && is_array( $nodes ) ){

    foreach ($nodes as $key => $node) {
      //Rename Folder
      if( $node['type'] == 'folder' ){
        //Ignore thumb folder
        if( $node['name'] == thumb_directory_name() ){
          continue;
        } // if

        $path = $node['path'];

        $ds = directory_separator();
        $folder_name_array = explode( $ds , $path );

        $folder_name_original = array_pop($folder_name_array);

        $folder_name = clean_path($folder_name_original); // Replaces all spaces with hyphens.

        if( empty( $folder_name ) ){
          $folder_name = generate_filename();
        } //if

        $final_path = implode( $ds , $folder_name_array );
        $final_path = $final_path.$ds.$folder_name;
 
        $renamed = rename($path, $final_path);

        if( !$renamed ) {
          $folder_name = $folder_name.'-'.generate_filename(10, false);
          $final_path = implode( $ds , $folder_name_array );
          $final_path = $final_path.$ds.$folder_name;
          rename($path, $final_path);
        }

        
        
        $output .= 'Renaming <span class="yellow accent-4">'.$folder_name_original.'</span> to <span class="cyan lighten-2">' .$folder_name. '</span><br>';

        rename_folders($final_path);//Recursive Call to rename File or Folder
      } // if
    } // foreach
  } // if

  echo $output;
}



/************************************************************
*   @function rename_data
*   @description rename file and folder
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function rename_data($path){

    echo "Path =". $path;

  //$path is complete file path including filename and extension
  //load directory separator
  $ds = directory_separator();

  $pathinfo = pathinfo($path);
 
  $final_path = $pathinfo['dirname'];
  $filename_text = clean_path( $pathinfo['filename'] );
  $filename_extension = clean_path( $pathinfo['extension'] );

  $new_path = $final_path.$ds.$filename_text.'.'.$filename_extension;

  if( $path != $new_path ){
    while( file_exists( $new_path ) ) {
      $new_path = $final_path.$ds.$filename_text.generate_filename(2,false).'.'.$filename_extension;
    }
    //$renamed = rename($path, $new_path);
  }

  echo $new_path;
}

/************************************************************
*   @function generate_thumbnails
*   @description generate image thumbnails [recursively]
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function generate_thumbnails($path, $force_regenerate = false){
  $files = scan($path);
  foreach ($files as $key => $value) {

    if($value['type']=='file'){
      if (preg_match('/(\.jpg|\.png|\.jpeg|\.bmp)$/i', $value['name'])) {
          
                  
        $dirName=create_thumb_directory($value['path']);//to Check thumb directory is exist or not

        $file=  pathinfo($value['name'], PATHINFO_FILENAME).'.'.strtolower(pathinfo($value['name'],PATHINFO_EXTENSION));

        if( !file_exists($dirName.$file) ){
          $image = new ImageResize($value['path']);
          $image->resizeToBestFit(300, 300);
          $image->save($dirName.$file);//saving the image in thumb directory
        }
      } 
    }

    if($value['type']=='folder'){
      if(strtolower($value['name'])!=thumb_directory_name()){
        generate_thumbnails($value['path']); //Recursive Call to Generate images Thumbnail
      }
    }    
  }
}



/************************************************************
*   @function generate_thumbnail
*   @description generate image thumbnails
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function generate_thumbnail($path, $force_regenerate = false){
  
    $thumb_directory_path = create_thumb_directory( $path );

    $ds = directory_separator();
    $filename = explode( $ds , $path );
    $filename = end( $filename );

    $thumb_directory_name = thumb_directory_name();

    $base_path = pathinfo($path);
    $base_path = $base_path['dirname'];

    $save_path = $base_path.$ds.$thumb_directory_name.$ds.$filename;

    echo " == ".$save_path;
    
    if( !file_exists($save_path) || $force_regenerate ){
        $image = new ImageResize($path);
        $image->resizeToBestFit(300, 300);
        $image->save($save_path);//saving the image in thumb directory
    }
}


/************************************************************
*   @function find_duplicate_folders
*   @description Find duplicate folders
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function find_duplicate_folders( $path ) {
  
  $output = '';
  $output .= '<br>No duplicate found';
  return $output;

}

/************************************************************
*   @function find_duplicate_files
*   @description Find duplicate files
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function find_duplicate_files( $path ) {
  
  $output = '';
  $output .= '<br>No duplicate found';
  return $output;

}



/************************************************************
*   @function create_thumb_directory
*   @description create directory for thumb
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function create_thumb_directory($path){

  $basepath = dirname($path);
  $ds=directory_separator();
  $directory=thumb_directory_name();

  $directory_name=$basepath.$ds.$directory;

  if(!file_exists($directory_name)){
    mkdir($directory_name,0755,true);
  }

  return $directory_name;
}



/************************************************************
*   @function delete_thumbnail
*   @description delete images thumb
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function delete_thumbnail($path, $force_regenerate = false){
  
    $thumb_directory_path = create_thumb_directory( $path );

    $ds = directory_separator();
    $filename = explode( $ds , $path );
    $filename = end( $filename );

    $thumb_directory_name = thumb_directory_name();

    $base_path = pathinfo( $path );
    $base_path = $base_path['dirname'];

    $save_path = $base_path.$ds.$thumb_directory_name.$ds.$filename;
    
    if( file_exists( $save_path) || $force_regenerate ){
      unlink( $save_path );//remove the image from thumbnail directory
    }
}



/************************************************************
*   @function delete_thumbnails
*   @description delete images thumb [recursively]
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
 function delete_thumbnails( $path ) {

  $ds = directory_separator();
  $thumb_directory_name = thumb_directory_name();
  $thumb_directory = $path.$ds.$thumb_directory_name;

  if( file_exists( $thumb_directory ) && is_dir( $thumb_directory ) ) {
    $files = scandir( $thumb_directory );
    foreach ($files as $key => $file) {
      if(!$file || $file[0] == '.') {
        continue; // Ignore hidden system files
      }

      if( is_dir( $thumb_directory.$ds.$file ) ){
        rename( $thumb_directory.$ds.$file, $path.$ds.$file);
      } else{
        unlink( $thumb_directory.$ds.$file );
      }
    }
    rmdir( $thumb_directory ); 
  }
  echo $thumb_directory;
}



/************************************************************
*   @function re_generate_thumbnail
*   @description Deletes and generates thumbnail
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function re_generate_thumbnails($path){
    delete_thumbnails($path);
    generate_thumbnails($path);
}



/************************************************************
*   @function re_generate_thumbnail
*   @description Deletes and generates thumbnail
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return null
************************************************************/
function re_generate_thumbnail($path){
    delete_thumbnail($path);
    generate_thumbnail($path);
}



/************************************************************
*   @function directory_separator
*   @description Returns directory separator
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return directory_separator [string]
************************************************************/
function directory_separator(){
    
    $directory_separator = '/';
    
    return $directory_separator;
}



/************************************************************
*   @function thumb_directory_name
*   @description Returns name of the thumb directory
*   @access public
*   @since  1.0.0.0
*   @author SGS Sandhu(sgssandhu.com)
*   @return thumb_directory_name [string]
************************************************************/
function thumb_directory_name(){
    
    $thumb_directory_name = '.thumb';
    
    return $thumb_directory_name;
}



/************************************************************
*   @function allowed_image_extensions
*   @description Array of allowed image types
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return allowed_image_extensions [array]
************************************************************/
function allowed_image_extensions(){
    
    $allowed_image_extensions = array('jpg','jpeg','png','bmp');
    
    return $allowed_image_extensions;
}



/************************************************************
*   @function get_image_data
*   @description Returns array of images files in a path recursively
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return get_image_files [string]
************************************************************/
function get_image_data($path){
  $files = array();
  $allowed_image_extensions = allowed_image_extensions();

  $nodes = scan($path);

  if( !empty( $nodes ) && is_array( $nodes ) ){
    foreach ($nodes as $key => $node) {
      if( $node['type'] == 'file'){
        $extension = explode( '.' , $node['name'] );//split array
        $extension = end( $extension );
        if(in_array( $extension, $allowed_image_extensions )){
          $files[] = $node['path'];
        }
      } else {
        if( $node['name'] != thumb_directory_name() ){
          $sub_dir_path = $node['path'];
          $sub_dir_images = get_image_data( $sub_dir_path );//Recursive call
          $files = array_merge( $files , $sub_dir_images );
        }
      }
    }
  }
  return $files;
}



/************************************************************
*   @function get_files_data
*   @description Returns array of images files in a path recursively
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return get_image_files [string]
************************************************************/
function get_files_data($path){
  $files = array();
  
  $nodes = scan($path);

  if( !empty( $nodes ) && is_array( $nodes ) ){
    foreach ($nodes as $key => $node) {
      if( $node['type'] == 'file'){
        
        $files[] = $node['path'];

      } else {
        if( $node['name'] != thumb_directory_name() ){
          $sub_dir_path = $node['path'];
          $sub_dir_images = get_files_data( $sub_dir_path );//Recursive call
          $files = array_merge( $files , $sub_dir_images );
        }
      }
    }
  }
  return $files;
}



/************************************************************
*   @function get_folders
*   @description Returns array of folders in a path [recursively]
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return folders [array]
************************************************************/
function get_folders($path){
  $folders = array();

  $nodes = scan($path);
  if( !empty( $nodes ) && is_array( $nodes ) ){
    foreach ($nodes as $key => $node) {
      if( $node['type'] == 'folder'){
        if( $node['name'] != thumb_directory_name() ){
          $folders[] = $node['path'];
          $sub_folder_path = $node['path'];
          $sub_folders = get_folders( $sub_folder_path );//recursive call
          $folders = array_merge( $folders , $sub_folders );
        }
      }
    }
  }
  return $folders;
}



/************************************************************
*   @function get_tree
*   @description Returns array of folders in a path [recursively]
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return folders [array]
************************************************************/
function get_tree($path){
  $folders = array();

  $nodes = scan($path);
  if( !empty( $nodes ) && is_array( $nodes ) ){
    foreach ($nodes as $key => $node) {
      if( $node['type'] == 'folder'){
        if( $node['name'] != thumb_directory_name() ){
          $folders[] = array(
            "name" => $node['name'],
            "path" => $node['path'],
            "items" => get_tree($node['path']) // Recursively get the contents of the folder
          );
        }
      }
    }
  }
  return $folders;
}



/************************************************************
*   @function get_directory_size
*   @description Returns directory size
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return directory_size
************************************************************/
function get_directory_size($directory){
 
  $directory_size= shell_exec('du -hs '.$directory.' | cut -f1');
  return $directory_size;

}



/************************************************************
*   @function is_delete
*   @description Returns ALLOW_DELETE value
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return true/false
************************************************************/
function is_delete(){
    
  if(ALLOW_DELETE==1){
    $allow_delete=true;
  }else{
    $allow_delete=false;
  }

  return $allow_delete;
  
}



/************************************************************
*   @function draw_tree
*   @description Generate Tree
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return output [string]
************************************************************/
function draw_tree( $path ) {
  $nodes = get_tree( $path );
  $ds = directory_separator();

  if( !empty( $nodes ) && is_array( $nodes ) ){
    $output = '';
    $output .= '<ul class="nodes">';
    foreach ( $nodes as $key => $node ) {
      
      $output .= '<li class="node folder">';

      $output .= '<div class="title">';
      $output .= $node['name'];
      $output .= '</div>';

      $output .= '<div class="size">';
      $output .= get_directory_size( $node['path'] );
      $output .= '</div>';

      $total_files_counter = count_files($node['path']). ' Files';
      $total_folder_counter = count( $node['items'] ) . ' Folders';

      $output .= '<div class="items">';      
      $output .= $total_folder_counter . ', ' . $total_files_counter;      
      $output .= '</div>';

      $output .= '<div class="actions">';
      $output .= '<a href="manage-cron.php?path=' . $node['path'] . '"><i class="ion-ios-settings"></i></a>';
      $output .= '</div>';

      $output .= draw_tree( $node['path'] );
      
      $output .= '</li>';

    } // foreach $nodes
    $output .= '</ul>';

    return $output;
  } //if
}



/************************************************************
*   @function draw_tree_user
*   @description Generate Tree
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return output [string]
************************************************************/
function draw_tree_user( $path ) {
  $nodes = get_tree( $path );
  $ds = directory_separator();

  if( !empty( $nodes ) && is_array( $nodes ) ){
    $output = '';
    $output .= '<ul class="nodes">';
    foreach ( $nodes as $key => $node ) {
      
      $output .= '<li class="node folder" >';

      $output .= '<div class="title" data-path="'.$path.$ds.$node['name'].'">';
      // $output .= '<a href="upload.php?path=' . $path.$ds.$node['name'] . '">';
      $output .= '<a href="uploader.php?path=' . $path.$ds.$node['name'] . '" target="_blank">';
      $output .= $node['name'];
      $output .= '</a>';
      $output .= '</div>';

      // $output .= '<div class="size">';
      // $output .= get_directory_size( $node['path'] );
      // $output .= '</div>';

      // $output .= '<div class="items">';
      // $output .= count( $node['items'] ). ' items';
      // $output .= '</div>';

      $output .= '<div class="actions">';
      $output .= '<a href="manage-cron.php?path=' . $node['path'] . '"><i class="ion-ios-settings"></i></a>';
      $output .= '</div>';

      $output .= draw_tree_user( $node['path'] );
      
      $output .= '</li>';

    } // foreach $nodes
    $output .= '</ul>';

    return $output;
  } //if
}



/************************************************************
*   @function get_image_detail
*   @description Get image detail
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return return [string]
************************************************************/
function get_image_detail1($path){
  $files = array();

  $exif = exif_read_data($path, 0, true);
  
  foreach ($exif as $key => $section) {
      foreach ($section as $type => $val) {
          
          $files[] = array(
            "key" => $key,
            "type" => $type,
            "val" => $val,
            "name" => "$key.$type: $val"); 

      }
  }
  return $files;
}



/************************************************************
*   @function get_image_detail
*   @description Get image detail
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return output [string]
************************************************************/
function get_image_detail($path){
  $files = array();
  $output='';
  $width='';
  $height='';

  $exif = exif_read_data($path, 0, true);
 
  foreach ($exif as $key => $section) {
      foreach ($section as $type => $val) {
                             
          if($type=='FileName'){
            if($type=='0'){
               $quality= (explode(",",$val));
               $output .="<div><span> </span> <span>".$quality[1]."  </span></div><br/>";
            }else{
              $output .="<div><span><br/>".$type." : </span> <span>".$val."  </span></div><br/>";
            }

            
          }elseif($type=='FileDateTime'){
            $output .="<div><span>".$type." : </span> <span>".$val."  </span></div><br/>";
          }elseif($type=='FileSize'){
           $output .="<div><span>".$type." : </span> <span>".$val."  </span></div><br/>"; 
          }elseif($type=='IsColor'){
            $output .="<div><span>".$type." : </span> <span>".$val."  </span></div><br/>";
          }elseif($type=='MimeType'){
           $output.="<div><span>Image Type : </span> <span>".$val."  </span></div><br/>";
          }elseif($type=='Width'){
            $width=$val;
          }elseif($type=='Height'){
            $height=$val;
          }

          if($width!='' && $height!=''){
            $output.="<div><span>Dimension : </span> <span>Width(x)= ".$width.", Height(y)= ".$height." </span></div><br/>";
            $width='';
            $height='';
          }
      }
  }
  echo $output;
}



/************************************************************
*   @function get_image_detail
*   @description Get image detail
*   @access public
*   @since  1.0.0.0
*   @author OXO Solutions®(oxosolutions.com)
*   @return output [string]
************************************************************/
function get_image_detail11($path){
  $files = array();
  $output='';
  $width='';
  $height='';

  $exif = exif_read_data($path, 0, true);
 
  foreach ($exif as $key => $section) {
      foreach ($section as $type => $val) {
                             
          if($type=='FileName'){
            if($type=='0'){
               $quality= (explode(",",$val));
               $output .="<div><span> </span> <span>".$quality[1]."  </span></div><br/>";
            }else{
              $output .="<div><span><br/>".$type." : </span> <span>".$val."  </span></div><br/>";
            }

            
          }elseif($type=='FileDateTime'){
            $output .="<div><span>".$type." : </span> <span>".$val."  </span></div><br/>";
          }elseif($type=='FileSize'){
           $output .="<div><span>".$type." : </span> <span>".$val."  </span></div><br/>"; 
          }elseif($type=='IsColor'){
            $output .="<div><span>".$type." : </span> <span>".$val."  </span></div><br/>";
          }elseif($type=='MimeType'){
           $output.="<div><span>Image Type : </span> <span>".$val."  </span></div><br/>";
          }elseif($type=='Width'){
            $width=$val;
          }elseif($type=='Height'){
            $height=$val;
          }

          if($width!='' && $height!=''){
            $output.="<div><span>Dimension : </span> <span>Width(x)= ".$width.", Height(y)= ".$height." </span></div><br/>";
            $width='';
            $height='';
          }
      }
  }
  echo $output;
}


/************************************************************
*   @function count_files
*   @description Count no of files in a folder
*   @access public
*   @since  1.0.0.0
*   @perm path    [string path of folder]
*   @author OXO Solutions®(oxosolutions.com)
*   @return total_files [number]
************************************************************/

function count_files($path){
  $total_files = 0; 

  $nodes = scan($path);
  if( !empty( $nodes ) && is_array( $nodes ) ){
    foreach ($nodes as $key => $node) {
      if ( $node['type'] == 'file' ) {
        $total_files = $total_files + 1;
        
      }     
    }
  }
  return $total_files;
}