function linechart(cabecera, cantidad){
    var ctx = document.getElementById("myChart");
    var d = new Date();
    var n = d.getHours();
    var horas = [];
    for (var i = 0; i < 6; i++) {
      //console.log(n-i);
      var hora = n-i
      if (hora <= 0) {
        hora = 24 + hora;
      }
      if (hora > 24) {
        hora = hora - 24
      }
      horas.push(hora);
    }
    horas.reverse();
    var data = {
        labels: cabecera,
        datasets: [{
            label: "Vehicle Counter",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(75,192,192,0.4)",
            borderColor: "rgba(75,192,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: cantidad,
            spanGaps: false,
        }]
    };
    var myLineChart = new Chart(ctx, {
        type: "line",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontSize: 10
                    }
                }]
            }
        },
    });
}
