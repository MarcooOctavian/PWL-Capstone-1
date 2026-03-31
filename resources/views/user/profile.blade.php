@extends('user.layouts.master')

@section('content')
    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>My Profile & Tickets</h2>
                        <div class="bt-option">
                            <a href="/home">Home</a>
                            <span>Profile</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="schedule-table-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Riwayat Transaksi</h2>
                        <p>Daftar tiket event yang sudah Anda pesan.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="schedule-table-tab">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="schedule-table-content">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>Nama Event</th>
                                            <th>Tanggal Pesan</th>
                                            <th>Jenis Tiket</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="hover-bg">
                                                <h5>Maranatha Tech Conference 2026</h5>
                                            </td>
                                            <td class="hover-bg">
                                                <p>29 Maret 2026</p>
                                            </td>
                                            <td class="hover-bg">
                                                <p>VIP (1 Tiket)</p>
                                            </td>
                                            <td class="hover-bg">
                                                <span style="color: #28a745; font-weight: bold;">Paid</span>
                                            </td>
                                            <td class="hover-bg">
                                                <a href="/e-ticket" class="primary-btn" style="padding: 10px 20px;">Lihat E-Ticket</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="hover-bg">
                                                <h5>SobatKost Grand Launching</h5>
                                            </td>
                                            <td class="hover-bg">
                                                <p>28 Maret 2026</p>
                                            </td>
                                            <td class="hover-bg">
                                                <p>Regular (2 Tiket)</p>
                                            </td>
                                            <td class="hover-bg">
                                                <span style="color: #ffc107; font-weight: bold;">Pending</span>
                                            </td>
                                            <td class="hover-bg">
                                                <a href="#" class="primary-btn" style="background: #333; padding: 10px 20px;">Bayar Sekarang</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
