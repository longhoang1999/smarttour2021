@extends('admin/layout/index')
@section('title')
    Edit Account
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <style>
    .box0 a {
        color: white;
    }
    .text-acc{color: #007bff;}
    .text-place {color: #28a745;}
    .text-avg {color: #17b6dd;}
    .text-feedback {color: #ffc107;}
    .text-xs { font-size: 12px;}
    .border-left-primary {border-top: 3px solid #007bff;}
    .border-left-success {border-top: 3px solid green;}
    .border-left-info {border-top: 3px solid #17b6dd;}
    .border-left-warning {border-top: 3px solid #ffc107;}
  </style>
@stop
@section('content')
  @if ($message = Session::get('status'))
      <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
  <div class="container-fluid">
    <div class="row">
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ trans('admin.totalAcc') }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalAcc}} {{ trans('admin.accounts') }}</div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-portrait fa-2x text-acc"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ trans('admin.totalPlace') }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalDes}} {{ trans('admin.places') }}</div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-map-marker-alt fa-2x text-place"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ trans('admin.avgStar') }}</div>
                    <div class="row no-gutters align-items-center">
                      <div class="col-auto">
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{number_format((float)$avgStar, 2, '.', '')}}</div>
                      </div>
                      <div class="col">
                        <div class="progress progress-sm mr-2">
                          <div class="progress-bar bg-info" role="progressbar" style="width: {{number_format((float)($avgStar*10), 2, '.', '')}}%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fab fa-medium fa-2x text-avg"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ trans('admin.totalFeedback') }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalFeedback}} {{ trans('admin.feedback') }}</div>
                  </div>
                  <div class="col-auto">
                    <i class="far fa-comments fa-2x text-feedback"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
  </div>

  <div class="title"><p class="text-uppercase">{{ trans('admin.chartDashboard') }}</p></div>
  <div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p class="text-lowercase">{{ trans('admin.chartDashboard') }}</p>
        </div>
        <div class="AllClass_Table_content">
            <div class="chart">
              <canvas id="chart"></canvas>
            </div>
        </div>
    </div>
@stop
@section('footer-js')
    <script src="{{asset('js/Chart.min.js')}}"></script>
    <script>
    $(document).ready(function(){
        new Chart(document.getElementById("chart"),{
        type: 'bar',
        data: {
          labels: [
            '{{ trans("admin.chartTotalAcc") }}','{{ trans("admin.chartTotalPlace") }}','{{ trans("admin.StarAvg") }}','{{ trans("admin.chartTotalFeedback") }}'
          ],
          datasets: [{ 
              label: '{{ trans("admin.Generalinformation") }}',
              data: [
                '{{$totalAcc}}','{{$totalDes}}','{{number_format((float)$avgStar, 2, '.', '')}}','{{$totalFeedback}}'
              ],
          
              backgroundColor:["rgba(255, 99, 132,0.7)","rgba(54, 162, 235,0.7)","rgba(16,167,69,0.7)","rgba(255, 94, 62,0.7)"],
              hoverBackgroundColor: [
                        '#ca4561',
                        '#178ad8',
                        '#3caa3c',
                        '#f35030'
                    ],
              borderColor: '#4b4bbd',
              hoverBorderColor:[
                        '#b5c4c7'
                    ],
              fill: false
            },
          ]
        },
        options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          },

        }
      });
    });
  </script>
@stop
