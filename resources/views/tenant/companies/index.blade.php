@extends('layouts.tenant')
@section('title','Companies')
@section('page_title', 'Companies')
@section('content')
<div class="sehat-page-header"><div><h1 class="page-title">Companies</h1></div><a href="{{ route('tenant.companies.create') }}" class="btn btn-sehat-primary">Add Company</a></div>
<div class="sehat-card">
<form class="row g-2 mb-3" method="GET"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search"></div><div class="col-md-2"><button class="btn btn-sehat-primary">Search</button></div></form>
<table class="table sehat-table"><thead><tr><th>Name</th><th>Contractors</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($companies as $company)
<tr>
<td>{{ $company->name }}</td>
<td>@if($company->contractors_count>0)<a href="{{ route('tenant.contractors.index',['company_id'=>$company->id]) }}">{{ $company->contractors_count }}</a>@else 0 @endif</td>
<td><span class="badge-sehat badge-{{ $company->status }}">{{ $company->status }}</span></td>
<td class="text-end">
<a href="{{ route('tenant.companies.edit',$company) }}" class="btn btn-sm btn-outline-primary">Edit</a>
@if($company->contractors_count>0)
<button class="btn btn-sm btn-outline-danger" disabled title="Cannot delete: contractors are associated">Delete</button>
@else
<form action="{{ route('tenant.companies.destroy',$company) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete company?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
@endif
</td></tr>
@empty<tr><td colspan="4" class="text-center text-muted">No companies</td></tr>@endforelse
</tbody></table>{{ $companies->links() }}</div>
@endsection
