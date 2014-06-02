var mbid;
var album;

function runScript(scriptUrl) {
  var head= document.getElementsByTagName('head')[0];
  var script= document.createElement('script');
  script.type= 'text/javascript';
  script.src= scriptUrl;
  head.appendChild(script);
}

function roundNumber(num, dec) {
  var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
  return result;
}

function queryTorrentz(query) {

  $.getJSON("http://query.yahooapis.com/v1/public/yql", {
      q: "select * from html where url=\"https://torrentz.eu/search?f=" + query + "\"",
      format: "json"
  }, function(data) {
    formatResults(data.query.results.body.div[2].dl);
  })
}

function formatResults(results) {
  console.log(results);
    var torrents = [];
    if (results.constructor !== Array) {
      if (results['dt'].content.indexOf('DMCA') == -1) {
	torrents.push(makeTorrentInfo(results));
      }
    } else {
    if (results.length > 3) {
      results = results.splice(0,3);
    }
    if(results[results.length - 1]['dt'].content.indexOf('DMCA') != -1) {
	  results.splice(results.length - 1, 1);
    }
    if (results.length == 1) {
      torrents.push(makeTorrentInfo(results[0]));
    } else if(results.length == 2 || results.length == 3) {
      for (var i = 0; i < results.length; i++) {
	  var torrent = {}
	  torrent['hash'] = results[i].dt.a.href.replace('/', '')
	  if (results[i].dt.a.content) {
	      var name = results[i].dt.a.content
	      if (results[i].dt.a.strong.constructor == Array) {
		for (var j = 0; j < results[i].dt.a.strong.length; j++) {
		  name = name.replace('\n', results[i].dt.a.strong[j]);
		}
	      } else {
		name = name.replace('\n', results[i].dt.a.strong);
	      }
	      torrent['name'] = name.replace(/ {2,}/g, ' ')
	  } else {
	      torrent['name'] = results[i].dt.a.strong.join(' ')
	  }
	  if (results[i].dd.span.length == 5) {
	      torrent['size'] = results[i].dd.span[2].content
	      torrent['seeders'] = results[i].dd.span[3].content
	      torrent['leechers'] = results[i].dd.span[4].content
	      torrent['verified'] = true;
	  } else if(results[i].dd.span.length == 4) {
	      torrent['size'] = results[i].dd.span[1].content
	      torrent['seeders'] = results[i].dd.span[2].content
	      torrent['leechers'] = results[i].dd.span[3].content
	      torrent['verified'] = false;
	  }
	  torrents.push(torrent);
      }
    } else if(numberoftorrents == 0) {
      var torrent = {}
	  torrent['hash'] = results.dt.a.href.replace('/', '')
	  if (results.dt.a.content) {
	      var name = results.dt.a.content
	      for (var j = 0; j < results.dt.a.strong.length; j++) {
		  name = name.replace('\n', results.dt.a.strong[j]);
	      }
	      torrent['name'] = name.replace(/ {2,}/g, ' ')
	  } else {
	      torrent['name'] = results[i].dt.a.strong.join(' ')
	  }
	  if (results.dd.span.length == 5) {
	      torrent['size'] = results.dd.span[2].content
	      torrent['seeders'] = results.dd.span[3].content
	      torrent['leechers'] = results.dd.span[4].content
	      torrent['verified'] = true;
	  } else if(results.dd.span.length == 4) {
	      torrent['size'] = results.dd.span[1].content
	      torrent['seeders'] = results.dd.span[2].content
	      torrent['leechers'] = results.dd.span[3].content
	      torrent['verified'] = false;
	  }
	  torrents.push(torrent);     
    }
    }
    inputResult(torrents)
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
    torrent['name'] = name.replace(/ {2,}/g, ' ')
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

function inputResult(torrentResults) {
  var torrents = [];
  console.log(torrentResults);
  for(i in torrentResults) {
      var contains = album['name'].toLowerCase().replace(/[.,()]/g, '');
      if (contains.length > 30) {
	contains = contains.substring(0,20);
      }
      if(torrentResults[i]['name'].toLowerCase().replace(/[.,()]/g, '').indexOf(contains) !== -1) {
	
          torrents.push(torrentResults[i]);
      }
  }
  if(torrents.length > 0) {
      document.getElementById('downloadButton').innerHTML = '<i class="icon-download-alt icon-white"></i> Download album';
      document.getElementById('downloadButton').className += " btn-success";
      document.getElementById('dropdownToggle').className += " btn-success";
      document.getElementById('downloadButton').href = "magnet:?xt=urn:btih:" + torrents[0]['hash'] + "&dn=" + torrents[0]['name'].replace(/ /g, '+')
      document.getElementById('dropdownTorrent').href = 'http://torcache.net/torrent/' + torrents[0]['hash'].toUpperCase() + '.torrent';
      document.getElementById('dropdownMagnet').href = "magnet:?xt=urn:btih:" + torrents[0]['hash'] + "&dn=" + torrents[0]['name'].replace(/ /g, '+')
      
      for(i in torrents) {
          var torrent = torrents[i];
          
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
      document.getElementById('downloadButton').innerHTML = '<i class="icon-download-alt icon-white"></i> Not available';
      document.getElementById('downloadButton').className += " btn-warning";
      document.getElementById('dropdownToggle').className += " btn-warning";
      document.getElementById('dropdropmenu').hidden = true;
      var searchGoogle = 'http://www.google.com/search?q=' + album['name'].replace(/ /g, '+') + '+' + album['artist'].replace(/ /g, '+') + '+torrent';
      document.getElementById('torrentTable').innerHTML = '<h3><i>No torrents available</i></h3><a class="btn btn-info" href="' + searchGoogle + '">Search Google<a/>';
      
  }
  jQuery("[rel=tooltip]").tooltip();
  document.getElementById('container').removeAttribute('hidden');
  document.getElementById('loadergif').hidden = true;
}

function queryResult(data) {
  album = data['album'];
  
  queryTorrentz(encodeURI(album['artist'] + " " + album['name']).replace('&', 'and'));
  
  document.getElementById('albumName').innerText = album['name'];
  document.title = album['name'];
  document.getElementById('albumArtist').innerText = album['artist'];
  //document.getElementById('albumArtist').href = "artist.php?mbid=" + album['tracks']['track'][0]['artist']['mbid'];
  document.getElementById('albumCover').src = album['image']['3']['#text'];
  if(album['wiki']) {
    document.getElementById('wiki').innerHTML = album['wiki']['summary']; 
  } else {
    document.getElementById('wikispace').hidden = true;
  }
  if (album['tracks']['track'].constructor !== Array) {
    var trackArray = [album['tracks']['track']];
    album['tracks']['track'] = trackArray;
  }
  for(var i = 0; i < album['tracks']['track'].length; i++) {
    var tr = document.createElement('tr');
    var td1 = document.createElement('td');
    td1.innerText = i + 1;
    
    var td2 = document.createElement('td');
    td2.innerText = album['tracks']['track'][i]['name'];
    
    
    var seconds = album['tracks']['track'][i]['duration'];
    var minutes = Math.floor(seconds/60);
    var secondsleft = seconds%60;
    if(minutes < 10)
      minutes = minutes;
    if(secondsleft<10)
      secondsleft = "0" + secondsleft;
    var duration = minutes + ':' + secondsleft;
    
    var td4 = document.createElement('td');
    td4.innerText = duration;
    
    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td4);
    document.getElementById('trackList').appendChild(tr);
  }
}

jQuery(document).ready(function ($) {
  $('#tabs').tab();
  $("[rel=tooltip]").tooltip();
  
  var lastfm_base = "http://ws.audioscrobbler.com/2.0/";
  var lastfm_key = "1c9e774e852e2297ffb4103df42e8121";
  var prmstr = window.location.search.substr(1);
  var prmarr = prmstr.split ("&");
  var params = {};

  for ( var i = 0; i < prmarr.length; i++) {
    var tmparr = prmarr[i].split("=");
    params[tmparr[0]] = tmparr[1];
  }

  mbid = decodeURI(params['mbid'].trim());
  var url = lastfm_base + "?method=album.getinfo&api_key=" + lastfm_key + "&mbid=" + mbid + "&format=json&callback=queryResult";
  runScript(url);
});
