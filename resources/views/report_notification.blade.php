<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Baru</title>
</head>
<body>
    <h2>Laporan Kerusakan Baru</h2>
    <p><strong>Nama:</strong> {{ $report->nama }}</p>
    <p><strong>Deskripsi:</strong> {{ $report->deskripsi }}</p>
    <p><strong>Lokasi:</strong> {{ $report->lokasi }}</p>
    <p><strong>Tanggal:</strong> {{ $report->created_at }}</p>

    <hr>
    <p>Segera tinjau laporan ini di dashboard admin.</p>
</body>
</html>
