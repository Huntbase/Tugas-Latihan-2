@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pilih Warehouse</h3>
    <form action="{{ route('warehouse.setActive') }}" method="POST">
        @csrf
        <select name="warehouse_id" class="form-control" required>
            @foreach($warehouses as $w)
            <option value="{{ $w->warehouse_id }}">{{ $w->name_id }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary mt-3">Gunakan</button>
    </form>
</div>
@endsection