<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Permohonan Izin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0;
        }
        .status-box {
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .status-approved {
            background-color: #dcfce7;
            border: 2px solid #16a34a;
        }
        .status-rejected {
            background-color: #fee2e2;
            border: 2px solid #dc2626;
        }
        .status-processing {
            background-color: #dbeafe;
            border: 2px solid #1e40af;
        }
        .status-revision {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .status-text {
            font-size: 20px;
            font-weight: bold;
        }
        .status-approved .status-text { color: #16a34a; }
        .status-rejected .status-text { color: #dc2626; }
        .status-processing .status-text { color: #1e40af; }
        .status-revision .status-text { color: #f59e0b; }
        
        .tracking-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .tracking-number {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 1px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #666;
            width: 40%;
        }
        .notes-box {
            background-color: #f8fafc;
            border-left: 4px solid #1e40af;
            padding: 15px;
            margin: 20px 0;
        }
        .notes-box h4 {
            margin: 0 0 10px;
            color: #1e40af;
        }
        .button {
            display: inline-block;
            background-color: #1e40af;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #1e3a8a;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .next-steps {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .next-steps h4 {
            color: #16a34a;
            margin: 0 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏛️ Sistem Perizinan Reklame Online</h1>
            <p>Kabupaten Sidoarjo</p>
        </div>

        <p>Yth. <strong>{{ $permit->nama_pemohon }}</strong>,</p>

        <p>Status permohonan izin reklame Anda telah diperbarui:</p>

        @php
            $statusClasses = [
                'approved' => 'status-approved',
                'rejected' => 'status-rejected',
                'submitted' => 'status-processing',
                'verified' => 'status-processing',
                'review_kasi' => 'status-processing',
                'review_kabid' => 'status-processing',
                'revision' => 'status-revision',
            ];
            $statusIcons = [
                'approved' => '✅',
                'rejected' => '❌',
                'submitted' => '📋',
                'verified' => '✓',
                'review_kasi' => '🔍',
                'review_kabid' => '🔍',
                'revision' => '📝',
            ];
            $statusLabels = [
                'submitted' => 'Diajukan',
                'verified' => 'Terverifikasi',
                'review_kasi' => 'Review Kasi',
                'review_kabid' => 'Review Kabid',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'revision' => 'Perlu Revisi',
            ];
            $statusClass = $statusClasses[$newStatus] ?? 'status-processing';
            $statusIcon = $statusIcons[$newStatus] ?? '📋';
            $statusLabel = $statusLabels[$newStatus] ?? ucfirst($newStatus);
        @endphp

        <div class="status-box {{ $statusClass }}">
            <div class="status-icon">{{ $statusIcon }}</div>
            <div class="status-text">{{ $statusLabel }}</div>
        </div>

        <div class="tracking-info">
            <small>Nomor Tracking</small>
            <div class="tracking-number">{{ $permit->tracking_number }}</div>
        </div>

        <table class="info-table">
            <tr>
                <td>Status Sebelumnya</td>
                <td>{{ $statusLabels[$oldStatus] ?? ucfirst($oldStatus) }}</td>
            </tr>
            <tr>
                <td>Status Baru</td>
                <td><strong>{{ $statusLabel }}</strong></td>
            </tr>
            <tr>
                <td>Waktu Update</td>
                <td>{{ now()->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Lokasi Reklame</td>
                <td>{{ $permit->lokasi_alamat }}</td>
            </tr>
        </table>

        @if($notes)
        <div class="notes-box">
            <h4>📝 Catatan dari Petugas:</h4>
            <p style="margin: 0;">{{ $notes }}</p>
        </div>
        @endif

        @if($newStatus === 'approved')
        <div class="next-steps">
            <h4>🎉 Langkah Selanjutnya:</h4>
            <ol style="margin: 10px 0; padding-left: 20px;">
                <li>Cetak surat izin dari sistem</li>
                <li>Bawa surat izin ke kantor DPMPTSP untuk legalisir</li>
                <li>Lakukan pembayaran retribusi sesuai ketentuan</li>
                <li>Pasang reklame sesuai dengan izin yang diberikan</li>
            </ol>
        </div>
        @elseif($newStatus === 'rejected')
        <div class="notes-box" style="border-color: #dc2626; background-color: #fef2f2;">
            <h4 style="color: #dc2626;">ℹ️ Informasi:</h4>
            <p style="margin: 0;">Permohonan Anda ditolak. Silakan hubungi kantor DPMPTSP untuk informasi lebih lanjut atau ajukan permohonan baru dengan perbaikan sesuai catatan di atas.</p>
        </div>
        @elseif($newStatus === 'revision')
        <div class="notes-box" style="border-color: #f59e0b; background-color: #fffbeb;">
            <h4 style="color: #f59e0b;">📋 Tindakan Diperlukan:</h4>
            <p style="margin: 0;">Permohonan Anda memerlukan revisi. Silakan login ke sistem dan lakukan perbaikan sesuai catatan di atas.</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ $trackingUrl }}" class="button">Lihat Detail Permohonan</a>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>Jika ada pertanyaan, silakan hubungi kami di:</p>
            <p>📞 (031) 8921234 | 📧 perizinan@sidoarjokab.go.id</p>
            <p style="margin-top: 15px;">© {{ date('Y') }} Dinas Penanaman Modal dan PTSP Kabupaten Sidoarjo</p>
        </div>
    </div>
</body>
</html>
