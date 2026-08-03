@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Request Stock Transfer</h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('stock-transfers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Barang</label>
            <select name="barang_id" class="form-select" required>
                <option value="">-- Pilih barang --</option>
                @foreach ($barangList as $b)
                {{-- adjust nama_barang if your column name differs --}}
                <option value="{{ $b->id }}" @selected(old('barang_id')==$b->id)>{{ $b->nama_barang }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">From Warehouse</label>
                <select name="from_warehouse_id" class="form-select" required>
                    <option value="">-- Select source --</option>
                    @foreach ($warehouses as $w)
                    <option value="{{ $w->id }}" @selected(old('from_warehouse_id')==$w->id)>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">To Warehouse</label>
                <select name="to_warehouse_id" class="form-select" required>
                    <option value="">-- Select destination --</option>
                    @foreach ($warehouses as $w)
                    <option value="{{ $w->id }}" @selected(old('to_warehouse_id')==$w->id)>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes (optional)</label>
            <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
        <a href="{{ route('stock-transfers.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection