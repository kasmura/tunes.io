    var query;

    function runScript(scriptUrl) {
      var head= document.getElementsByTagName('head')[0];
      var script= document.createElement('script');
      script.type= 'text/javascript';
      script.src= scriptUrl;
      head.appendChild(script);
    }

    function searchOutput(data) {
        var totalResults = 0;
        var results = data['results'];
        var opensearchTotalResults = results['opensearch:totalResults'];
        document.getElementById('herounit').innerHTML = '';
        var queryWithoutPlus = query;
        while (queryWithoutPlus.indexOf('+') != -1) {
          queryWithoutPlus = queryWithoutPlus.replace('+', ' ');
        }
        if (results['albummatches'] == '\n') {
          document.getElementById('herounit').innerHTML += '<h3><span id="results"></span>No results for "' + queryWithoutPlus + '"</h3>';
        } else {
          document.getElementById('herounit').innerHTML += '<h3><span id="results"></span> results for "' + queryWithoutPlus + '"</h3><br /><table id="resultsTable" class="table table-condensed"></table>';
          for(var i = 0; i < results['albummatches']['album'].length; i++) {
              if(results['albummatches']['album'][i]['mbid']) {
                  totalResults++;
                  var resultsTable = document.getElementById('resultsTable');
                  var tr = document.createElement('tr');
                  var td0 = document.createElement('td');
                  td0.width = "40";
                  var td0text = '<img width="34" height="34" src="' + results['albummatches']['album'][i]['image'][0]['#text'] + '" />';
                  console.log(td0text);
                  td0.innerHTML = td0text;
                  var td1 = document.createElement('td');
                  var td1text = "<h4><a href='album.html?mbid=" + results['albummatches']['album'][i]['mbid'] + "'>" + results['albummatches']['album'][i]['name'] + "</a></h4>";
                  td1.innerHTML = td1text;
                  var td2 = document.createElement('td');
                  td2.width = "40%";
                  var td2text = "<h4>" + results['albummatches']['album'][i]['artist'] + "</h4>";
                  td2.innerHTML = td2text;
                  tr.appendChild(td0);
                  tr.appendChild(td1);
                  tr.appendChild(td2);
                  resultsTable.appendChild(tr);
              }
          }
          document.getElementById('results').innerText = totalResults;
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

        query = decodeURI(params['q'].trim().replace('(Deluxe Version)', '').replace('(Deluxe Edition)', ''));
        var url = lastfm_base + "?method=album.search&album=" + query.replace(' ', '+') + "&limit=100&format=json&api_key=" + lastfm_key + "&callback=searchOutput";
        var opensearchTotalResults = 0;
        runScript(url);
    });