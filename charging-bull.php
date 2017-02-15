<html>
<head>
<style>




body, html{
 height: 100%; 
  background-color: #000; 
  margin: 0;
  padding: 0;
}



.video-container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
}

.video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}


#stock {
  position: absolute;
  display: block;
  z-index: 100;
  left: 10%; right: 10%; top: 10%; bottom: 10%;
  color: #fafafa;
  font-family:"Helvetica",sans-serif;
  text-align: center;
  font-size: 150px;
  font-weight:800;
  letter-spacing: -4px;
  word-break: break-word;
  white-space: normal;
}

#etc{
  display: block;
}

#time, #change, #percent{
  display: inline-block;
  font-size: 24px;
  font-weight: normal;
  color: #fff;
  letter-spacing: 0px;
}

*/

</style>
</head>
<body>


  <div id="stock">
    <div id="etc">
      <div id='result'></div>
      <div id='time'></div>
      <div id='change'></div>
      <div id='percent'></div> 
    </div>
  </div>

<script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<script>
function camViewer()
{var flashOutput;flashOutput='<div class="earthcam-embed-outer" style=""><div class="earthcam-embed-container" style="width:100%; height:auto; background-color:#000000;">'+'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '+'codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=11,6,0,0" '+'width="1400" height="900" '+'id="client_video_player" '+'align="middle">'+'<param name="allowScriptAccess" value="always" />'+'<param name="movie" value="https://www.earthcam.com/swf/streaming/client_video_player.swf?20170119173000" />'+'<param name="quality" value="high" />'+'<param name="bgcolor" value="#000000" />'+'<param name="wmode" value="transparent"> '+'<param name="allowFullScreen" value="true" /> '+'<param name="FlashVars" value="livePath=rtmp://video3.earthcam.com/fecnetwork&amp;liveFileName=chargingbull.flv&amp;rotate=0&amp;image_rotate=0&amp;bufferTime=4&amp;logoPlacement=br&amp;volume=1&showLogo=true&amp;width=1400&amp;height=900&amp;showZoomControls=true&amp;showFS=true&allowDCFS=true&amp;timeout=30&amp;maintainAspectRatio=true&amp;offlineimage=http://static.earthcam.com/cams/includes/images/offline_images/placeholder_bgHD.jpg"> '+'<embed src="https://www.earthcam.com/swf/streaming/client_video_player.swf?20170119173000" '+'FlashVars="livePath=rtmp://video3.earthcam.com/fecnetwork&amp;liveFileName=chargingbull.flv&amp;rotate=0&amp;image_rotate=0&amp;bufferTime=4&amp;logoPlacement=br&amp;volume=1&showLogo=true&amp;width=1400&amp;height=900&amp;showZoomControls=true&amp;showFS=true&allowDCFS=true&amp;timeout=30&amp;maintainAspectRatio=true&amp;offlineimage=http://static.earthcam.com/cams/includes/images/offline_images/placeholder_bgHD.jpg" '+'quality="high" wmode="transparent" bgcolor="#000000" width="1400" height="900" '+'name="client_video_player" align="middle" '+'allowFullScreen="true" '+'allowScriptAccess="always" type="application/x-shockwave-flash" '+'pluginspage="http://www.macromedia.com/go/getflashplayer" />'+'</object></div></div>'+'';document.write(flashOutput);}
camViewer();
  
</script>
</div>


<script>
    $("document").ready(function () {

        //Calling function
        reLoad();

        function reLoad(){
            $.getJSON("https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20%3D%20'^GSPC'&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=", function(data) {
                $("#result").html("$"+ data.query.results.quote.LastTradePriceOnly);
                $("#result").hide().fadeIn(500);
                $("#time").html(""+ data.query.results.quote.LastTradeTime);
                $("#change").html(""+ data.query.results.quote.Change);
                $("#percent").html(""+ data.query.results.quote.ChangeinPercent);
            });
        setTimeout(reLoad,6000);
        };

    });

</script>


  </body>
</html>