<?php
require_once 'config.php';

$mbid = $_GET['mbid'];
$url = "$LASTFM_BASE?method=artist.getinfo&format=json&mbid=$mbid&api_key=$LASTFM_KEY";
$json = file_get_contents($url);
$data = json_decode($json, true);
$artist = $data['artist'];

$url2 = "$LASTFM_BASE?method=artist.gettopalbums&format=json&limit=12&mbid=$mbid&api_key=$LASTFM_KEY";
$json2 = file_get_contents($url2);
$data2 = json_decode($json2, true);
$albums = $data2['topalbums']['album']
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px;
        background-image: url('background.jpg');
      }
      hr {
        border: 0;
        color: black;
        background-color: black;
        height: 1px;
      }
      A {
        color: inherit;
      }
      A:hover {
        text-decoration: underline;
        color: inherit;
      }
    </style>
    <title><?php echo $artist['name']; ?> - tunes.io</title>
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
          <a class="brand" href="index.php">tunes.io</a>
            <ul class="nav pull-right">
              <form class="navbar-search pull-left" action="search.php">
                <input type="text" name="q" class="search-query span2" placeholder="Search">
              </form>
            </ul>
        </div><!-- /.nav-collapse -->
      </div>
    </div>
    <div class="container" style="background-color: #eeeeee; padding: 20px; width:70%; border-radius: 6px; padding: 60px; margin-bottom: 30px;">
        <?php
        //print_r($albums[0]);
        ?>
        <h1><?php echo $artist['name']; ?></h1>
        <br />
        <div class="row-fluid">
            <div class="span4">
                <img style="border: 1px solid" src="<?php echo $artist['image'][3]['#text'] ?>" />
            </div>
            <div class="span8">
                <p><?php echo $artist['bio']['summary']; ?></p>
            </div>
        </div>
        <br />
        <hr />
        <h2>Albums</h2>
        <br />
        <?php
        $resultCount = count($albums);
        $rowCounter = 1;
        for ($counter = 1; $counter < $resultCount; $counter += 1) {
          $album = $albums[$counter - 1];
          if($rowCounter == 1)
            echo "<div class='row-fluid'>";
          if($album['mbid'] !== "") {
        ?>
        <div class="span2">
          <a href="album.php?mbid=<?php echo $album['mbid'] ?>">
          <img style="border: 1px solid" src="<?php echo $album['image'][2]['#text'] ?>" />
          <br /><?php echo $album['name']; ?></a>
        </div>
        <?php
          }
          if($rowCounter == 6) {
            echo "</div><br />";
            $rowCounter = 1;
          } else {
            $rowCounter += 1;
          }
        }
        ?>
    </div>
    <script src="http://twitter.github.com/bootstrap/assets/js/jquery.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
      $("[rel=tooltip]").tooltip();
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-34026217-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    });
    </script>
  </body>
</html>