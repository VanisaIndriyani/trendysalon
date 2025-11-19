<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RecommendationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection */
    protected Collection $recs;

    public function __construct(Collection $recs)
    {
        $this->recs = $recs->values();
    }

    /**
     * Provide the data rows.
     */
    public function collection(): Collection
    {
        return $this->recs;
    }

    /**
     * Provide the header row.
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama',
            'Nomor Telepon',
            'Bentuk Wajah',
            'Panjang Rambut',
            'Jenis Rambut',
            'Tipe Rambut',
            'Vitamin Rambut',
            'Model Direkomendasikan',
        ];
    }

    /**
     * Map a single recommendation to a row.
     */
    public function map($rec): array
    {
        static $no = 0; $no++;
        return [
            $no,
            optional($rec->created_at)->format('d/m/Y'),
            $rec->name,
            $rec->phone,
            $rec->face_shape,
            $rec->hair_length,
            $rec->hair_type,
            $rec->hair_condition,
            $rec->vitamin ?? '-',
            $rec->recommended_models,
        ];
    }
}