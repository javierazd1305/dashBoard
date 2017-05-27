function bubblechart(cabecera,count){
    var ctx = document.getElementById("myChart-2");
    var labels = cabecera
    var data = {
          datasets: [{
              data: count,
              backgroundColor: [
                  "#FF6384",
                  "#4BC0C0",
                  "#FFCE56",
                  "#E7E9ED",
                  "#36A2EB"
              ],
              label: 'My dataset' // for legend
          }],
          labels: labels
      };
    var myPolarChart = new Chart(ctx, {
        type: 'polarArea',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false
        },
    });
  }
