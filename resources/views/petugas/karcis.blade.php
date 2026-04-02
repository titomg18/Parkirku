{{-- resources/views/petugas/karcis.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karcis Parkir - {{ $parking->ticket_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.6/JsBarcode.all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700;14..32,800&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; }

        /* ===== KARCIS STYLES ===== */
        .karcis {
            width: 320px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            position: relative;
        }
        .karcis-header {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 100%);
            padding: 20px;
            text-align: center;
            color: white;
        }
        .karcis-body { padding: 20px; }
        .karcis-divider {
            position: relative;
            margin: 0;
            height: 24px;
            background: white;
            display: flex;
            align-items: center;
        }
        .karcis-divider::before {
            content: '';
            position: absolute;
            left: -14px;
            width: 24px; height: 24px;
            background: #f0f4f8;
            border-radius: 50%;
        }
        .karcis-divider::after {
            content: '';
            position: absolute;
            right: -14px;
            width: 24px; height: 24px;
            background: #f0f4f8;
            border-radius: 50%;
        }
        .dashed-line {
            border: none;
            border-top: 2px dashed #e2e8f0;
            width: 100%;
        }
        .karcis-footer {
            background: #f8fafc;
            padding: 16px 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .plat {
            font-family: 'Courier Prime', monospace;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #1e293b;
        }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #1e293b; text-align: right; }

        /* ===== PRINT STYLES ===== */
        @media print {
            body { background: white !important; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-area { display: flex; justify-content: center; align-items: flex-start; padding-top: 0; }
            .karcis { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body>

    {{-- Tombol Cetak (tidak ikut print) --}}
    <div class="no-print min-h-screen flex flex-col items-center justify-center p-6 gap-4">
        <div class="flex gap-3 mb-4">
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl flex items-center gap-2 shadow transition">
                <i class="fas fa-print"></i> Cetak Karcis
            </button>
            <a href="{{ route('petugas.dashboard') }}"
                class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-6 py-2.5 rounded-xl flex items-center gap-2 shadow-sm transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <p class="text-gray-400 text-sm mb-2">Preview karcis parkir:</p>
    </div>

    {{-- Area Karcis (yang akan di-print) --}}
    <div class="print-area flex justify-center pb-10 -mt-4">
        <div class="karcis" id="karcis">

            {{-- Header --}}
            <div class="karcis-header">
                <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px">
                    <div style="background:rgba(255,255,255,0.2);border-radius:10px;padding:8px 10px">
                        <i class="fas fa-parking" style="font-size:22px"></i>
                    </div>
                    <div style="text-align:left">
                        <p style="font-size:20px;font-weight:800;line-height:1">ParkirKu</p>
                        <p style="font-size:11px;opacity:0.8">Sistem Manajemen Parkir</p>
                    </div>
                </div>
                <p style="font-size:11px;opacity:0.7;margin-top:4px">KARCIS MASUK KENDARAAN</p>
            </div>

            {{-- Body --}}
            <div class="karcis-body">

                {{-- No Kendaraan --}}
                <div style="text-align:center;margin-bottom:16px;padding:12px;background:#f1f5ff;border-radius:10px;border:2px dashed #bfdbfe">
                    <p style="font-size:10px;color:#64748b;margin-bottom:4px;text-transform:uppercase;letter-spacing:0.08em">Nomor Kendaraan</p>
                    <p class="plat">{{ $parking->no_kendaraan }}</p>
                </div>

                {{-- Info --}}
                <div>
                    <div class="info-row">
                        <span class="info-label">Jenis</span>
                        <span class="info-value" style="text-transform:capitalize">
                            @php $icon = match($parking->jenis_kendaraan){'mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-motorcycle'}; @endphp
                            <i class="fas {{ $icon }}" style="margin-right:4px"></i>{{ $parking->jenis_kendaraan }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tgl. Masuk</span>
                        <span class="info-value">{{ $parking->waktu_masuk->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jam Masuk</span>
                        <span class="info-value" style="font-size:15px;color:#1d4ed8">{{ $parking->waktu_masuk->format('H:i') }} WIB</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tarif/jam</span>
                        <span class="info-value">
                            Rp {{ number_format(match($parking->jenis_kendaraan){'mobil'=>5000,'truk'=>10000,default=>2000}, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Petugas</span>
                        <span class="info-value">{{ $parking->petugas?->name ?? '-' }}</span>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="karcis-divider" style="margin:16px -20px">
                    <hr class="dashed-line">
                </div>

                {{-- Barcode --}}
                <div style="text-align:center;margin-top:4px">
                    <p style="font-size:10px;color:#94a3b8;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.08em">Scan untuk keluar</p>
                    <svg id="barcode"></svg>
                    <p style="font-family:'Courier Prime',monospace;font-size:11px;color:#64748b;margin-top:4px;letter-spacing:0.08em">
                        {{ $parking->ticket_code }}
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="karcis-footer">
                <p style="font-size:10px;color:#94a3b8">Simpan karcis ini. Tunjukkan saat keluar.</p>
                <p style="font-size:10px;color:#94a3b8;margin-top:2px">Kehilangan karcis dikenakan denda.</p>
            </div>

        </div>
    </div>

    <script>
        JsBarcode("#barcode", "{{ $parking->ticket_code }}", {
            format: "CODE128",
            width: 1.8,
            height: 50,
            displayValue: false,
            margin: 0,
            background: "transparent",
            lineColor: "#1e293b"
        });

        // Auto print jika ada param ?print=1
        @if(request()->get('print'))
            window.onload = () => setTimeout(() => window.print(), 500);
        @endif
    </script>
</body>
</html>