<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prestasi;
use App\Models\Mahasiswa;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        // Data for the first chart (Prestasi per month)
        $prestasiData = Prestasi::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('MIN(created_at) as created_at_min')
        )
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b")'))
            ->orderBy('created_at_min')
            ->get();

        $months = $prestasiData->pluck('month')->toArray();
        $counts = $prestasiData->pluck('count')->toArray();

        // Data for the second chart (Tingkatan Prestasi per month)
        $tingkatanData = Prestasi::select(
            DB::raw('DATE_FORMAT(created_at, "%b") as month'),
            'tingkatan_prestasi',
            DB::raw('COUNT(*) as count'),
            DB::raw('MIN(created_at) as created_at_min')
        )
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%b")'), 'tingkatan_prestasi')
            ->orderBy('created_at_min')
            ->get();

        $tingkatanChartData = [
            'Lokal' => array_fill(0, 12, 0),
            'Nasional' => array_fill(0, 12, 0),
            'Internasional' => array_fill(0, 12, 0)
        ];

        $allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $mahasiswaBerprestasi = Mahasiswa::has('prestasi')->count();

        foreach ($tingkatanData as $data) {
            $monthIndex = array_search($data->month, $allMonths);
            if ($monthIndex !== false) {
                $tingkatanChartData[$data->tingkatan_prestasi][$monthIndex] = $data->count;
            }
        }

        $twoMonthsAgo = Carbon::now()->subMonths(2)->startOfMonth();
        $lastTwoMonths = [
            Carbon::now()->subMonth()->format('F'),
            Carbon::now()->format('F')
        ];

        $prodiData = DB::table('mahasiswa')
            ->select('mahasiswa.prodi', DB::raw('COUNT(DISTINCT prestasi.mahasiswa_id) as mahasiswa_count'))
            ->join('prestasi', 'mahasiswa.id', '=', 'prestasi.mahasiswa_id')
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('mahasiswa_count')
            ->take(5)
            ->get();

        $totalMahasiswaBerprestasi = $prodiData->sum('mahasiswa_count');

        $prestasiLokal = DB::table('mahasiswa')
            ->select('mahasiswa.prodi', DB::raw('COUNT(DISTINCT prestasi.mahasiswa_id) as mahasiswa_count'))
            ->join('prestasi', 'mahasiswa.id', '=', 'prestasi.mahasiswa_id')
            ->where('prestasi.tingkatan_prestasi', '=', 'Lokal')
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('mahasiswa_count')
            ->take(5)
            ->get();

        $prestasiNasional = DB::table('mahasiswa')
            ->select('mahasiswa.prodi', DB::raw('COUNT(DISTINCT prestasi.mahasiswa_id) as mahasiswa_count'))
            ->join('prestasi', 'mahasiswa.id', '=', 'prestasi.mahasiswa_id')
            ->where('prestasi.tingkatan_prestasi', '=', 'Nasional')
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('mahasiswa_count')
            ->take(5)
            ->get();

        $prestasiInternasional = DB::table('mahasiswa')
            ->select('mahasiswa.prodi', DB::raw('COUNT(DISTINCT prestasi.mahasiswa_id) as mahasiswa_count'))
            ->join('prestasi', 'mahasiswa.id', '=', 'prestasi.mahasiswa_id')
            ->where('prestasi.tingkatan_prestasi', '=', 'Internasional')
            ->groupBy('mahasiswa.prodi')
            ->orderByDesc('mahasiswa_count')
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'role', 'months', 'counts', 'tingkatanChartData', 'allMonths', 'mahasiswaBerprestasi', 'lastTwoMonths', 'prodiData', 'totalMahasiswaBerprestasi', 'prestasiLokal', 'prestasiNasional', 'prestasiInternasional'));
    }
}
