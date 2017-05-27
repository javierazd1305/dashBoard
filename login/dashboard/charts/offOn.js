function pieOffOn(datos){
  var ctx = document.getElementById("myChart-1");
  //console.log(datos);
  var data = {
      labels: [
          "Offline",
          "Online"
      ],
      datasets: [{
          data: datos,
          backgroundColor: [
              "#FF6384",
              "#36A2EB"
          ],
          hoverBackgroundColor: [
              "#FF6384",
              "#36A2EB"
          ]
      }]
  };
  var myPieChart = new Chart(ctx, {
      type: 'pie',
      data: data,
      options: {
          responsive: true,
          maintainAspectRatio: false
      },
  });

}
