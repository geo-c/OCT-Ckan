<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Samuel Navas Medrano">
    <link rel="icon" href="favicon.ico">

    <title>GEO-C</title>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- <script src="assets/js/ie-emulation-modes-warning.js"></script> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="container">
        <div class="header clearfix">
            <nav>
            <!--
              <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a href="#">Servers</a></li>
                <li role="presentation"><a href="http://giv-oct.uni-muenster.de:5000">Dataset</a></li>
              </ul> -->

                <ul class="nav nav-pills pull-right" id="pills">
                    <li class="active"><a role="tab" data-toggle="tab" href="#servers">Servers</a></li>
                    <li id="resources-tab"><a role="tab" data-toggle="tab" href="#resources">Resources</a></li>
                    <li><a href="/dev-corner" target="_blank">Dev-Corner</a></li>
                    <li><a role="tab" style="cursor:no-drop;">Help</a></li>
                </ul>

            </nav>
            <h3 class="text-muted">The Open City Toolkit</h3>
        </div>

      <?php
        include 'get_url.php';
        $online = '<span style="color:green">ONLINE</span>';
        $offline = '<span style="color:red">OFFLINE</span>';
        $run = '<span style="color:green">RUNNING</span>';
        $notrun = '<span style="color:red">NOT RUNNING</span>';

        // We dont need to check GIV-OCT, if this code is running the server is online
        $statusOCT = $online;
        exec ( "ps -e | grep -e apache2 -e postgres -e paster | awk '{print $4}' | sort | uniq 2>&1", $servicesOCT);
        array_unshift($servicesOCT,"avoid first element with index = 0");

        if(@fsockopen ('giv-lodumdata.uni-muenster.de', 80, $errno, $errstr, 10)){
          $statusLOD = $online;
          //$request_url =  'http://giv-lodum.uni-muenster.de/servicesPOST.php';
          $request_url = "http://128.176.146.99/servicesPOST.php";
          $servicesLOD = array("apache2","postgres");
          $servicesLOD = json_decode(curl_post($request_url,$servicesLOD));
          array_unshift($servicesLOD,"avoid first element with index = 0");
        } else {
          $statusLOD = $offline;
          $servicesLOD = [];
        }

        if(@fsockopen ('giv-lodumdata.uni-muenster.de', 80, $errno, $errstr, 10)){
          $statusDAT = $online;
          //$request_url =  'http://giv-lodumdata.uni-muenster.de/servicesPOST.php';
          $request_url = "http://128.176.147.2/servicesPOST.php";
          $servicesDAT = array("apache2","postgres");
          $servicesDAT = json_decode(curl_post($request_url,$servicesDAT));
          array_unshift($servicesDAT,"avoid first element with index = 0");
        } else {
          $statusDAT = $offline;
          $servicesDAT = [];
        }
      ?>

        <div class="tab-content">
            <!-- SERVERS TAB -->
            <div id="servers" class="tab-pane fade in active">
                <div class="row marketing">
                    <div class="col-xs-4">
                        <h4>GIV-OCT</h4>
                        <p><i>giv-oct.uni-muenster.de</i></p>
                        <p><?=$statusOCT?></p> <a href="uptime2.log">Uptime log</a>
                        <h4>Services:</h4>
                        <ul style="list-style-type:square">
                            <?php $apacheOCT = (!array_search('apache2', $servicesOCT) ? $notrun : $run) ?>
                            <li>Apache2: <?=$apacheOCT?></li>
                            <?php $postOCT = (!array_search('postgres', $servicesOCT) ? $notrun : $run) ?>
                            <li>Postgres: <?=$postOCT?></li>
                            <?php $ckanOCT = (!array_search('paster', $servicesOCT) ? $notrun : $run) ?>
                            <li>CKAN: <?=$ckanOCT?></li>
                        </ul><br>
                    </div>

                    <div class="col-xs-4">
                        <h4>GIV-LODUM</h4>
                        <p><i>giv-lodum.uni-muenster.de</i></p>
                        <p><?=$statusLOD?></p>
                        <a href="http://giv-lodum.uni-muenster.de/uptime2.log">Uptime log</a>
                        <h4>Services:</h4>
                        <ul style="list-style-type:square">
                            <?php $apacheLOD = (!array_search('apache2', $servicesLOD) ? $notrun : $run) ?>
                            <li>Apache2: <?=$apacheLOD?></li>
                            <?php $postLOD = (!array_search('postgres', $servicesLOD) ? $notrun : $run) ?>
                            <li>Postgres: <?=$postLOD?></li>
                        </ul><br>
                    </div>

                    <div class="col-xs-4">
                        <h4>GIV-LODUMDATA</h4>
                        <p><i>giv-lodumdata.uni-muenster.de</i></p>
                        <p><?=$statusDAT?></p>
                        <a href="http://giv-lodumdata.uni-muenster.de/uptime2.log">Uptime log</a>
                        <h4>Services:</h4>
                        <ul style="list-style-type:square">
                            <?php $apacheDAT = (!array_search('apache2', $servicesDAT) ? $notrun : $run) ?>
                            <li>Apache2: <?=$apacheDAT?></li>
                            <?php $postDAT = (!array_search('postgres', $servicesDAT) ? $notrun : $run) ?>
                            <li>Postgres: <?=$postDAT?></li>
                        </ul><br>
                    </div>
                </div>
            </div>

            <!-- RESOURCES TAB -->
            <div id="resources" class="tab-pane fade marketing">
                <div class="row marketing" id="results"></div>
                <div class="row marketing">
                    <div id="buttons" class="btn-group" data-toggle="buttons"></div>
                    <div id="urls"></div>
                </div>
            </div>

            <!-- HELP TAB-->
            <div id="help" class="tab-pane fade marketing"></div>

        </div>

        <footer class="footer container">
            <center>
                <a href="http://geo-c.eu/"><img src="http://giv-oct.uni-muenster.de/oct/images/logo-geoc.png" width="145px" height="auto" style="float:left;"></a>
                <div align="right">
                    <a href="https://github.com/geo-c/Open-City-Toolkit"><i class="fa fa-github-square fa-2x"></i></a>
                    <a href="https://twitter.com/geoc_eu"><i class="fa fa-twitter-square fa-2x"></i></a>
                    <a href="https://www.facebook.com/geo.c.opencities"><i class="fa fa-facebook-square fa-2x"></i></a>
                    <span style="position: relative; right: 0; top: 0;">
                        <a href="http://giv-oct.uni-muenster.de:5000/">
                            <i class="fa fa-square fa-2x" style="position: relative; top: 0; left: 0;"></i>
                        </a>
                        <a href="http://giv-oct.uni-muenster.de:5000/">
                            <img src="images/ckan-logo-footer-white.png" width="18px" height="auto" style="position: absolute; top: -6px; left: 3px; pointer-events:none;">
                        </a>
                    </span>
                </div>
            </center>
        </footer>
    </div>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="assets/js/ie10-viewport-bug-workaround.js"></script> -->


    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="js/Parser.js"></script>
    <script src="js/Querier.js"></script>

    <script>
        initParser();
        query("http://giv-oct.uni-muenster.de:5000", "results");
        </script>

    <script>
        if( '<?php echo $ckanOCT; ?>' == '<span style="color:red">NOT RUNNING</span>')
            document.getElementById('resources-tab').innerHTML = '<a role="tab" style="cursor:no-drop;">Resources</a>';
            console.log(document.getElementById('resources-tab'));

            $(function() {
                var hash = window.location.hash;
                // do some validation on the hash here
                //if(hash=="severs" || hash=="resources"){
                $('#pills a[href="'+hash+'"]').tab('show');
                //}
            });
        </script>

    </body>
</html>
