@extends('layouts.admin')

@section('title', 'Add Event')

@section('content')
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Event Details</h3>
                </div>
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Event Title</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g.: Summer Music Festival" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Category</label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label>Primary Location</label>
                                <select name="location_id" class="form-control @error('location_id') is-invalid @enderror" required>
                                    <option value="">-- Select Location --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->venue_name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Status</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="Upcoming" {{ old('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @if(auth()->user()->role == 1)
                            <div class="col-sm-6">
                                <label>Organizer</label>
                                <select name="organizer_id" class="form-control @error('organizer_id') is-invalid @enderror" required>
                                    <option value="">-- Select Organizer --</option>
                                    @foreach($organizers as $org)
                                        <option value="{{ $org->id }}" {{ old('organizer_id', auth()->id()) == $org->id ? 'selected' : '' }}>
                                            {{ $org->name }} ({{ $org->role == 1 ? 'Admin' : 'Organizer' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('organizer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Event Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Enter detailed description...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label>Event Banner (Opsional)</label>
                            <div class="custom-file">
                                <input type="file" name="banner_url" class="custom-file-input @error('banner_url') is-invalid @enderror" id="customFile" accept="image/jpeg,image/png,image/jpg" onchange="document.getElementById('file-label').innerHTML = this.files[0].name">
                                <label class="custom-file-label" id="file-label" for="customFile">Pilih Gambar (JPG/PNG)</label>
                            </div>
                            @error('banner_url')<div class="text-danger mt-1" style="font-size: 80%;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-default mr-2">Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.custom-file-input').on('change', function() {
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });
            });
        </script>
    @endpush
@endsection
