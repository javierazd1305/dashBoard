<?php
ini_set('memory_limit', '-1');
$servername = "127.0.0.1";
$username = "admin";
$password = "1234";
$dbname = "telecom";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "connected";
$sql = "SELECT * FROM registro where minute(fecha) = 48 and hour(fecha) =22 and day(fecha) = 26 and month(fecha)= 7 and year(fecha) = 2016";
$result = $conn->query($sql);
$dataArray = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //echo $row["IMSI"] .",". $row["fecha"] .",". $row["latitud"] .",". $row["longitud"]. "<br>";
        $data = array(
          'type' => 'Feature',
          'geometry' => array(
            'type'=>'Point',
            'coordinates' =>array((float) $row['longitud'],(float)$row['latitud'])
          ),
          'properties'=> array(
            'hora' => $row['fecha']
          )
        );
        //echo json_encode($data);
        array_push($dataArray, $data);
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<html>

<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.36.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.36.0/mapbox-gl.css' rel='stylesheet' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://d3js.org/d3.v4.js"></script>
    <script src="test.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
        }

        .map-overlay {
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            position: absolute;
            width: 25%;
            top: 0;
            left: 0;
            padding: 10px;
            opacity: 0.5;
        }

        .map-overlay .map-overlay-inner {
            background-color: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.20);
            border-radius: 3px;
            padding: 5px;
            margin-bottom: 10px;
        }

        .map-overlay h2 {
            line-height: 10px;
            display: block;
            margin: 0 0 10px;
        }

        .map-overlay .legend .bar {
            height: 10px;
            width: 100%;
            background: linear-gradient(to right, #FCA107, #7F3121);
        }

        .map-overlay input {
            background-color: transparent;
            display: inline-block;
            width: 100%;
            position: relative;
            margin: 0;
            cursor: ew-resize;
        }

                #menu {
                    background: #fff;
                    position: absolute;
                    z-index: 1;
                    top: 10px;
                    right: 10px;
                    border-radius: 3px;
                    width: 120px;
                    border: 1px solid rgba(0, 0, 0, 0.4);
                    font-family: 'Open Sans', sans-serif;
                }

                #menu a {
                    font-size: 13px;
                    color: #404040;
                    display: block;
                    margin: 0;
                    padding: 0;
                    padding: 10px;
                    text-decoration: none;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.25);
                    text-align: center;
                }

                #menu a:last-child {
                    border: none;
                }

                #menu a:hover {
                    background-color: #f8f8f8;
                    color: #404040;
                }

                #menu a.active {
                    background-color: #3887be;
                    color: #ffffff;
                }

                #menu a.active:hover {
                    background: #3074a4;
                }
    </style>
</head>

<body>
   <script type="text/javascript">
        var weekday = new Array(7);
        weekday[0] = "Monday";
        weekday[1] = "Tuesday";
        weekday[2] = "Wednesday";
        weekday[3] = "Thursday";
        weekday[4] = "Friday";
        weekday[5] = "Saturday";
        weekday[6] = "Sunday";
        var months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        var ano = new Date().getFullYear();
        //console.log(ano);
        var anos = []
        for (i = ano - 5; i <= ano; i++) {
            anos.push(i)
        }
    </script>

    <div id='map'></div>
    <nav id="menu"></nav>

    <div class='map-overlay top'>
        <div class='map-overlay-inner'>
            <h2>Telecom Data</h2>
            <p>Minute</p>
            <label id='minute'></label>
            <input id='slider' type='range' min='0' max='59' step='1' value='48' />
        </div>
        <div class='map-overlay-inner'>
            <p id=>Hour</p>
            <label id='hour'></label>
            <input id='slider_hour' type='range' min=0 max=24 step='1' value='22' />
        </div>
        <div class='map-overlay-inner'>
            <p id="day">Day</p>
            <label id='dia'></label>
            <input id='slider_dia' type='range' min=0 max=31 step='1' value='26' />
        </div>
        <div class='map-overlay-inner'>
            <p>Month</p>
            <label id='month'></label>
            <input id='slider_month' type='range' min=0 max=11 step='1' value='7' />
        </div>
        <div class='map-overlay-inner'>
            <p>Year</p>
            <label id='ano'></label>
            <input id='slider_ano' type='range' min=2012 max=2017 step='1' value='2016' />
        </div>
        <div class='map-overlay-inner'>
            <button type="button"  onclick="doFunction();" id="button" class="button">Apply</button>
        </div>
        <script type="text/javascript">
          function doFunction(){
            var minute = document.getElementById("slider").value;
            var hour = document.getElementById("slider_hour").value;
            var day = document.getElementById("slider_dia").value;
            var month = document.getElementById("slider_month").value;
            var year = document.getElementById("slider_ano").value;
            var query = minute + " " + hour + " " + day + " " + month + " " + year
            var query_1 ="WHERE minute(fecha)="+minute
            //console.log(minute + " " + hour + " " + day + " " + month + " " + year);
            $.ajax({
                url: 'test.php',
                type: 'post',
                data: { "callFunc1": 44, "minute":minute,"hour":hour,"day":day,"month":month},
                success: function(response) {
                  //console.log(response);
                  var dataResponse = $.parseJSON('[' + response + ']');
                  data = dataResponse[0];
                  console.log(data.length);
                  idNew = makeid();
                  console.log(idSource);
                  console.log(idNew);
                  map.removeLayer("point");
                  map.removeLayer("cluster-count");
                  map.removeLayer("unclustered-point");
                  idSourceHeat = idNew + "-heat"
                  map.addSource(idNew, {
                      "type": "geojson",
                      "data": {
                          "type": "FeatureCollection",
                          "features": data
                      },
                      cluster: true,
                      clusterMaxZoom: 14, // Max zoom to cluster points on
                      clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
                  });

                  //new

                  map.addSource(idSourceHeat, {
                      "type": "geojson",
                      "data": {
                          "type": "FeatureCollection",
                          "features": data
                      },
                      cluster: true,
                      clusterMaxZoom: 14, // Max zoom to cluster points on
                      clusterRadius: 10 // Radius of each cluster when clustering points (defaults to 50)
                  });
                  var layers = [
                      [0, 'green'],
                      [20, 'orange'],
                      [200, 'red']
                  ];
                  layers.forEach(function(layer, i) {
                      map.addLayer({
                          "id": "cluster-" + i,
                          "type": "circle",
                          "source": idSourceHeat,
                          "paint": {
                              "circle-color": layer[1],
                              "circle-radius": 50,
                              "circle-blur": 1 // blur the circles to get a heatmap look
                          },
                          "filter": i === layers.length - 1 ? [">=", "point_count", layer[0]] : ["all", [">=", "point_count", layer[0]],
                              ["<", "point_count", layers[i + 1][0]]
                          ]
                      }, 'waterway-label');
                  });
                  var toggleableLayerIds = ['cluster-0', 'cluster-1', 'cluster-2'];
                  for (var i = 0; i < toggleableLayerIds.length; i++) {
                    map.setLayoutProperty(toggleableLayerIds[i], 'visibility', 'none');
                  }

                  //new



                  map.addLayer({
                      "id": "point",
                      "type": "circle",
                      "source": idNew,
                      filter: ["has", "point_count"],
                      paint: {
                          "circle-color": {
                              property: "point_count",
                              type: "interval",
                              stops: [
                                  [0, "#51bbd6"],
                                  [100, "#f1f075"],
                                  [750, "#f28cb1"],
                              ]
                          },
                          "circle-radius": {
                              property: "point_count",
                              type: "interval",
                              stops: [
                                  [0, 20],
                                  [100, 30],
                                  [750, 40]
                              ]
                          }
                      }
                  });
                  map.addLayer({
                      id: "cluster-count",
                      type: "symbol",
                      source: idNew,
                      filter: ["has", "point_count"],
                      layout: {
                          "text-field": "{point_count_abbreviated}",
                          "text-font": ["DIN Offc Pro Medium", "Arial Unicode MS Bold"],
                          "text-size": 12
                      }
                  });
                  map.addLayer({
                      id: "unclustered-point",
                      type: "circle",
                      source: idNew,
                      filter: ["!has", "point_count"],
                      paint: {
                          "circle-color": "#11b4da",
                          "circle-radius": 4,
                          "circle-stroke-width": 1,
                          "circle-stroke-color": "#fff"
                      }
                  });
                  //console.log(map.getSource("lala"));
                  map.removeSource(idSource);
                  idSource = idNew;


                }
            });
          }
        </script>
    </div>

    <script>
        var data = <?php echo json_encode($dataArray); ?>;
        //console.log(data);
        console.log(data.length);
        for (element in data){
          //console.log(data[element]);
        }
        function filterBy(minute) {
            var filters = ['==', 'minute', minute]
            //map.setFilter('point', filters)
            document.getElementById('minute').textContent = minute;
        }

        function filterByDia(dia) {
            var filters = ['==', 'dia', dia]
            //map.setFilter('point', filters)
            document.getElementById('dia').textContent = dia;
        }

        function filterByHour(hour) {
            var filters = ['==', 'hour', hour]
            //map.setFilter('point', filters)
            document.getElementById('hour').textContent = hour;
        }

        function filterByMonth(month) {
            var filters = ['==', 'month', month]
            //map.setFilter('point', filters)
            document.getElementById('month').textContent = months[month];
        }

        function filterByAno(ano) {
            var filters = ['==', 'ano', ano]
            //map.setFilter('point', filters)
            document.getElementById('ano').textContent = ano;
        }

        mapboxgl.accessToken = 'pk.eyJ1IjoiamF2aWVyYXpkIiwiYSI6ImNqMHh4OGRwbTAwMTAzM3BiZjdya2k3ZjYifQ.8srntVtXZZF538OjGBQH0g';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/light-v9',
            center: [1.521835, 42.506317],
            zoom: 10
        });

        var idSource = makeid();
        var idSourceHeat = makeid();
        idSourceHeat = idSourceHeat + "-heat";
        map.on('load', function() {
            map.addSource(idSource, {
                "type": "geojson",
                "data": {
                    "type": "FeatureCollection",
                    "features": data
                },
                cluster: true,
                clusterMaxZoom: 14, // Max zoom to cluster points on
                clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
            });

            //new
            map.addSource(idSourceHeat, {
                "type": "geojson",
                "data": {
                    "type": "FeatureCollection",
                    "features": data
                },
                cluster: true,
                clusterMaxZoom: 14, // Max zoom to cluster points on
                clusterRadius: 10 // Radius of each cluster when clustering points (defaults to 50)
            });
            var layers = [
                [0, 'green'],
                [20, 'orange'],
                [200, 'red']
            ];
            layers.forEach(function(layer, i) {
                map.addLayer({
                    "id": "cluster-" + i,
                    "type": "circle",
                    "source": idSourceHeat,
                    "paint": {
                        "circle-color": layer[1],
                        "circle-radius": 50,
                        "circle-blur": 1 // blur the circles to get a heatmap look
                    },
                    "filter": i === layers.length - 1 ? [">=", "point_count", layer[0]] : ["all", [">=", "point_count", layer[0]],
                        ["<", "point_count", layers[i + 1][0]]
                    ]
                }, 'waterway-label');
            });
            var toggleableLayerIds = ['cluster-0', 'cluster-1', 'cluster-2'];
            var otherLayers = ['point','cluster-count','unclustered-point']
            for (var i = 0; i < toggleableLayerIds.length; i++) {
              map.setLayoutProperty(toggleableLayerIds[i], 'visibility', 'none');
            }
                //var id = toggleableLayerIds[i];
                var link = document.createElement('a');
                link.href = '#';
                link.className = 'active';
                link.textContent = "heatmap";
                var layers = document.getElementById('menu');
                link.onclick = function(e) {
                    for (var i = 0; i < toggleableLayerIds.length; i++) {
                      var clickedLayer = toggleableLayerIds[i];
                      e.preventDefault();
                      e.stopPropagation();

                      var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

                      if (visibility === 'visible') {
                          map.setLayoutProperty(clickedLayer, 'visibility', 'none');
                          this.className = '';
                      } else {
                          this.className = 'active';
                          map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
                      }
                    }
                    for (var i = 0; i < otherLayers.length; i++) {
                      var idLayer = otherLayers[i];
                      var visibility = map.getLayoutProperty(clickedLayer, 'visibility');
                      if (visibility === 'visible') {
                          map.setLayoutProperty(idLayer, 'visibility', 'none');
                          this.className = '';
                      } else {
                          this.className = 'active';
                          map.setLayoutProperty(idLayer, 'visibility', 'visible');
                      }
                    }
                };
                layers.appendChild(link);

            //new

            map.addLayer({
                "id": "point",
                "type": "circle",
                "source": idSource,
                filter: ["has", "point_count"],
                paint: {
                    "circle-color": {
                        property: "point_count",
                        type: "interval",
                        stops: [
                            [0, "#51bbd6"],
                            [100, "#f1f075"],
                            [750, "#f28cb1"],
                        ]
                    },
                    "circle-radius": {
                        property: "point_count",
                        type: "interval",
                        stops: [
                            [0, 20],
                            [100, 30],
                            [750, 40]
                        ]
                    }
                }
            });
            map.addLayer({
                id: "cluster-count",
                type: "symbol",
                source: idSource,
                filter: ["has", "point_count"],
                layout: {
                    "text-field": "{point_count_abbreviated}",
                    "text-font": ["DIN Offc Pro Medium", "Arial Unicode MS Bold"],
                    "text-size": 12
                }
            });
            map.addLayer({
                id: "unclustered-point",
                type: "circle",
                source: idSource,
                filter: ["!has", "point_count"],
                paint: {
                    "circle-color": "#11b4da",
                    "circle-radius": 4,
                    "circle-stroke-width": 1,
                    "circle-stroke-color": "#fff"
                }
            });

        })


        filterBy(48);
        filterByDia(26);
        filterByHour(22);
        filterByMonth(7);
        filterByAno(anos[anos.length-2]);

        document.getElementById('slider').addEventListener('input', function(e) {
            var minute = parseInt(e.target.value, 10);
            filterBy(minute);
        });
        document.getElementById('slider_dia').addEventListener('input', function(e) {
            var dia = parseInt(e.target.value, 10);
            filterByDia(dia);
        });
        document.getElementById('slider_hour').addEventListener('input', function(e) {
            var hour = parseInt(e.target.value, 10);
            filterByHour(hour);
        });
        document.getElementById('slider_ano').addEventListener('input', function(e) {
            var ano = parseInt(e.target.value, 10);
            filterByAno(ano);
        });
        document.getElementById('slider_month').addEventListener('input', function(e) {
            var month = parseInt(e.target.value, 10);
            filterByMonth(month);
        });
    </script>

</body>

</html>
