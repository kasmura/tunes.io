<?php
$artistID = intval($_GET['id']);
$url = "http://itunes.apple.com/lookup?id=" . $artistID . '&entity=album';
$json = file_get_contents($url);
$data = json_decode($json, true);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
        <style>
            body {
                padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            }
        </style>
        <title>BitcoinTunes</title>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href="#">BitcoinTunes</a>
                  <div class="nav-collapse">
                    <ul class="nav">
                      <li class="active"><a href="#">Home</a></li>
                    </ul>
                  </div><!--/.nav-collapse -->
                </div>
              </div>
            </div>
        
        <div class="container">
            <h1>Bon Iver</h1>
            <br />
            <div class="row-fluid">
                <div class="span5">
                    <div class="well">
                        <h3>Top albums</h3>
                        <br />
                        <?php
                        $resultCount = intval($data['resultCount']);
                        $rowCounter = 1;
                        for ($counter = 1; $counter < $resultCount; $counter += 1) {
                           $album = $data['results'][$counter];
                           if($rowCounter == 1)
                               echo "<div class='row-fluid'>";
                        ?>
                        <div class="span4">
                             <img style="border: 1px solid" src="<?php echo $album['artworkUrl100'] ?>" />
                             <br /><a href="album.php?id=<?php echo $album['collectionId'] ?>"><?php echo $album['collectionName']; ?></a>
                        </div>  
                        <?php
                           if($rowCounter == 3) {
                               echo "</div><br />";
                               $rowCounter = 1;
                           } else {
                               $rowCounter += 1;
                           }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/jquery.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-transition.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-alert.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-modal.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-scrollspy.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tab.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-popover.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-button.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-collapse.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-carousel.js"></script>
        <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-typeahead.js"></script>
    </body>
</html>