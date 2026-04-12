@extends('user.layouts.master')

@section('content')
    <section class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Event</h2>
                        <div class="bt-option">
                            <a href="/">Home</a>
                            <span>Our Event</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="speaker-section spad">
        <div class="container">
            <div class="row">
                @foreach($events as $event)
                    <div class="col-lg-12 mb-4">
                        <div class="speaker-item">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="si-pic">
                                        @if($event->banner_url)
                                            <img src="{{ asset($event->banner_url) }}" alt="">
                                        @else
                                            <div style="width:100%; height:200px; background:#eee; display:flex; align-items:center; justify-content:center;">
                                                <span>No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="si-text">
                                        <div class="si-title">
                                            <h4>{{ $event->title }}</h4>
                                            <span>{{ $event->date }}</span>
                                        </div>
                                        <p>
                                            {{ $event->description ?? 'No description available' }}
                                        </p>
                                        <div class="mt-3">
                                            <a href="{{ route('checkout.create', ['event_id' => $event->id]) }}" class="primary-btn">
                                                Pesan Tiket
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
