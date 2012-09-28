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

function queryFenopy(query) {
console.log(query);
$.getJSON("http://query.yahooapis.com/v1/public/yql", {
    q: "select * from json where url=\"http://fenopy.eu/module/search/api.php?keyword=" + query + "&sort=peer&format=json&limit=3&category=1\"",
    format: "json"
}, function(data) {
    if (data.query.results) {
        fenopyResult(data.query.results.json['json']);
    } else {
	fenopyResult([]);        
    }
})
}

function fenopyResult(torrentResults) {
  var torrents = [];
  for(i in torrentResults) {
      console.log(torrentResults[i]['name']);
      if(torrentResults[i]['name'].toLowerCase().indexOf(album['name'].toLowerCase()) !== -1) {
          torrents.push(torrentResults[i]);
      }
  }
  console.log(torrents.length);
  if(torrents.length > 0) {
      document.getElementById('downloadButton').innerHTML = '<i class="icon-download-alt icon-white"></i> Download album';
      document.getElementById('downloadButton').className += " btn-success";
      document.getElementById('dropdownToggle').className += " btn-success";
      document.getElementById('downloadButton').href = torrents[0]['magnet'];
      document.getElementById('dropdownTorrent').href = 'http://torcache.net/torrent/' + torrents[0]['hash'].toUpperCase() + '.torrent';
      document.getElementById('dropdownMagnet').href = torrents[0]['magnet'];
      
      for(i in torrents) {
          var torrent = torrents[i];
          
          var tr = document.createElement('tr');
          var td0 = document.createElement('td');
          td0.innerHTML = '<i class="icon-magnet"></i>';
          
          var td1 = document.createElement('td');
          td1.innerHTML = '<a style="color: #0088cc;" href="' + torrent['magnet'] + '" rel="tooltip" data-original-title="' + torrent['name'] + '">Download</a>';
          
          var td2 = document.createElement('td');
          td2.innerHTML = '<td><i class="icon-hdd"></i> ' + roundNumber(torrent['size']/1000000, 2) + ' MB</td>';
          
          var td3 = document.createElement('td');
          td3.innerHTML = '<span rel="tooltip" data-original-title="' + torrent['seeder'] + ' seeders"><i class="icon-chevron-down"></i> ' + torrent['seeder'] + '</span>';
          
          var td4 = document.createElement('td');
          td4.innerHTML = '<span rel="tooltip" data-original-title="' + torrent['leecher'] + ' leechers"><i class="icon-chevron-up"></i> ' + torrent['leecher'] + '</span>';
          
          var td5 = document.createElement('td');
          if(torrent['verified'] == 0)
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
      document.getElementById('torrentTable').innerHTML = '<h3><i>No torrents available</i></h3>';
  }
  jQuery("[rel=tooltip]").tooltip();
  document.getElementById('container').removeAttribute('hidden');
}

function queryResult(data) {
  album = data['album'];
  
  queryFenopy(encodeURI(album['artist'] + " " + album['name']).replace('&', 'and'));
  
  document.getElementById('albumName').innerText = album['name'];
  document.getElementById('albumArtist').innerText = album['artist'];
  document.getElementById('albumArtist').href = "artist.php?mbid=" + album['tracks']['track'][0]['artist']['mbid'];
  document.getElementById('albumCover').src = album['image']['3']['#text'];
  if(album['wiki']) {
     document.getElementById('wiki').innerHTML = album['wiki']['summary']; 
  }
  for(var i = 0; i < album['tracks']['track'].length; i++) {
    var tr = document.createElement('tr');
    var td1 = document.createElement('td');
    td1.innerText = i + 1;
    
    var td2 = document.createElement('td');
    td2.innerText = album['tracks']['track'][i]['name'];
    
    var td3 = document.createElement('td');
    td3.innerText = album['tracks']['track'][i]['artist']['name'];
    
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
    tr.appendChild(td3);
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
