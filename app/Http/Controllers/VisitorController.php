<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Visitor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function create()
    {
        return view('pages.visitors.create', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visitor_type'  => ['required', 'in:mahasiswa,umum'],
            'name'          => ['required', 'string', 'max:255'],
            'purpose'       => ['nullable', 'array'],
            'purpose.*'     => ['string', 'max:100'],

            // Mahasiswa
            'nim'           => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],

            // Umum
            'institution'   => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {

            Visitor::create([
                'visitor_type'  => $data['visitor_type'],
                'name'          => $data['name'],
                'nim'           => $data['visitor_type'] === 'mahasiswa'
                                    ? $data['nim'] ?? null
                                    : null,
                'department_id' => $data['visitor_type'] === 'mahasiswa'
                                    ? $data['department_id'] ?? null
                                    : null,
                'institution'   => $data['visitor_type'] === 'umum'
                                    ? $data['institution'] ?? null
                                    : null,
                'purpose'       => $data['purpose'] ?? [],
                'visit_at'      => now(),
            ]);

            // Sinkronisasi student (khusus mahasiswa)
            if ($data['visitor_type'] === 'mahasiswa' && !empty($data['nim'])) {
                Student::updateOrCreate(
                    ['nim' => $data['nim']],
                    [
                        'nama'          => $data['name'],
                        'department_id' => $data['department_id'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('visitors.form')
            ->with(
                'success',
                'Terima kasih atas kunjungannya. Data Anda telah tersimpan dan dapat digunakan untuk pengajuan Bebas Pustaka maupun Penyerahan Skripsi. Untuk mengakses fitur tersebut, silakan hubungi staf perpustakaan.'
            );
    }



    public function index(Request $request)
    {
        $query = Visitor::with('department')->orderByDesc('visit_at');

        $from = $request->input('from');
        $to = $request->input('to');

        if ($from) {
            $query->whereDate('visit_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('visit_at', '<=', $to);
        }

        $visitors = $query->paginate(20)->withQueryString();

        $today = Visitor::whereDate('visit_at', Carbon::today())->count();
        $month = Visitor::whereYear('visit_at', Carbon::now()->year)
            ->whereMonth('visit_at', Carbon::now()->month)
            ->count();
        $total = Visitor::count();

        $byDepartment = Visitor::selectRaw('department_id, COUNT(*) as total')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        $weeklyLabels = [];
        $weeklyValues = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->startOfWeek()->subWeeks($i);
            $end = (clone $start)->endOfWeek();
            $weeklyLabels[] = $start->format('d M') . ' - ' . $end->format('d M');
            $weeklyValues[] = Visitor::whereBetween('visit_at', [$start, $end])->count();
        }

        $monthlyLabels = [];
        $monthlyValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyValues[] = Visitor::whereYear('visit_at', $month->year)
                ->whereMonth('visit_at', $month->month)
                ->count();
        }

        $dailyLabels = [];
        $dailyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyLabels[] = $date->format('d M');
            $dailyValues[] = Visitor::whereDate('visit_at', $date)->count();
        }

        return view('visitors.index', [
            'visitors' => $visitors,
            'total' => $total,
            'today' => $today,
            'month' => $month,
            'byDepartment' => $byDepartment,
            'weeklyLabels' => $weeklyLabels,
            'weeklyValues' => $weeklyValues,
            'monthlyLabels' => $monthlyLabels,
            'monthlyValues' => $monthlyValues,
            'dailyLabels' => $dailyLabels,
            'dailyValues' => $dailyValues,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $query = Visitor::with('department')->orderByDesc('visit_at');

        $from = $request->input('from');
        $to = $request->input('to');

        if ($from) {
            $query->whereDate('visit_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('visit_at', '<=', $to);
        }

        $rows = $query->get();
        $filename = 'laporan_pengunjung_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Waktu', 'Nama', 'NIM', 'Program Studi', 'Keperluan']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    optional($row->visit_at)->format('Y-m-d H:i:s'),
                    $row->name,
                    $row->nim,
                    optional($row->department)->name,
                    $row->purpose,
                ]);
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Visitor::with('department')->orderByDesc('visit_at');

        $from = $request->input('from');
        $to = $request->input('to');

        if ($from) {
            $query->whereDate('visit_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('visit_at', '<=', $to);
        }

        $visitors = $query->get();
        $pdf = PDF::loadView('visitors.report-pdf', [
            'visitors' => $visitors,
            'from' => $from,
            'to' => $to,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan_pengunjung.pdf');
    }
}
