function runScript(scriptUrl) {
  var head= document.getElementsByTagName('head')[0];
  var script= document.createElement('script');
  script.type= 'text/javascript';
  script.src= scriptUrl;
  head.appendChild(script);
}

function getInfo(data) {
  console.log('getInfo');
  console.log(data);
  var artist = data['artist'];
  document.getElementById('headerArtist').innerText = artist['name'];
  document.getElementById('artistImage').src = artist.image[3]['#text'];
  document.getElementById('bio').innerHTML = artist.bio.summary;
}

function gettopalbums(data) {
    console.log('gettopalbums');
    var albums = data['topalbums']['album'];
    var resultCount = albums.length;
    var rowCounter = 1;
    for(counter = 1; i < rowCounter; i++) {
        var album = albums[counter - 1];
        if(rowCounter == 1) {
            
        }
    }
}

jQuery(document).ready(function ($) {
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
  var url = lastfm_base + "?method=artist.getinfo&format=json&mbid=" + mbid + "&api_key=" + lastfm_key + "&callback=getInfo";
  var url2 = lastfm_base + "?method=artist.gettopalbums&format=json&limit=12&mbid=" + mbid + "&api_key=" + lastfm_key + "&callback=gettopalbums";
  runScript(url);
  runScript(url2);
});