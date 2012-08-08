<?php
//$mbid = "0270cde6-6b5b-31fa-b04b-d8b68ff612d4";
$mbid = $_GET['mbid'];

$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=1c9e774e852e2297ffb4103df42e8121&mbid=$mbid&format=json";
$json = file_get_contents($url);
$data = json_decode($json, true);
$album = $data['album'];

$fenopyQuery = str_replace(' ', '+', $album['artist']) . "+" . str_replace(' ', '+', $album['name']);
$url2 = "http://fenopy.eu/module/search/api.php?keyword=$fenopyQuery&sort=relevancy&format=json&limit=3&category=1";
$json2 = file_get_contents($url2);
$torrents = json_decode($json2, true);
$topTorrent = $torrents[0];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px;
        background-image: url('background.jpg');
      }
    </style>
    <title>tunes.io</title>
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
      <div class="row-fluid">
        <div class="span4">
          <h1><i><?php echo $album['name'] ?></i></h1>
          <h2>by <?php echo $album['artist'] ?></h2>
          <br />
          <img style="border: 2px solid" src="<?php echo $album['image']['4']['#text'] ?>" />
          <br /><br />
          <div class="btn-group">
            <button class="btn btn-large  btn-success dropdown-toggle" data-toggle="dropdown">
              <i class="icon-download-alt icon-white"></i> Download album 
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="http://torcache.net/torrent/<?php echo $torrents[0]['hash'] ?>.torrent">Torrent file</a></li>
              <li><a href="<?php echo $torrents[0]['magnet'] ?>">Magnet link</a></li>
            </ul>
          </div>
          <br />
        </div>
        <div class="span8">
          <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li class="active"><a href="#torrents" data-toggle="tab">Torrents</a></li>
            <li><a href="#tracks" data-toggle="tab">Tracks</a></li>
          </ul>
          <div id="my-tab-content" class="tab-content">
            <div class="tab-pane active" id="torrents">
              <table class="table">
                <?php
                  foreach($torrents as $torrent) {
                ?>
                <tr>
                  <td><i class="icon-magnet"></i> <a href="<?php echo $torrent['magnet'] ?>" rel="tooltip" data-original-title="<?php echo $torrent['name'] ?>">Download</a></td>
                  <td><i class="icon-hdd"></i> <?php echo round($torrent['size']/1000000,2) ?> MB</td>
                  <td><i class="icon-chevron-down"></i> <?php echo $torrent['seeder'] ?></td>
                  <td><i class="icon-chevron-up"></i> <?php echo $torrent['leecher'] ?></td>
                  <td>
                    <?php
                      if($torrent['verified'] == 0)
                        echo '<i class="icon-question-sign"></i> not verified';
                      else
                        echo '<i class="icon-ok-sign"></i> verified';
                    ?>
                  </td>
                </tr>
                <?php
                  }
                ?>
              </table>
            </div>
            <div class="tab-pane" id="tracks">
              <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Artist</th>
                    <th>Time</th>
                  </tr>
                </thead>
               <tbody>
                 <?php
                   $counter = 0;
                   foreach($album['tracks']['track'] as $track) {
                     $counter += 1;
                 ?>
                 <tr>
                   <td><?php echo $counter; ?></td>
                   <td><?php echo $track['name'] ?></td>
                   <td><?php echo $track['artist']['name'] ?></td>
                   <td>
                     <?php
                       $seconds = $track['duration'];
                       $minutes = floor($seconds/60);
                       $secondsleft = $seconds%60;
                       if($minutes<10)
                         $minutes = $minutes;
                       if($secondsleft<10)
                         $secondsleft = "0" . $secondsleft;
                       echo "$minutes:$secondsleft";
                     ?>
                   </td>
                 </tr>
                 <?php
                   }
                 ?>
               </tbody>
             </table>
           </div>
         </div>   
      </div>
    </div>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
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
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
      $('#tabs').tab();
      $("[rel=tooltip]").tooltip();
    });
    </script>
  </body>
</html>
