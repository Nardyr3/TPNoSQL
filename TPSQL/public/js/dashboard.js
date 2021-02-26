/* globals Chart:false, feather:false */

(function () {
  'use strict'

  // Graphs
  var ctx = document.getElementById('myChart')

  var graphData = document.querySelector('.graph-data');
  var dataGraphString = graphData.dataset.graph;

  console.log(dataGraphString);

  var dataGraph = dataGraphString.replace('[','').replace(']','').split(',').map(Number);

  var data = dataGraph;


  // var dataGraph = $('.graph-data').data('graph');

  console.log(dataGraph)

  var labelGraph = document.querySelector('.graph-data').dataset.label.replace('[','').replace(']','').replace('\"','').split(',');

  console.log(labelGraph)

  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labelGraph,
      datasets: [{
        // data: [
        //   15339,
        //   21345,
        //   18483,
        //   24003,
        //   23489,
        //   24092,
        //   12034
        // ],
        data: dataGraph,
        borderColor: '#007bff',
        borderWidth: 4,
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      },
      legend: {
        display: false
      }
    }
  })
})()


