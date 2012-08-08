<?php
$query = $_GET['q'];
$url = "http://ws.audioscrobbler.com/2.0/?method=album.search&album=" . str_replace(' ', '+', $query) . "&limit=10&format=json&api_key=1c9e774e852e2297ffb4103df42e8121";
$json = file_get_contents($url);
$data = json_decode($json, true);
$results = $data['results'];
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
        <div class="container" style="width:70%">
          <div class="hero-unit">
            <h3><?php echo $results['opensearch:totalResults'] ?> results for "<?php echo $query; ?>"</h3>
            <br />
            <table class="table">
              <?php
              foreach($results['albummatches']['album'] as $album) {
                if($album['image'][1]['#text'] && $album['mbid'] && strlen($album['name']) < 50) {
              ?>
              <tr>
                  <td><img src="<?php echo $album['image'][1]['#text']; ?>" /></td>
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
    $("[rel=tooltip]").tooltip();
</script>
    </body>
</html>
