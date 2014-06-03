    var globalChart;
    function chart(data) {
      globalChart = data;
      var rowCounter = 0;
      var chartTable = document.getElementById('chartTable');
      for(var j = 0; j < 5; j++) {
        var tr = document.createElement('tr');
        for(var i = 0; i < 8; i++) {
            // 9 1 1
            var entry = data['feed']['entry'][j * 8 + i];
            var td = document.createElement('td');

            var a = document.createElement('a');
            var rank = j * 8 + i;
            a.innerHTML = '<a class="highlightit" data-toggle="modal" onclick="findTorrent(' + rank + ')" href="#about"><img style="border:0px" src="'
            + entry['im:image'][2]['label'] + '" /></a>';

            td.appendChild(a);
            tr.appendChild(td);
        }
        chartTable.appendChild(tr);
      }
    }
    
    function findTorrent(rank) {
      document.getElementById('torrentResults').innerHTML = "<table class='table' id='torrentTable'></table>";
      var entry = globalChart['feed']['entry'][rank]
      document.getElementById('albumCover').src = entry['im:image'][2]['label'].replace('170x170','400x400')
      document.getElementById('albumTitle').innerHTML = entry['im:name']['label']
      document.getElementById('albumArtist').innerHTML = entry['im:artist']['label'];
      queryTorrentz(entry['im:artist']['label'], entry['im:name']['label'] .replace(/ *\([^)]*\) */g, ""));
    }
    
    
function queryTorrentz(artist, album) {
  var query = encodeURIComponent(artist) + "%20" + encodeURIComponent(album)
  var yqlQuery = "select * from html where url=\"https://torrentz.eu/search?f=" + query + "\"";
  $.getJSON("http://query.yahooapis.com/v1/public/yql", {
      q: yqlQuery,
      format: "json"
  }, function(data) {
    formatResults(data.query.results.body.div[2].dl, album);
  })
}

function formatResults(results, album) {
    var torrents = [];
    if (results.constructor !== Array) {
      if (results['dt'].content.indexOf('DMCA') == -1)
	torrents.push(makeTorrentInfo(results))
    } else {
      if (results.length > 3)
	results = results.splice(0,3);
      if(results[results.length - 1]['dt'].content.indexOf('DMCA') != -1)
	results.splice(results.length - 1, 1);
      if (results.length == 1)
	torrents.push(makeTorrentInfo(results[0]));
      else if(results.length == 2 || results.length == 3) {
	for (var i = 0; i < results.length; i++)
	  torrents.push(makeTorrentInfo(results[i]));
      }
      else if(numberoftorrents == 0)
	torrents.push(makeTorrentInfo(results));     
    }
    inputResult(torrents, album)
}

function makeTorrentInfo(result) {
  var torrent = {}
  torrent['hash'] = result.dt.a.href.replace('/', '')
  if (result.dt.a.content) {
    var name = result.dt.a.content
    if (result.dt.a.strong.constructor == Array) {
      for (var j = 0; j < result.dt.a.strong.length; j++) {
	name = name.replace('\n', result.dt.a.strong[j]);
      }
    } else {
      name = name.replace('\n', result.dt.a.strong);
    }
    torrent['name'] = name.replace(/ {2,}/g, ' ').replace(/ , /g, ', ').replace(/ '/g, "'").replace(/ &/g, "&");
  } else {
    torrent['name'] = result.dt.a.strong.join(' ')
  }
  if (result.dd.span.length == 5) {
    torrent['size'] = result.dd.span[2].content
    torrent['seeders'] = result.dd.span[3].content
    torrent['leechers'] = result.dd.span[4].content
    torrent['verified'] = true;
  } else if(result.dd.span.length == 4) {
    torrent['size'] = result.dd.span[1].content
    torrent['seeders'] = result.dd.span[2].content
    torrent['leechers'] = result.dd.span[3].content
    torrent['verified'] = false;
  }
  return torrent;
}
    
function inputResult(torrentResults, album) {
  var torrents = [];
  console.log(torrentResults);
  for(i in torrentResults) {
      var contains = album.toLowerCase().replace(/[.,()]/g, '');
      if (contains.length > 30) {
	contains = contains.substring(0,20);
      }
      if(torrentResults[i]['name'].toLowerCase().replace(/[.,()]/g, '').indexOf(contains) !== -1) {
	
          torrents.push(torrentResults[i]);
      }
  }
  if(torrents.length > 0) {    
      for(i in torrents) {
          var torrent = torrents[i];
          
          if (torrent['seeders'] == undefined)
            continue;
          
          var tr = document.createElement('tr');
          var td0 = document.createElement('td');
          td0.innerHTML = '<i class="icon-magnet"></i>';
          
          var td1 = document.createElement('td');
          td1.innerHTML = '<a style="color: #0088cc;" href="magnet:?xt=urn:btih:' + torrent['hash'] + '&dn=' + torrent['name'].replace(/ /g, '+') + '" rel="tooltip" data-original-title="' + torrent['name'] + '">Download</a>';
          
          var td2 = document.createElement('td');
          td2.innerHTML = '<td><i class="icon-hdd"></i> ' + (torrent['size']) + '</td>';
          
          var td3 = document.createElement('td');
          td3.innerHTML = '<span rel="tooltip" data-original-title="' + torrent['seeders'] + ' seeders"><i class="icon-chevron-up"></i> ' + torrent['seeders'] + '</span>';
          
          var td4 = document.createElement('td');
          td4.innerHTML = '<span rel="tooltip" data-original-title="' + torrent['leechers'] + ' leechers"><i class="icon-chevron-down"></i> ' + torrent['leechers'] + '</span>';
          
          var td5 = document.createElement('td');
          if(torrent['verified'] == false)
              td5.innerHTML = '<i class="icon-question-sign"></i> not verified';
          else
              td5.innerHTML = '<i class="icon-ok-sign"></i> verified';
          
          tr.appendChild(td0);
          tr.appendChild(td1);
          tr.appendChild(td2);
          tr.appendChild(td3);
          tr.appendChild(td4);
          tr.appendChild(td5);
          document.getElementById('torrentTable').appendChild(tr);
      }
      
  } else {
    document.getElementById('torrentResults').innerHTML = "<a class='btn btn-large btn-warning' disabled='disabled'>No torrents found</a>";
  }
  jQuery("[rel=tooltip]").tooltip();
}