<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Data Rekomendasi' }}</title>
    <style>
        /* Ukuran halaman dan orientasi untuk konten tabel lebar */
        @page { size: A4 landscape; margin: 12mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; margin: 0; }
        h3 { margin-bottom: 8px; }

        /* Tabel lebih rapat dan tidak meluber */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: 1px solid #e5e7eb; padding: 5px; text-align: left; vertical-align: top; }
        th { background: #f9fafb; }
        th, td { word-wrap: break-word; overflow-wrap: anywhere; }

        /* Lebar kolom agar pas di halaman landscape */
        th:nth-child(1), td:nth-child(1) { width: 4%; }
        th:nth-child(2), td:nth-child(2) { width: 10%; }
        th:nth-child(3), td:nth-child(3) { width: 10%; }
        th:nth-child(4), td:nth-child(4) { width: 12%; }
        th:nth-child(5), td:nth-child(5) { width: 10%; }
        th:nth-child(6), td:nth-child(6) { width: 10%; }
        th:nth-child(7), td:nth-child(7) { width: 10%; }
        th:nth-child(8), td:nth-child(8) { width: 10%; }
        th:nth-child(9), td:nth-child(9) { width: 7%; }
        th:nth-child(10), td:nth-child(10) { width: 17%; }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <h3>{{ $title ?? 'Data Rekomendasi' }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Nomor Telepon</th>
                <th>Bentuk Wajah</th>
                <th>Panjang Rambut</th>
                <th>Jenis Rambut</th>
                <th>Tipe Rambut</th>
                <th>Vitamin Rambut</th>
                <th>Model Direkomendasikan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($recs as $rec)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ optional($rec->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $rec->name }}</td>
                    <td>{{ $rec->phone }}</td>
                    <td>{{ $rec->face_shape }}</td>
                    <td>{{ $rec->hair_length }}</td>
                    <td>{{ $rec->hair_type }}</td>
                    <td>{{ $rec->hair_condition }}</td>
                    <td>{{ $rec->vitamin ?? '-' }}</td>
                    <td>{{ $rec->recommended_models }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;">Belum ada data rekomendasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>