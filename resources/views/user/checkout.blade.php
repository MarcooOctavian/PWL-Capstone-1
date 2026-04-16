@extends('user.layouts.master')

@section('content')
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    <div class="section-title text-center">
                        <h2>Checkout Tiket</h2>
                        <p>Silakan lengkapi detail pesanan dan data diri Anda di bawah ini.</p>
                    </div>

                    <!-- Error Messages Display -->
                    @if ($errors->any())
                        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <strong>Transaksi Gagal!</strong>
                            <ul style="margin-top: 10px; margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <strong>Perhatian!</strong> {{ session('error') }}

                            @if(str_contains(session('error'), 'Waiting List'))
                                <form action="{{ route('waiting-list.join') }}" method="POST" style="margin-top: 15px;">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <input type="hidden" name="type_ticket_id" value="{{ old('type_ticket_id') }}">
                                    <input type="hidden" name="name" value="{{ old('name') }}">
                                    <input type="hidden" name="email" value="{{ old('email') }}">

                                    <button type="submit" class="btn btn-primary" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                                        <i class="fas fa-list"></i> Masukkan Saya ke Waiting List
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('checkout.store') }}" method="POST" class="comment-form">
                        @csrf
                        <div class="row" style="background: #fdfdfd; padding: 30px; border-radius: 8px; border: 1px solid #eee;">

                            <div class="col-lg-12">
                                <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #111; border-bottom: 2px solid #f1592a; padding-bottom: 8px;">1. Pilihan Tiket</h4>
                            </div>

                            <input type="text" value="{{ $event->title }}" readonly
                                   style="width: 100%; height: 50px; margin-bottom: 20px; padding: 0 20px; background: #f5f5f5; border: 1px solid #e1e1e1; border-radius: 4px; font-size: 16px;">
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <select name="schedule_id" required
                                    style="width: 100%; height: 50px; margin-bottom: 20px; padding: 0 20px; border: 1px solid #e1e1e1; border-radius: 4px; font-size: 16px;">
                                <option value="">-- Pilih Jadwal Event --</option>
                                <!-- Schedules Iteration -->
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                        |
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        |
                                        {{ $schedule->location->venue_name ?? 'Lokasi tidak tersedia' }}
                                        ({{ $schedule->location->city ?? '-' }})
                                    </option>
                                @endforeach
                            </select>

                            <select name="type_ticket_id" id="ticket_select" required
                                    style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; border-radius: 4px;">

                                <option value="">-- Pilih Jenis Tiket --</option>
                                <!-- Ticket Types Iteration -->
                                @foreach($typeTickets as $type)
                                    <option
                                        value="{{ $type->id }}"
                                        data-max="{{ $type->max_purchase }}"
                                        data-stock="{{ $type->stock }}"
                                        {{ old('type_ticket_id') == $type->id ? 'selected' : '' }}
                                    >
                                        {{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }}
                                        (Sisa: {{ $type->stock > 0 ? $type->stock : 'Habis' }})
                                    </option>
                                @endforeach
                            </select>

                            <select name="qty" id="qty_select" required
                                    style="width: 100%; height: 50px; margin-bottom: 30px; padding-left: 20px; border: 1px solid #e1e1e1; border-radius: 4px;">
                                <option value="">-- Pilih Jumlah Tiket --</option>
                            </select>

                            <div class="col-lg-12">
                                <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #111; border-bottom: 2px solid #f1592a; padding-bottom: 8px;">2. Identitas Kontak Utama</h4>
                            </div>

                            <div class="col-lg-6">
                                <input type="text" name="name" placeholder="Nama Lengkap (sesuai KTP)" value="{{ old('name') }}" required style="border-radius: 4px; margin-bottom: 20px;">
                            </div>
                            <div class="col-lg-6">
                                <input type="email" name="email" placeholder="Alamat Email (untuk E-Ticket)" value="{{ old('email') }}" required style="border-radius: 4px; margin-bottom: 30px;">
                            </div>

                            <div class="col-lg-12">
                                <h4 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #111; border-bottom: 2px solid #f1592a; padding-bottom: 8px;">3. Metode Pembayaran</h4>
                            </div>

                            <div class="col-lg-12">
                                <select name="payment_method" id="payment_method" required onchange="togglePaymentInput()" style="width: 100%; height: 50px; margin-bottom: 20px; padding-left: 20px; border: 1px solid #e1e1e1; color: #666666; font-size: 16px; border-radius: 4px;">
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS (Scan Barcode Umum)</option>
                                    <option value="ovo" {{ old('payment_method') == 'ovo' ? 'selected' : '' }}>OVO</option>
                                    <option value="dana" {{ old('payment_method') == 'dana' ? 'selected' : '' }}>DANA</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit / Debit</option>
                                </select>
                            </div>

                            <div class="col-lg-12" id="dynamic_payment_input" style="display: {{ old('payment_credential') ? 'block' : 'none' }};">
                                <input type="text" name="payment_credential" id="payment_credential" value="{{ old('payment_credential') }}" placeholder="" style="border-radius: 4px; margin-bottom: 20px; width: 100%;">
                            </div>

                            <div class="col-lg-12 text-center mt-4">
                                <button type="submit" class="site-btn" style="width: 100%; border-radius: 4px; font-size: 18px; padding: 15px 0;">Lanjutkan ke Verifikasi</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            togglePaymentInput();

            var ticketSelect = document.getElementById('ticket_select');
            if (ticketSelect.value) {
                ticketSelect.dispatchEvent(new Event('change'));
            }
        });

        // JavaScript Logic to Populate Qty Dropdown
        document.getElementById('ticket_select').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var qtySelect = document.getElementById('qty_select');

            // Clear previously selected qty
            qtySelect.innerHTML = '<option value="">-- Pilih Jumlah Tiket --</option>';

            if (!selectedOption.value) return;

            var maxPurchase = parseInt(selectedOption.getAttribute('data-max'));
            var stock = parseInt(selectedOption.getAttribute('data-stock'));

            if (stock <= 0) {
                // If sold out, show 1 to bypass to form error validation
                var opt = document.createElement('option');
                opt.value = 1;
                opt.text = "1 (Daftar Waiting List)";
                qtySelect.appendChild(opt);
                qtySelect.value = 1; // Auto select
            } else {
                // If stock available, calculate maximum limit
                var limit = Math.min(maxPurchase, stock);
                for (var i = 1; i <= limit; i++) {
                    var opt = document.createElement('option');
                    opt.value = i;
                    opt.text = i;
                    qtySelect.appendChild(opt);
                }
            }

            // Preserve old value if form fails and reloads
            var oldQty = "{{ old('qty') }}";
            if (oldQty && oldQty <= (stock <= 0 ? 1 : Math.min(maxPurchase, stock))) {
                qtySelect.value = oldQty;
            }
        });

        function togglePaymentInput() {
            var method = document.getElementById('payment_method').value;
            var inputContainer = document.getElementById('dynamic_payment_input');
            var inputField = document.getElementById('payment_credential');

            if (method === 'ovo' || method === 'dana') {
                inputContainer.style.display = 'block';
                inputField.placeholder = "Masukkan Nomor " + method.toUpperCase() + " Anda (Contoh: 0812...)";
                inputField.required = true;
            }
            else if (method === 'qris') {
                inputContainer.style.display = 'block';
                inputField.placeholder = "Masukkan Nomor WhatsApp (Untuk Bukti QRIS)";
                inputField.required = true;
            }
            else if (method === 'credit_card') {
                inputContainer.style.display = 'block';
                inputField.placeholder = "Masukkan Nomor Kartu Kredit (Simulasi: 4111-2222-...)";
                inputField.required = true;
            }
            else {
                inputContainer.style.display = 'none';
                inputField.required = false;
            }
        }
    </script>
@endsection
