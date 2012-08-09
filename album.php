<?php
//$mbid = "0270cde6-6b5b-31fa-b04b-d8b68ff612d4";
$mbid = $_GET['mbid'];
$apiKey = "1c9e774e852e2297ffb4103df42e8121";

$url = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key=1c9e774e852e2297ffb4103df42e8121&mbid=$mbid&format=json";
$json = file_get_contents($url);
$data = json_decode($json, true);
$album = $data['album'];

$fenopyQuery = str_replace(' ', '+', $album['artist']) . "+" . str_replace(' ', '+', $album['name']);
$url2 = "http://fenopy.eu/module/search/api.php?keyword=$fenopyQuery&sort=relevancy&format=json&limit=3&category=1";
$json2 = file_get_contents($url2);
$torrentResults = json_decode($json2, true);

$torrents = array();
print_r($stack);
foreach($torrentResults as $torrentResult) {
  if (strpos(strtolower($torrentResult['name']), strtolower($album['name'])) !== false) {
    array_push($torrents, $torrentResult);
  }
}
$someResults = false;
if(count($torrents) > 0) {
  $someResults = true;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <style>
      body {
        padding-top: 80px;
        background-image: url('background.jpg');
      }
      .icon-itunes {
        background-image: url("itunes.png");
        background-position: center center;
      }
      hr {
        border: 0;
        color: black;
        background-color: #555555;
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
    <div class="container" style="background-color: #eeeeee; padding: 20px; width: 70%; border-radius: 6px; padding: 60px; margin-bottom: 30px;">
      <div class="row-fluid">
        <div class="span4">
          <h1><i><?php echo $album['name'] ?></i></h1>
          <h2>by <a style="color:inherit;" href="artist.php?mbid=<?php echo $album['tracks']['track'][0]['artist']['mbid'] ?>"><?php echo $album['artist'] ?></a></h2>
          <br />
          <img style="border: 2px solid" src="<?php echo $album['image']['3']['#text'] ?>" />
          <br /><br />
          <?php
          if($someResults == true) {
          ?>
          <div class="btn-group">
            <button class="btn btn-large btn-success dropdown-toggle" data-toggle="dropdown">
              <i class="icon-download-alt icon-white"></i> Download album 
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li><a href="http://torcache.net/torrent/<?php echo $torrents[0]['hash'] ?>.torrent"><i class="icon-file"></i> Torrent file</a></li>
              <li><a href="<?php echo $torrents[0]['magnet'] ?>"><i class="icon-magnet"></i> Magnet link</a></li>
              <?php
              $url3 = "http://ws.audioscrobbler.com/2.0/?method=album.getbuylinks&mbid=$mbid&country=united+states&format=json&api_key=$apiKey";
              $json3 = file_get_contents($url3);
              $data3 = json_decode($json3, true);
              $iTunesLink = "";
              foreach($data3['affiliations']['downloads']['affiliation'] as $affiliation) {
                  if($affiliation['supplierName'] == 'iTunes') {
                      $iTunesLink = $affiliation['buyLink'];
                  }
              }
              if($iTunesLink !== "") {
              ?>
              <li class="divider"></li>
              <li><a target="_blank" href="<?php echo $iTunesLink ?>"><i class="icon-itunes"></i> Buy on iTunes</a></li>
              <?php
              }
              ?>
              <!--<li><a target="_blank" href="http://www.last.fm/affiliate/byid/8/2427924/44/ws.album.buylinks.b25b959554ed76058ac220b7b2e0a026"><i class="icon-amazon"></i> Buy on Amazon</a></li>-->
            </ul>
          </div>
          <?php
          } else {
          ?>
          <button class="btn btn-large btn-warning disabled" disabled="disabled">Not available</button>
          <?php
          }
          ?>
        </div>
        <div class="span8">
          <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
            <li class="active"><a href="#torrents" data-toggle="tab">Torrents</a></li>
            <li><a href="#tracks" data-toggle="tab">Tracks</a></li>
          </ul>
          <div id="my-tab-content" class="tab-content">
            <div class="tab-pane active" id="torrents">
              <?php
              if($album['wiki']['summary']) {
                  echo $album['wiki']['summary'];
                  echo "<br /><br />";
              }
              ?>
                
              <table class="table">
                <?php
                  if($someResults == true) {
                    foreach($torrents as $torrent) {
                ?>
                <tr>
                  <td><i class="icon-magnet"></i> <a style="color: #0088cc;" href="<?php echo $torrent['magnet'] ?>" rel="tooltip" data-original-title="<?php echo $torrent['name'] ?>">Download</a></td>
                  <td><i class="icon-hdd"></i> <?php echo round($torrent['size']/1000000, 2) ?> MB</td>
                  <td><span rel="tooltip" data-original-title="<?php echo $torrent['seeder'] ?> seeders"><i class="icon-chevron-down"></i> <?php echo $torrent['seeder'] ?></span></td>
                  <td><span rel="tooltip" data-original-title="<?php echo $torrent['leecher'] ?> leechers"><i class="icon-chevron-up"></i> <?php echo $torrent['leecher'] ?></span></td>
                  <td>
                    <?php
                      if($torrent['verified'] == 0) {
                        echo '<i class="icon-question-sign"></i> not verified';
                      } else {
                        echo '<i class="icon-ok-sign"></i> verified';
                      }
                    ?>
                  </td>
                </tr>
                <?php
                    }
                  } else {
                ?>
                <h3><i>No torrents available</i></h3>
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
    <script src="http://twitter.github.com/bootstrap/assets/js/jquery.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tab.js"></script>
    <script src="http://twitter.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
      $('#tabs').tab();
      $("[rel=tooltip]").tooltip();
    });
    </script>
  </body>
</html>
