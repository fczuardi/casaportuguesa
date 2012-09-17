var http = require('http');
var querystring = require('querystring');


var content = [
    {
        "subscription_id": "1",
        "object": "user",
        "object_id": "itatibafoo",
        "changed_aspect": "media",
        "time": 1297286541
    },
    {
        "subscription_id": "2",
        "object": "tag",
        "object_id": "itatibabar",
        "changed_aspect": "media",
        "time": 1297286541
    }
];

var options = {
  host: 'localhost',
  port: 8888,
  path: '/mnmo/casaportuguesa/instagram_callback.php',
  /*
  host: 'umacasaportuguesacomcerteza.com',
  port: 80,
  path: '/instagram_callback.php',
  */

  method: 'POST'
};

var req = http.request(options, function(res) {
  console.log('STATUS: ' + res.statusCode);
  console.log('HEADERS: ' + JSON.stringify(res.headers));
  res.setEncoding('utf8');
  res.on('data', function (chunk) {
    console.log('BODY: ' + chunk);
  });
});

req.on('error', function(e) {
  console.log('problem with request: ' + e.message);
});

req.write(JSON.stringify(content));
req.end();