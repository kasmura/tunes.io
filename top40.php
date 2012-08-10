<?php
$url = "http://itunes.apple.com/us/rss/topalbums/limit=40/json";
$json = file_get_contents($url);
$data = json_decode($json, true);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <style>
      body {
        padding-top: 40px;
        background-image: url('background.jpg');
      }
      hr {
        border: 0;
        color: black;
        background-color: black;
        height: 1px;
      }
    </style>
    <title>Top albums - tunes.io</title>
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
                    <ul class="nav">
            <li class="active"><a href="top40.php">Top 40</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container" style="padding: 0px; width:100%; border-radius: 6px; padding: 0px; margin-bottom: 0px;">
        <table cellpadding="0">
            <?php
            $rowCounter = 1;
            for ($counter = 1; $counter < 41; $counter += 1) {
              $entry = $data['feed']['entry'][$counter - 1];
              if($rowCounter == 1)
                echo "<tr>";
            ?>
            <td>
              <a class="highlightit" href="search.php?q=<?php echo urlencode($entry['im:artist']['label']) . "+" . urlencode($entry['im:name']['label']); ?>">
                <img style="border:0px" src="<?php echo $entry['im:image'][2]['label'] ?>" />
              </a>
            </td>
            <?php
              if($rowCounter == 8) {
                echo "</tr>";
                $rowCounter = 1;
              } else {
                $rowCounter += 1;
              }
            }
            ?>
        </table>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
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
