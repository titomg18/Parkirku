<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karcis Parkir - {{ $parking->ticket_code }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700;14..32,800&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100%; }
        .page-wrapper { display: flex; justify-content: center; align-items: flex-start; padding: 24px 16px 32px; }
        .karcis { width: 300px; background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); position: relative; }
        .karcis-header { background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 100%); padding: 18px 20px; text-align: center; color: white; border-radius: 16px 16px 0 0; }
        .karcis-body { padding: 16px 20px; }
        .karcis-divider { position: relative; height: 24px; display: flex; align-items: center; margin: 0 -2px; }
        .karcis-divider::before { content: ''; position: absolute; left: -12px; width: 24px; height: 24px; background: #f0f4f8; border-radius: 50%; z-index: 2; }
        .karcis-divider::after { content: ''; position: absolute; right: -12px; width: 24px; height: 24px; background: #f0f4f8; border-radius: 50%; z-index: 2; }
        .dashed-line { border: none; border-top: 2px dashed #e2e8f0; width: 100%; margin: 0 12px; position: relative; z-index: 1; }
        .karcis-footer { background: #f8fafc; padding: 12px 20px; text-align: center; border-top: 1px solid #e2e8f0; border-radius: 0 0 16px 16px; }
        .plat { font-family: 'Courier Prime', monospace; font-size: 24px; font-weight: 700; letter-spacing: 0.12em; color: #1e293b; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 7px; font-size: 12px; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #1e293b; text-align: right; }
        .barcode-wrap { text-align: center; margin-top: 6px; padding: 0; overflow: hidden; width: 100%; }
        .barcode-wrap svg { display: block; max-width: 100%; height: auto; margin: 0 auto; }
        @media print {
            @page { size: 80mm auto; margin: 4mm; }
            html, body { background: white !important; }
            .page-wrapper { padding: 0; }
            .karcis { width: 100%; box-shadow: none; border-radius: 0; }
            .karcis-header { border-radius: 0; }
            .karcis-footer { border-radius: 0; }
            .karcis-divider::before, .karcis-divider::after { background: white !important; }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="karcis" id="karcis">
        <div class="karcis-header">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px">
                <div style="background:rgba(255,255,255,0.2);border-radius:10px;padding:7px 9px">
                    <i class="fas fa-parking" style="font-size:20px"></i>
                </div>
                <div style="text-align:left">
                    <p style="font-size:19px;font-weight:800;line-height:1">ParkirKu</p>
                    <p style="font-size:10px;opacity:0.8">Sistem Manajemen Parkir</p>
                </div>
            </div>
            <p style="font-size:10px;opacity:0.7;margin-top:4px;letter-spacing:0.05em">KARCIS MASUK KENDARAAN</p>
        </div>

        <div class="karcis-body">
            <div style="text-align:center;margin-bottom:14px;padding:10px 12px;background:#f1f5ff;border-radius:10px;border:2px dashed #bfdbfe">
                <p style="font-size:9px;color:#64748b;margin-bottom:3px;text-transform:uppercase;letter-spacing:0.08em">Nomor Kendaraan</p>
                <p class="plat">{{ $parking->no_kendaraan }}</p>
            </div>

            <div>
                <div class="info-row">
                    <span class="info-label">Jenis</span>
                    <span class="info-value" style="text-transform:capitalize">
                        @php $icon = match($parking->jenis_kendaraan){'mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-motorcycle'}; @endphp
                        <i class="fas {{ $icon }}" style="margin-right:3px"></i>{{ $parking->jenis_kendaraan }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tgl. Masuk</span>
                    <span class="info-value">{{ $parking->waktu_masuk->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jam Masuk</span>
                    <span class="info-value" style="font-size:13px;color:#1d4ed8;font-weight:700">{{ $parking->waktu_masuk->format('H:i') }} WIB</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tarif/jam</span>
                    <span class="info-value">Rp {{ number_format(match($parking->jenis_kendaraan){'mobil'=>5000,'truk'=>10000,default=>2000}, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Petugas</span>
                    <span class="info-value">{{ $parking->petugas?->name ?? '-' }}</span>
                </div>
            </div>

            <div class="karcis-divider" style="margin:12px -20px">
                <hr class="dashed-line">
            </div>

            <div class="barcode-wrap">
                <p style="font-size:9px;color:#94a3b8;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.08em">Scan untuk keluar</p>
                <svg id="barcode"></svg>
                <p style="font-family:'Courier Prime',monospace;font-size:10px;color:#64748b;margin-top:4px;letter-spacing:0.06em">{{ $parking->ticket_code }}</p>
            </div>
        </div>

        <div class="karcis-footer">
            <p style="font-size:9px;color:#94a3b8">Simpan karcis ini. Tunjukkan saat keluar.</p>
            <p style="font-size:9px;color:#94a3b8;margin-top:2px">Kehilangan karcis dikenakan denda.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        JsBarcode("#barcode", "{{ $parking->ticket_code }}", {
            format:       "CODE128",
            width:        1.4,
            height:       45,
            displayValue: false,
            margin:       4,
            background:   "transparent",
            lineColor:    "#1e293b"
        });
        // Responsive: pastikan SVG tidak melebihi wrapper
        var svg = document.getElementById('barcode');
        if (svg) {
            svg.removeAttribute('width');
            svg.setAttribute('width', '100%');
        }
    });

    @if(request()->get('print'))
        window.onload = () => setTimeout(() => window.print(), 500);
    @endif
</script>
</body>
</html>