@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad" style="padding-top: 40px; padding-bottom: 60px; background-color: #f4f6f9;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    @if($ticket->status === 'valid')
                        <div style="border-top: 5px solid #28a745; padding: 30px 20px; border-radius: 10px; background: #fff; box-shadow: 0 5px 20px rgba(0,0,0,0.08); text-align: center;">
                            <div style="width: 60px; height: 60px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fa fa-check" style="color: white; font-size: 30px;"></i>
                            </div>
                            <h3 style="font-weight: 700; color: #333; margin-bottom: 5px;">TIKET VALID</h3>
                            <span style="display: inline-block; background: #e6f4ea; color: #28a745; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-bottom: 20px;">
                                STATUS: {{ strtoupper($ticket->status) }}
                            </span>
                    @elseif($ticket->status === 'used')
                        <div style="border-top: 5px solid #dc3545; padding: 30px 20px; border-radius: 10px; background: #fff; box-shadow: 0 5px 20px rgba(0,0,0,0.08); text-align: center;">
                            <div style="width: 60px; height: 60px; background: #dc3545; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fa fa-times" style="color: white; font-size: 30px;"></i>
                            </div>
                            <h3 style="font-weight: 700; color: #333; margin-bottom: 5px;">TIKET SUDAH DIGUNAKAN</h3>
                            <span style="display: inline-block; background: #fce4e4; color: #dc3545; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-bottom: 20px;">
                                STATUS: {{ strtoupper($ticket->status) }}
                            </span>
                    @else
                        <div style="border-top: 5px solid #fd7e14; padding: 30px 20px; border-radius: 10px; background: #fff; box-shadow: 0 5px 20px rgba(0,0,0,0.08); text-align: center;">
                            <div style="width: 60px; height: 60px; background: #fd7e14; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fa fa-exclamation" style="color: white; font-size: 30px;"></i>
                            </div>
                            <h3 style="font-weight: 700; color: #333; margin-bottom: 5px;">TIKET BELUM LUNAS</h3>
                            <span style="display: inline-block; background: #fff3cd; color: #fd7e14; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-bottom: 20px;">
                                STATUS: {{ strtoupper($ticket->status) }}
                            </span>
                    @endif

                            <hr style="border-top: 1px dashed #ddd; margin-bottom: 20px;">

                            <div style="text-align: left; margin-bottom: 25px;">
                                <small style="color: #888; display: block; margin-bottom: 3px; font-size: 12px;">NAMA PEMESAN</small>
                                <h5 style="font-weight: 600; margin-bottom: 15px; font-size: 18px;">{{ $ticket->transaction->user->name ?? 'Guest' }}</h5>

                                <small style="color: #888; display: block; margin-bottom: 3px; font-size: 12px;">EVENT & TIKET</small>
                                <h5 style="font-weight: 600; font-size: 16px; margin-bottom: 2px;">{{ $ticket->typeTicket->event->title ?? 'Event Tidak Ditemukan' }}</h5>
                                <p style="color: #f1592a; font-weight: 600; font-size: 14px; margin-bottom: 15px;">{{ $ticket->typeTicket->name ?? 'Unknown Ticket' }}</p>

                                <small style="color: #888; display: block; margin-bottom: 3px; font-size: 12px;">KODE TIKET</small>
                                <p style="font-family: monospace; font-size: 16px; font-weight: bold; background: #f8f9fa; padding: 10px; border-radius: 5px; text-align: center; border: 1px solid #eee;">
                                    {{ $ticket->qr_code ?? 'TIDAK ADA KODE' }}
                                </p>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @if($ticket->status === 'valid')
                                    <form action="{{ route('ticket.process_scan', $ticket->qr_code) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="primary-btn" style="width: 100%; border: none; padding: 12px; border-radius: 8px; font-weight: bold;">
                                            Konfirmasi Check-In
                                        </button>
                                    </form>
                                @endif
                                <a href="/home" class="site-btn" style="width: 100%; background: #fff; color: #666; border: 1px solid #ddd; padding: 12px; border-radius: 8px; text-align: center; font-weight: bold;">
                                    Batal / Kembali
                                </a>
                            </div>

                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection
