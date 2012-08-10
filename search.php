<?php
require_once 'config.php';
$query = $_GET['q'];
$url = "$LASTFM_BASE?method=album.search&album=" . str_replace(' ', '+', $query) . "&limit=100&format=json&api_key=$LASTFM_KEY";
$json = file_get_contents($url);
$data = json_decode($json, true);
$results = $data['results'];
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
    <div class="container" style="width:70%">
      <div class="hero-unit">
        <h3><span id="results"></span> results for "<?php echo $query; ?>"</h3>
        <br />
        <table class="table table-condensed">
          <?php
          $realResults = 0;
          foreach($results['albummatches']['album'] as $album) {
                if($album['mbid'] && strlen($album['name']) < 50) {
                    $realResults += 1;
              ?>
              <tr>
                  <td><img width="34" height="34" src="<?php echo $album['image'][0]['#text']; ?>" /></td>
                  <td><h3><a href="album.php?mbid=<?php echo $album['mbid']; ?>"><?php echo $album['name']; ?></a></h3></td>
                  <td><h3><?php echo $album['artist']; ?><h3></td>
              </tr>
              <?php
                }
              }
              ?>
        </table>
      </div>
     </div>
     <script src="http://twitter.github.com/bootstrap/assets/js/jquery.js"></script>
     <script type="text/javascript">
     jQuery(document).ready(function ($) {
       $("[rel=tooltip]").tooltip();
       document.getElementById('results').innerHTML = "<?php echo $realResults ?>";
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
