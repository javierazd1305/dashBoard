//var data = <?php echo json_encode($total); ?>;

function PieDatos(data){
  var pieChart = data[0];
  //console.log(pieChart);
  //console.log(bubbleChart);

  var sensoresOn = 0;
  var sensoresOff = 0;
  var consolidadoPie = []
  for (var i=0; i < pieChart.length; i++){
    //console.log(data[i]["properties"]["status"]);
    //console.log(data[i]);
    var activo = pieChart[i]["properties"]["status"];
    if (activo == '1') {
      sensoresOn +=1;
    }else {
      sensoresOff +=1;
    }
  }
  consolidadoPie.push(sensoresOff)
  consolidadoPie.push(sensoresOn)
  //console.log(consolidadoPie);
  return consolidadoPie;
}

function BubbleDatos(datos){
  var bubbleChart = data[1];
  var labelsBubble = []
  var cantidadBubble = []
  for (var i = 0; i < bubbleChart.length; i++) {
    //console.log(bubbleChart[i]["counts"]);
    var tipo = bubbleChart[i]["type"]
    var cantidad = bubbleChart[i]["counts"]
    labelsBubble.push(tipo)
    cantidadBubble.push(cantidad)
  }
  //console.log(labelsBubble);
  //console.log(cantidadBubble);
  return [labelsBubble, cantidadBubble]
}


function LineDatos(datos){
  var lineChart = data[2];
  var labelLine = []
  var cantidadLine = []
  for (var i = 0; i < lineChart.length; i++) {
    //console.log(bubbleChart[i]["counts"]);
    var label = lineChart[i]["count_date"]
    var cantidad = lineChart[i]["count_total"]
    labelLine.push(label)
    cantidadLine.push(cantidad)
  }
  //console.log(labelsBubble);
  //console.log(cantidadBubble);
  return [labelLine, cantidadLine]
}
