<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Slow TV + Fast Data (Puffins & Wikipedia)</title>
  
  
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

.mw-feed {
  position: absolute;
  display: block;
  z-index: 100;
  left: 10%; right: 10%; top: 10%; bottom: 10%;
  color: #fafafa;
  font-family:"Helvetica",sans-serif;
  text-align: center;
  font-size: 100px;
  font-weight:800;
  letter-spacing: -4px;
  word-break: break-word;
  white-space: normal;
}



</style>
</head>

<body>
  <div class="video-container">
  <iframe src="https://www.youtube.com/embed/oD8kq6xjNNo?rel=0&amp;controls=0&amp;showinfo=0&autoplay=1" frameborder="0" allowfullscreen class="video"></iframe>
  <pre id="feed" class="mw-feed"></pre>
</div>



    
  <!--<span>Messages in the last second: 
  <span id="rate-min">..</span> (average so far: <span id="rate-avg">..</span>/s)</span>-->
  
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script>
    	(function() {
  // See http://socket.io/
  var socket = io.connect('https://stream.wikimedia.org/rc');

  var feedNode = document.getElementById('feed');
  var rateMinNode = document.getElementById('rate-min');
  var rateAvgNode = document.getElementById('rate-avg');
  var errorNode = document.createElement('div');
  errorNode.className = 'alert alert-danger';
  var infoNode = document.createElement('div');
  infoNode.className = 'alert alert-info';
  var updateBuffer = makeDisplayBuffer(1);
  var freq;
  console.log('Connecting...');
  
  socket.on('connect', function() {
    console.log('Connected! Listening for events...');
    
    freq = new Frequency(1000, function(count, average) {
      rateMinNode.textContent = count;
      rateAvgNode.textContent = average;
    });

    // Subscribe to one or more wikis
    // See https://wikitech.wikimedia.org/wiki/RCStream
    socket.emit('subscribe', 'en.wikipedia.org');
  });

  socket.on('change', function(rc) {
    if (freq) {
      freq.add(1);
    }

    // See https://www.mediawiki.org/wiki/Manual:RCFeed#Properties
    if (rc.type == 'edit') {
      printEvent({
        type: 'rc',
        data: rc
      });
    }

  });

  socket.on('error', function(data) {
    // Don't print {isTrusted: true}.  (Is this an error?)
    if (!data.isTrusted) {
      console.log("Error: " + event.data);
      window.reload();
    }
  });

  function printEvent(event) {
    var node;
    if (event.type === 'rc') {

      var wikistream = event.data['title'];

      filterList = ['Draft:', 'Help:', 'User:', 'User talk:', 'File:', 'File talk:', 'Wikipedia:', 'Wikipedia talk:', 'Talk:', 'Template:', 'Template talk:', 'Category:', 'Category talk:'];
      var includesPrefix = function(string) {
        for (var i = 0; i < filterList.length; i++) {
          if (string.includes(filterList[i])) {
            return true;
          }
        }
        return false;
      };

      if (includesPrefix(wikistream)) {
        // do nothing
      } else {
        node = document.createTextNode(wikistream);
        $(feedNode).prepend(node);
        updateBuffer(node);
      }

      /*
      else if (event.type === 'error') {
        $(errorNode).empty().text(JSON.stringify(event.data));
        if (!errorNode.parentNode) {
          $(feedNode).before(errorNode);
        }
      } else if (event.type === 'info') {
        $(infoNode).text(event.message);
        if (!infoNode.parentNode) {
          $(feedNode).prepend(infoNode);
          updateBuffer(infoNode);
        }
      }*/
    }
  }

  function makeDisplayBuffer(size) {
    var buffer = [];
    return function(element) {
      buffer.push(element);
      if (buffer.length > size) {
        var popped = buffer.shift();
        popped.parentNode.removeChild(popped);
      }
    }
  }
}());

function Frequency(interval, callback) {
  var freq = this;
  var rAF = window.requestAnimationFrame || setTimeout;

  this.interval = interval;
  this.callback = callback;
  this.count = 0;
  this.total = 0;
  this.since = this.start = this.now();

  function checker() {
    freq.check();
    rAF(checker);
  }
  rAF(checker);
}
Frequency.prototype.now = (function() {
  var perf = window.performance;
  return perf.now ?
    function() {
      return perf.now();
    } :
    function() {
      return +new Date();
    };
}());
Frequency.prototype.add = function(count) {
  this.count += count;
  this.total += count;
  this.check();
};
Frequency.prototype.check = function() {
  var count, avg, ellapsedTotal;
  var ellapsed = this.now() - this.since;
  if (ellapsed >= this.interval) {
    ellapsedTotal = this.now() - this.start;
    count = this.count;
    // One optional digit
    avg = (this.total / (ellapsedTotal / this.interval)).toFixed(1).replace('.0', '');
    this.since = this.now();
    this.count = 0;
    this.callback(count, avg);
  }
};
    	
    </script>

</body>
</html>
