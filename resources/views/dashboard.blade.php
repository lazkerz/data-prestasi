@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <!-- <div class="flex flex-row gap-3 md:ml-3 mt-5 mb-5">
            <a href="" class="bg-purple-300 rounded-md hover:bg-purple-400 text-white font-bold py-2 px-3 rounded shadow shadow-md">
                Akademik
            </a>
            <a href="" class="bg-purple-300 rounded-md hover:bg-purple-400 text-white font-bold py-2 px-3 rounded shadow shadow-md">
                Non Akademik
            </a>
        </div> -->
        <h1 class="text-2xl font-semibold text-black my-5 ml-3">Dashboard</h1>

        <div class="md:ml-3 grid grid-cols-2 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 rounded rounded-lg border border-purple-400 shadow shadow-md w-full p-4">
            <div>
                <div class="flex flex-col gap-1 mb-2">
                    <p class="text-sm font-medium">Jumlah</p>
                    <h2 class="text-md font-semibold text-black">Prestasi</h2>
                </div>
                <div class="bg-gray-200 h-45 p-2 rounded rounded-lg w-full" id="prestasiChart">
                </div>
            </div>

            <div>
                <div class="flex flex-col gap-1 mb-2">
                    <p class="text-sm font-medium">Jumlah</p>
                    <h2 class="text-md font-semibold text-black">Tingkatan Prestasi</h2>
                    <div class="bg-gray-200 h-45 p-2 rounded rounded-lg w-full" id="tingkatanChart"></div>
                </div>
            </div>

        </div>
        <div class="md:ml-3 mt-3 flex flex-col bg-purple-300 p-5 rounded rounded-lg w-full">
            <div class="bg-white flex flex-col gap-3 p-4 rounded rounded-lg shadow shadow-md w-fit mb-3">
                <p class="text-md font-semibold">Mahasiswa Berprestasi</p>
                <h2 class="text-3xl font-semibold text-black">{{ $mahasiswaBerprestasi }}</h2>
                <p class="text-sm font-medium">dalam {{ $lastTwoMonths[0] }} - {{ $lastTwoMonths[1] }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-3 rounded rounded-lg shadow shadow-md w-full">
                <div class="bg-white p-2 rounded rounded-lg w-full rounded rounded-lg">
                    <p class="text-sm font-semibold">Prodi</p>
                    <h2 class="text-md font-semibold text-purple-500 mb-3">Lima Teratas</h2>

                    @foreach($prodiData as $data)
                    <div class="flex flex-row gap-2 justify-between">
                        <span class="font-semibold text-sm">{{ $data->prodi }}</span>
                        <span class="font-medium text-sm">Jumlah : {{ $data->mahasiswa_count }} Mahasiswa Berprestasi</span>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white p-2 rounded rounded-lg w-full">
                    <p class="text-sm font-semibold">Prodi</p>
                    <h2 class="text-md font-semibold text-purple-500">Prestasi Internasional Terbanyak</h2>
                    @foreach($prestasiInternasional as $data)
                    <div class="flex flex-row gap-2 justify-between">
                        <span class="font-semibold text-sm">{{ $data->prodi }}</span>
                        <span class="font-medium text-sm">Jumlah : {{ $data->mahasiswa_count }} Mahasiswa Berprestasi</span>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white p-2 rounded rounded-lg w-full">
                    <p class="text-sm font-semibold">Prodi</p>
                    <h2 class="text-md font-semibold text-purple-500">Prestasi Nasional Terbanyak</h2>
                    @foreach($prestasiNasional as $data)
                    <div class="flex flex-row gap-2 justify-between">
                        <span class="font-semibold text-sm">{{ $data->prodi }}</span>
                        <span class="font-medium text-sm">Jumlah : {{ $data->mahasiswa_count }} Mahasiswa Berprestasi</span>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white p-2 rounded rounded-lg w-full">
                    <p class="text-sm font-semibold">Prodi</p>
                    <h2 class="text-md font-semibold text-purple-500">Prestasi Lokal Terbanyak</h2>
                    @foreach($prestasiLokal as $data)
                    <div class="flex flex-row gap-2 justify-between">
                        <span class="font-semibold text-sm">{{ $data->prodi }}</span>
                        <span class="font-medium text-sm">Jumlah : {{ $data->mahasiswa_count }} Mahasiswa Berprestasi</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var months = <?php echo json_encode($months); ?>;
    var prestasis = <?php echo json_encode($counts); ?>;
    var maxPrestasis = Math.max(...prestasis);
    var tingkatanChartData = <?php echo json_encode($tingkatanChartData); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        // First chart (Prestasi per month)
        Highcharts.chart('prestasiChart', {
            chart: {
                type: 'area',
                backgroundColor: '#e5e7eb',
                height: 300,
                spacing: [40, 20, 20, 20]
            },
            title: {
                text: null
            },
            xAxis: {
                categories: allMonths,
                title: {
                    text: 'Bulan'
                },
                tickInterval: 1,
                labels: {
                    rotation: 0
                }
            },
            yAxis: {
                min: 0,
                max: maxPrestasis,
                tickInterval: 1,
                title: {
                    text: 'Jumlah Prestasi'
                },
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Prestasi',
                data: allMonths.map(month => prestasis[months.indexOf(month)] || 0),
                color: '#9f7aea',
                fillColor: {
                    linearGradient: [0, 0, 0, 300],
                    stops: [
                        [0, Highcharts.color('#9f7aea').setOpacity(0.5).get('rgba')],
                        [1, Highcharts.color('#9f7aea').setOpacity(0).get('rgba')]
                    ]
                }
            }],
            plotOptions: {
                area: {
                    fillOpacity: 0.5
                }
            }
        });

        // Second chart (Tingkatan Prestasi)
        Highcharts.chart('tingkatanChart', {
            chart: {
                type: 'column',
                backgroundColor: '#e5e7eb',
                height: 300,
                spacing: [40, 20, 20, 20]
            },
            title: {
                text: null
            },
            xAxis: {
                categories: allMonths,
                title: {
                    text: 'Bulan'
                },
                crosshair: true,
                tickInterval: 1,
                labels: {
                    rotation: 0
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah Prestasi'
                }
            },
            legend: {
                align: 'right',
                verticalAlign: 'top',
                backgroundColor: '#FFFFFF',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    groupPadding: 0.1
                }
            },
            series: [{
                name: 'Lokal',
                data: tingkatanChartData.Lokal,
                color: '#9f7aea' // Purple
            }, {
                name: 'Nasional',
                data: tingkatanChartData.Nasional,
                color: '#4fd1c5' // Teal
            }, {
                name: 'Internasional',
                data: tingkatanChartData.Internasional,
                color: '#f6ad55' // Orange
            }],
            credits: {
                enabled: false
            }
        });
    });
</script>
@endpush