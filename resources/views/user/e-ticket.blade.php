@extends('user.layouts.master')

@section('content')
<style>
    .ticket-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 15px;
        background-color: #f4f7f6;
        min-height: 100vh;
    }

    .ticket-card {
        background: #ffffff;
        width: 100%;
        max-width: 800px;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        display: flex;
        flex-direction: row;
        margin-bottom: 30px;
        position: relative;
    }

    .ticket-left {
        background: #f1592a;
        color: white;
        padding: 30px;
        width: 30%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-right: 2px dashed rgba(255, 255, 255, 0.6);
    }

    .ticket-right {
        padding: 30px 40px;
        width: 70%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .ticket-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .event-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .ticket-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #222;
        margin: 0;
    }

    .ticket-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 10px;
    }

    .qr-container {
        padding: 10px;
        background: white;
        border-radius: 8px;
        display: inline-block;
    }
    
    .btn-print {
        background-color: #f1592a;
        color: #fff;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-print:hover {
        background-color: #d1471f;
    }
    
    .ticket-card::before, .ticket-card::after {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        background-color: #f4f7f6;
        border-radius: 50%;
        left: 30%;
        transform: translateX(-50%);
        z-index: 10;
    }

    .ticket-card::before {
        top: -20px;
        box-shadow: inset 0 -5px 10px rgba(0,0,0,0.05);
    }

    .ticket-card::after {
        bottom: -20px;
        box-shadow: inset 0 5px 10px rgba(0,0,0,0.05);
    }
    
    @media (max-width: 768px) {
        .ticket-card {
            flex-direction: column;
        }
        .ticket-left {
            width: 100%;
            border-right: none;
            border-bottom: 2px dashed rgba(255, 255, 255, 0.6);
            padding: 40px 20px;
        }
        .ticket-right {
            width: 100%;
        }
        .ticket-card::before, .ticket-card::after {
            display: none; 
        }
    }

    @media print {
        @page {
            margin: 1cm;
        }

        header, footer, nav, .site-header, .footer-section, .breadcrumb-section, aside {
            display: none !important;
        }

        .no-print {
            display: none !important;
        }
        body {
            background-color: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .ticket-wrapper {
            background-color: transparent !important;
            padding: 0 !important;
            min-height: auto !important;
        }
        .ticket-card {
            box-shadow: none !important;
            border: 2px solid #ddd !important;
            max-width: 100% !important; 
            width: 100% !important;
            margin: 0 0 30px 0 !important;
            page-break-inside: avoid;
            
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .ticket-card::before, .ticket-card::after {
            display: none !important;
        }

        .ticket-left {
            border-right: 2px dashed #ddd !important; 
        }
    }
</style>

<div class="ticket-wrapper">
    <div class="container">
        <div class="text-center mb-5 no-print">
            <h2 style="font-weight: 700; color:#333;">E-Ticket Anda</h2>
            <p>Tunjukkan E-Ticket ini saat melakukan registrasi ulang di lokasi acara.</p>
        </div>

        <!-- User Tickets Iteration -->
        @foreach($allTickets as $ticket)
            <div class="ticket-card mx-auto">
                <div class="ticket-left">
                    <h3 style="font-weight: 800; margin-bottom: 5px; font-size: 28px;">{{ strtoupper($ticket->typeTicket->name) }}</h3>
                    <p style="margin-bottom: 15px; font-size: 14px; opacity: 0.9;">ADMISSION TICKET</p>
                    
                    <div class="qr-container mt-2">
                        {!! QrCode::size(120)->generate(url('/scan/' . $ticket->qr_code)) !!}
                    </div>
                </div>

                <div class="ticket-right">
                    <div class="ticket-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 class="event-title">{{ strtoupper($ticket->typeTicket->event->title ?? 'Nama Event Belum Ditentukan') }}</h4>
                        @if($ticket->status === 'valid')
                            <span style="background-color: #28a745; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">VALID</span>
                        @elseif($ticket->status === 'used')
                            <span style="background-color: #dc3545; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">SUDAH DIGUNAKAN</span>
                        @else
                            <span style="background-color: #6c757d; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">{{ strtoupper($ticket->status) }}</span>
                        @endif
                    </div>

                    <div class="ticket-info-grid">
                        <!-- Buyer Name -->
                        <div class="info-item">
                            <span class="info-label">Nama Pemesan</span>
                            <span class="info-value">{{ $ticket->transaction->user->name ?? 'Guest' }}</span>
                        </div>
                        
                        <!-- Ticket Code Format: TCK-0001 -->
                        <div class="info-item">
                            <span class="info-label">Kode Tiket</span>
                            <span class="info-value" style="color: #f1592a;">
                                TCK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <!-- Event Date & Time -->
                        <div class="info-item">
                            <span class="info-label">Tanggal Acara</span>
                            <span class="info-value">
                                {{ \Carbon\Carbon::parse($ticket->typeTicket->event->date ?? now())->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <!-- Event Location -->
                        <div class="info-item">
                            <span class="info-label">Lokasi</span>
                            <span class="info-value">
                                @if($ticket->typeTicket->event->location)
                                    {{ $ticket->typeTicket->event->location->venue_name }}, {{ $ticket->typeTicket->event->location->city }}
                                @else
                                    Venue Acara Sesuai Jadwal
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="ticket-footer">
                        <span class="info-label mb-0" style="font-size: 10px;">ID validasi QR: {{ $ticket->qr_code }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="text-center mt-4 mb-5 no-print">
            <button onclick="window.print()" class="btn-print">
                Cetak / Simpan PDF Tiket
            </button>
        </div>
    </div>
</div>
@endsection
