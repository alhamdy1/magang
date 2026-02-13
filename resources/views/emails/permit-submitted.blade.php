<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Izin Diterima</title>
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
        .tracking-box {
            background-color: #dbeafe;
            border: 2px solid #1e40af;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .tracking-box label {
            display: block;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .tracking-number {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 2px;
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
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
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

        <p>Permohonan izin reklame Anda telah berhasil diterima dan sedang dalam proses verifikasi. Berikut adalah detail permohonan Anda:</p>

        <div class="tracking-box">
            <label>Nomor Tracking</label>
            <div class="tracking-number">{{ $permit->tracking_number }}</div>
            <p style="margin: 10px 0 0; font-size: 12px; color: #666;">Simpan nomor ini untuk melacak status permohonan</p>
        </div>

        <table class="info-table">
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>{{ $permit->created_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Jenis Reklame</td>
                <td>{{ ucfirst(str_replace('_', ' ', $permit->klasifikasi)) }}</td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>{{ $permit->lokasi_alamat }}</td>
            </tr>
            <tr>
                <td>Ukuran/Jumlah</td>
                <td>{{ $permit->ukuran_jumlah }}</td>
            </tr>
            <tr>
                <td>Status Saat Ini</td>
                <td><span style="color: #f59e0b; font-weight: 600;">📋 Menunggu Verifikasi</span></td>
            </tr>
        </table>

        <div style="text-align: center;">
            <a href="{{ $trackingUrl }}" class="button">Lacak Status Permohonan</a>
        </div>

        <div class="warning">
            <strong>⚠️ Penting:</strong><br>
            • Simpan email ini sebagai bukti pengajuan permohonan<br>
            • Gunakan nomor tracking untuk melacak status permohonan<br>
            • Proses verifikasi membutuhkan waktu 3-7 hari kerja<br>
            • Anda akan menerima notifikasi email setiap ada perubahan status
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
