@component('mail::message')
# Laporan Kerusakan Baru

**Nama:** {{ $report->reporter_name }}  
**Email:** {{ $report->email }}  
**Jabatan:** {{ $report->position }}  
**Fakultas:** {{ $report->department }}  
**Kategori:** {{ $report->category }}  
**Subkategori:** {{ $report->subcategory }}  
**Lantai:** {{ $report->floor }}  
**Lokasi:** {{ $report->lokasi }}  
**Deskripsi:**  
{{ $report->description }}

@endcomponent
