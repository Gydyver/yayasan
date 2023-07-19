@extends('app/layout')

@section('content')
@if(Auth::User()->usergroup_id == 1)
<!-- <div> -->
<!-- Superadmin -->
<div class="row">
    <div class="col-md-6">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Nama</span>
                <span class="info-box-number">
                    {{$user_info[0]->name}}
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Class</span>
                <span class="info-box-number">
                    {{count($totalStudent)}}
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <canvas id="pieGenderStudData">
            </canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <canvas id="columnCanceledClass">
            </canvas>
        </div>
    </div>
</div>

<!-- </div> -->

@elseif(Auth::User()->usergroup_id == 2)
<div>
    <!-- Teacher -->
    <div class="row">
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Nama</span>
                    <span class="info-box-number">
                        {{$user_info[0]->name}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Class</span>
                    <span class="info-box-number">
                        {{count($totalStudent)}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="pieGenderStudData">
                </canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <canvas id="columnMostAbsentStud">
                </canvas>
            </div>
        </div>
    </div>
</div>

@elseif(Auth::User()->usergroup_id == 3)
<div>
    <!-- Student -->
    <div class="row">
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Nama</span>
                    <span class="info-box-number">
                        {{$user_info[0]->name}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Class</span>
                    <span class="info-box-number">
                        {{$next_class[0]->class_name}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Hafalan Terakhir</span>
                    <span class="info-box-number">
                        {{ $studentLatestData[0]->latest_hapalan}}
                    </span>

                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Halaman Terakhir</span>
                    <span class="info-box-number">
                        {{$studentLatestData[0]->latest_halaman}}
                    </span>

                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Kelas Berikutnya</span>
                    <span class="info-box-number">
                        {{date('d-m-Y H:i', strtotime($next_class[0]->session_start))}} -
                        {{date('H:i', strtotime($next_class[0]->session_end))}}
                    </span>

                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <canvas id="studentHistoryChart"></canvas>
            </div>
        </div>
    </div>
</div>
@else

@endif

@endsection

@section('script')
<script>
    $(document).ready(function() {
        var usergroup_id = `{{Auth::User()->usergroup_id}}`;
        var idEncrypted = "{{$idEncrypted}}"

        if (usergroup_id == 1) {
            student_gender_pie();
            cancelled_class();

        } else if (usergroup_id == 2) {
            student_gender_pie();
            absent_student();
        } else if (usergroup_id == 3) {
            history_student(idEncrypted)
        }
    })

    function student_gender_pie() {
        $.ajax({
            type: "GET",
            url: "{{ url('/dashboard/getDatasetStudGen') }}",
            success: function(response) {
                var datas = JSON.parse(response)
                var labels = datas.label;
                var color = ["#152358", "#176AA1", "#19AADE", "#19C9E5", "#1AD5D3", "#1CE3BD", "#280573", "#B149D3"]

                var dataset = [];

                var ctx = $('#pieGenderStudData');
                var config = {
                    type: 'pie',
                    options: {
                        title: {
                            display: true,
                            text: 'Perbandingan Siswa Laki-Laki dan Perempuan',
                            position: 'top'
                        },
                        rotation: -0.7 * Math.PI,
                    },
                    data: {
                        labels: labels,
                        datasets: [{
                            fill: true,
                            backgroundColor: [
                                'red',
                                'blue'
                            ],
                            data: datas.total,
                            // Notice the borderColor 
                            borderColor: ['black', 'black'],
                            borderWidth: [2, 2]
                        }]
                        // datasets: dataset
                    },

                };
                var chart = new Chart(ctx, config);
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
            }
        });
    }

    function cancelled_class() {
        $.ajax({
            type: "GET",
            url: "{{ url('/dashboard/getDataSetSesGenCanceled') }}",
            success: function(response) {
                var datas = JSON.parse(response)
                var labels = datas.label;
                var color = ["#152358", "#176AA1", "#19AADE", "#19C9E5", "#1AD5D3", "#1CE3BD", "#280573", "#B149D3"]

                var dataset = [];

                var ctx = $('#columnCanceledClass');
                var config = {
                    type: 'bar',
                    options: {
                        title: {
                            display: true,
                            text: 'Kelas Dibatalkan',
                            position: 'top'
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                ticks: {
                                    suggestedMin: 0, // minimum will be 0, unless there is a lower value.
                                    beginAtZero: true // minimum value will be 0.
                                }
                            }]
                        }
                    },
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Kelas Dibatalkan',
                            fill: true,
                            backgroundColor: [
                                'red',
                                'blue'
                            ],
                            data: datas.total,
                            // Notice the borderColor 
                            borderColor: ['blue', 'blue'],
                            borderWidth: [2, 2]
                        }]
                        // datasets: dataset
                    },

                };
                var chart = new Chart(ctx, config);
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
            }
        });

    }

    function absent_student() {
        $.ajax({
            type: "GET",
            url: "{{ url('/dashboard/getStudHighestAbsent') }}",
            success: function(response) {
                var datas = JSON.parse(response)
                var labels = datas.label;
                var color = ["#152358", "#176AA1", "#19AADE", "#19C9E5", "#1AD5D3", "#1CE3BD", "#280573", "#B149D3"]

                var dataset = [];

                var ctx = $('#columnMostAbsentStud');
                var config = {
                    type: 'bar',
                    options: {
                        title: {
                            display: true,
                            text: '5 Peserta Didik dengan Absen Terbanyak',
                            position: 'top'
                        },
                        rotation: -0.7 * Math.PI,
                        scales: {
                            yAxes: [{
                                display: true,
                                ticks: {
                                    suggestedMin: 0, // minimum will be 0, unless there is a lower value.
                                    beginAtZero: true // minimum value will be 0.
                                }
                            }]
                        }
                    },
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Peserta Didik',
                            fill: true,
                            backgroundColor: [
                                'blue',
                                'blue'
                            ],
                            data: datas.total,
                            // Notice the borderColor 
                            borderColor: ['blue', 'blue'],
                            borderWidth: [2, 2]
                        }]
                        // datasets: dataset
                    },

                };
                var chart = new Chart(ctx, config);
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
            }
        });

    }

    function history_student(idEncrypted) {
        $.ajax({
            type: "GET",
            url: "{{ url('history/getDatasetHistory') }}" + '/' + idEncrypted,
            success: function(response) {
                var datas = JSON.parse(response)
                var labels = datas.varlabel;
                var color = ["#152358", "#176AA1", "#19AADE", "#19C9E5", "#1AD5D3", "#1CE3BD", "#280573", "#B149D3"]

                var dataset = [];

                Object.keys(datas['varbaru']).forEach(key => {

                    dataset.push({
                        label: key,
                        data: datas['varbaru'][key],
                        borderWidth: 1,
                    }, )
                });

                var ctx = $('#studentHistoryChart');
                var config = {
                    type: 'line',
                    options: {
                        title: {
                            display: true,
                            text: 'History Peserta Didik',
                            position: 'top'
                        },
                        // scales: {
                        //     y: {
                        //         beginAtZero: true
                        //     }
                        // }
                        scales: {
                            yAxes: [{
                                display: true,
                                ticks: {
                                    suggestedMin: 0, // minimum will be 0, unless there is a lower value.
                                    beginAtZero: true // minimum value will be 0.
                                }
                            }]
                        }
                    },
                    data: {
                        labels: labels,
                        datasets: dataset
                    },

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