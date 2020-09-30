@extends('adminlte::page')

@section('content')
  @include('app.dashboard.components.card')
  <div class="row">
    @include('app.dashboard.components.sales-chart')
    @include('app.dashboard.components.product-cart')
  </div>
@endsection

@push('js')
  <script>
    $(function () {
      'use strict'

      var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
      }

      var mode      = 'index'
      var intersect = true

      var $salesChart = $('#sales-chart')
      var salesChart  = new Chart($salesChart, {
        type   : 'bar',
        data   : {
          labels  : ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              backgroundColor: '#007bff',
              borderColor    : '#007bff',
              data           : [1000, 2000, 3000, 2500, 2700, 2500, 3000]
            },
            {
              backgroundColor: '#ced4da',
              borderColor    : '#ced4da',
              data           : [700, 1700, 2700, 2000, 1800, 1500, 2000]
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips           : {
            mode     : mode,
            intersect: intersect
          },
          hover              : {
            mode     : mode,
            intersect: intersect
          },
          legend             : {
            display: false
          },
          scales             : {
            yAxes: [{
              // display: false,
              gridLines: {
                display      : true,
                lineWidth    : '4px',
                color        : 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
              },
              ticks    : $.extend({
                beginAtZero: true,

                // Include a dollar sign in the ticks
                callback: function (value, index, values) {
                  if (value >= 1000) {
                    value /= 1000
                    value += 'k'
                  }
                  return '$' + value
                }
              }, ticksStyle)
            }],
            xAxes: [{
              display  : true,
              gridLines: {
                display: false
              },
              ticks    : ticksStyle
            }]
          }
        }
      })
    })

  </script>
@endpush
