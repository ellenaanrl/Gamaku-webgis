<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kerusakan Baru</title>
</head>
<body>
    <h2>Laporan Baru Diterima</h2>
    <p><strong>Nama Pelapor:</strong> {{ $report->reporter_name }}</p>
    <p><strong>Email Pelapor:</strong> {{ $report->email }}</p>
    <p><strong>Departemen:</strong> {{ $report->department }}</p>
    <p><strong>Kategori:</strong> {{ $report->category }} - {{ $report->subcategory }}</p>
    <p><strong>Deskripsi:</strong> {{ $report->description }}</p>
    <p><strong>Status:</strong> {{ $report->status }}</p>
</body>
</html>
