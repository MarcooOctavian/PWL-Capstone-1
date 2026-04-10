<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Anda</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #f1592a; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; color: #333333; line-height: 1.6; }
        .qr-container { text-align: center; margin: 20px 0; padding: 20px; border: 2px dashed #f1592a; border-radius: 8px; background-color: #fff9f7; }
        .qr-container img { width: 200px; height: 200px; }
        .ticket-details { background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .ticket-details table { width: 100%; border-collapse: collapse; }
        .ticket-details th { text-align: left; padding: 8px 0; color: #555555; width: 40%; border-bottom: 1px solid #eeeeee; }
        .ticket-details td { padding: 8px 0; font-weight: bold; border-bottom: 1px solid #eeeeee; }
        .footer { background-color: #f1f1f1; padding: 20px; text-align: center; font-size: 12px; color: #777777; }
        .ticket-divider { border-top: 2px dashed #ddd; margin: 30px 0; }
    </style>
</head>
<body>

@php
    $firstTicket = $tickets->first();
    $eventTitle = $firstTicket ? ($firstTicket->typeTicket->event->title ?? 'Event Registration') : 'Event Registration';
    $userName = $firstTicket ? ($firstTicket->transaction->user->name ?? 'Peserta') : 'Peserta';
@endphp

<div class="email-wrapper">
    <div class="header">
        <h1>E-Ticket Terkonfirmasi!</h1>
        <p style="margin-top: 5px; opacity: 0.9;">{{ strtoupper($eventTitle) }}</p>
    </div>

    <div class="content">
        <p>Halo, <strong>{{ $userName }}</strong>,</p>
        <p>Terima kasih atas pembayaran Anda. Tiket Anda telah berhasil diterbitkan. Harap tunjukkan QR Code di bawah ini kepada petugas saat memasuki area acara.</p>

        @foreach($tickets as $index => $ticket)

            <div class="qr-container">
                <p style="margin-top: 0; font-size: 16px; font-weight: bold; color: #f1592a;">TIKET {{ $index + 1 }}</p>
                <img src="https://quickchart.io/qr?text={{ urlencode($ticket->qr_code) }}&size=200&margin=2" alt="QR Code Tiket">
                <h3 style="margin-bottom: 0; letter-spacing: 2px;">{{ $ticket->qr_code }}</h3>
            </div>

            <div class="ticket-details">
                <table>
                    <tr>
                        <th>Jenis Tiket</th>
                        <td>{{ $ticket->typeTicket->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Event</th>
                        <td>{{ \Carbon\Carbon::parse($ticket->typeTicket->event->date ?? now())->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $ticket->typeTicket->event->location->name ?? 'TBA' }}</td>
                    </tr>
                </table>
            </div>

            @if(!$loop->last)
                <div class="ticket-divider"></div>
            @endif

        @endforeach
        <p style="font-size: 14px; color: #e74c3c; margin-top: 30px;"><em>Catatan: Jangan bagikan QR Code ini kepada siapa pun. Satu QR Code hanya berlaku untuk satu kali scan.</em></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Event Management System. Hak Cipta Dilindungi.</p>
        <p>Email ini dibuat otomatis oleh sistem. Harap tidak membalas email ini.</p>
    </div>
</div>
</body>
</html>
