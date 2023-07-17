@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Class</h3>
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <div class="row">
            <div class="col-md-1">
                <p>Class : </p>
            </div>
            <div class="col-md-11">
                <p>{{$class[0]->name}}</p>
            </div>
        </div>
        <div>
            <canvas id="studentHistoryChart" width="600" height="400"></canvas>
        </div>

    </div>
    @endsection

    @section('script')
    <script>
        $(document).ready(function() {
            //ID student
            var idEncrypted = "{{request('idEncrypted')}}"
            history_student(idEncrypted)
            // var options = {
            //     type: 'line',
            //     data: {
            //         labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
            //         datasets: [{
            //                 label: '# of Votes',
            //                 data: [12, 19, 3, 5, 2, 3],
            //                 borderWidth: 1
            //             },
            //             {
            //                 label: '# of Points',
            //                 data: [7, 11, 5, 8, 3, 7],
            //                 borderWidth: 1
            //             }
            //         ]
            //     },
            //     options: {
            //         scales: {
            //             yAxes: [{
            //                 ticks: {
            //                     reverse: false
            //                 }
            //             }]
            //         }
            //     }
            // }

            // var ctx = document.getElementById('studentHistoryChart').getContext('2d');
            // new Chart(ctx, options);
        });

        function history_student(idEncrypted) {
            $.ajax({
                type: "GET",
                url: "{{ url('history/getDatasetHistory') }}" + '/' + idEncrypted,
                success: function(response) {
                    var labels = response.labels
                    var data = response.data

                    var ctx = $('#studentHistoryChart');
                    var config = {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'History Point',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 1)',

                            }]
                        }
                    };
                    var chart = new Chart(ctx, config);
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                }
            });
        }
    </script>
    @endsection