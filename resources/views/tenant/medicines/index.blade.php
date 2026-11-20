@extends('layouts.tenant')
@section('title','Medicines')
@section('page_title', 'Medicines')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Medicines</h1></div><a href="{{ route('tenant.medicines.create') }}" class="btn btn-sehat-primary">Add Medicine</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div><div class="col-md-2"><button class="btn btn-sehat-primary">Search</button></div></form>
<table class="table sehat-table"><thead><tr><th>Name</th><th>Category</th><th>Manufacturer</th><th>Stock</th><th>Price</th><th></th></tr></thead><tbody>
@forelse($medicines as $medicine)
<tr><td>{{ $medicine->name }}</td><td>{{ $medicine->category?->name?:'—' }}</td><td>{{ $medicine->manufacturer }}</td><td>{{ $medicine->stock }}</td><td>₹{{ number_format($medicine->price,2) }}</td>
<td class="text-end"><a href="{{ route('tenant.medicines.edit',$medicine) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form class="d-inline" method="POST" action="{{ route('tenant.medicines.destroy',$medicine) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
@empty<tr><td colspan="6" class="text-center text-muted">No medicines</td></tr>@endforelse
</tbody></table>{{ $medicines->links() }}</div>
@endsection