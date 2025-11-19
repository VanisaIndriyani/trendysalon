<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Recommendation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RecommendationsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportsExportController extends Controller
{
    /**
     * Build base query consistent with reports view, including optional search.
     */
    protected function query(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        return Recommendation::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $like = '%'.$q.'%';
                    $w->where('name', 'like', $like)
                      ->orWhere('phone', 'like', $like)
                      ->orWhere('hair_length', 'like', $like)
                      ->orWhere('hair_type', 'like', $like)
                      ->orWhere('hair_condition', 'like', $like)
                      ->orWhere('face_shape', 'like', $like)
                      ->orWhere('recommended_models', 'like', $like);
                });
            })
            ->orderByDesc('created_at');
    }

    /**
     * Export as native XLSX using Laravel Excel.
     */
    public function excel(Request $request)
    {
        // Auth check (allow admin or super)
        if (!session('admin_logged_in') && !session('super_logged_in')) {
            abort(403);
        }

        $recs = $this->query($request)->get();
        $filename = 'data_rekomendasi_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new RecommendationsExport($recs), $filename);
    }

    /**
     * Export as PDF using dompdf.
     */
    public function pdf(Request $request)
    {
        if (!session('admin_logged_in') && !session('super_logged_in')) {
            abort(403);
        }

        $recs = $this->query($request)->get();
        $pdf = Pdf::loadView('reports.pdf', [
            'title' => 'Data Rekomendasi',
            'recs' => $recs,
        ])->setPaper('a4', 'landscape');

        $filename = 'data_rekomendasi_'.now()->format('Ymd_His').'.pdf';
        return $pdf->download($filename);
    }
}