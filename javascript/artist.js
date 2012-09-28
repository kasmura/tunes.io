function runScript(scriptUrl) {
  var head= document.getElementsByTagName('head')[0];
  var script= document.createElement('script');
  script.type= 'text/javascript';
  script.src= scriptUrl;
  head.appendChild(script);
}

function getInfo(data) {
  console.log('getInfo');
  var artist = data['artist'];
}

function gettopalbums(data) {
    console.log('gettopalbums');
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