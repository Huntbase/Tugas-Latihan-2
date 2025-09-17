@extends('layout.master')

@section('konten')
<div class="container mt-5">
    <h3>Pilih Gudang</h3>

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('warehouse.setActive') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="warehouse_id" class="form-label">Gudang</label>
            <select name="warehouse_id" id="warehouse_id" class="form-select">
                @foreach($warehouses as $wh)
                <option value="{{ $wh->warehouse_id }}">
                    {{ $wh->name }} - {{ $wh->location }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Masuk</button>
    </form>
</div>
@endsection