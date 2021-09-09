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


// Temporarily increase Execution time
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

// Temporarily increase memory limit
ini_set('memory_limit','512M');

require_once('include/Git.php');

    $repo_path = './';


    $repo = Git::open($repo_path);

    // $remote_url = $repo->run('config remote.origin.url');
    // $remote_url = trim($remote_url);
    // $remote_url = str_replace("sgssandhu:target2024@","",$remote_url);
    // $remote_url = str_replace("https://github.com","https://sgssandhu:target2024@github.com",$remote_url);
    // //echo $remote_url;
    
    // $repo->run('config remote.origin.url "'.$remote_url.'"');
    // $repo->run('config user.name "SGS Sandhu"');
    // $repo->run('config user.email "sgs.sandhu@gmail.com"');
    // $repo->run('config user.password "target2024"');
    // $repo->run('config push.default matching');
    
    $git_add = $repo->add('-A');
    $git_commit = $repo->commit('Update');
    // $git_commit = $repo->commit('Update '.$date);
    $git_status = $repo->run('status');
    // $git_status = $repo->run('config remote.origin.url');

    //$git_status = $repo->run("commit --amend --date='".$date." 09:00:00 2011 +0000' -C HEAD");
    //$git_status = $repo->run("GIT_COMMITTER_DATE="date" git commit --amend --date "date" commit --amend --date='".$date." 09:00:00 2011 +0000' -C HEAD");

    $git_push = $repo->push();

    echo "\r\nInitializing Add\r\n".$git_add;
    echo "\r\nInitializing Commit\r\n".$git_commit;
    echo "\r\nInitializing Status\r\n".$git_status;
    echo "\r\nInitializing Git Push\r\n".$git_push;
    echo "\r\nGit Push Completed";
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