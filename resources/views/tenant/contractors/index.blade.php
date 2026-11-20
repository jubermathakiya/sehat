@extends('layouts.tenant')
@section('title','Contractors')
@section('page_title', 'Contractors')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Contractors</h1></div><a href="{{ route('tenant.contractors.create') }}" class="btn btn-sehat-primary">Add Contractor</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET">
<div class="col-md-3"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div>
<div class="col-md-3"><select name="company_id" class="form-select"><option value="">All companies</option>@foreach($companies as $c)<option value="{{ $c->id }}" @selected(request('company_id')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-2"><button class="btn btn-sehat-primary">Filter</button></div>
</form>
<table class="table sehat-table"><thead><tr><th>Code</th><th>Name</th><th>Mobile</th><th>Companies</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($contractors as $contractor)
<tr>
<td><code>{{ $contractor->contractor_code }}</code></td>
<td>{{ $contractor->name }}</td>
<td>{{ $contractor->mobile }}</td>
<td>{{ $contractor->companies->pluck('name')->join(', ') }}</td>
<td><span class="badge-sehat badge-{{ $contractor->status }}">{{ $contractor->status }}</span></td>
<td class="text-end"><a href="{{ route('tenant.contractors.edit',$contractor) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form action="{{ route('tenant.contractors.destroy',$contractor) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td>
</tr>
@empty<tr><td colspan="6" class="text-center text-muted">No contractors</td></tr>@endforelse
</tbody></table>{{ $contractors->links() }}</div>
@endsection
