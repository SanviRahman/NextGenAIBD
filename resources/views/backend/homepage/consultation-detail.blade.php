@extends('backend.layouts.master')
@section('content')
<section class="container-fluid p-4" style="max-width:1060px">
    <a href="{{ route('admin.consultation.index') }}" class="btn btn-outline-secondary mb-3">← Back to Consultation Inbox</a>
    <h1 class="h2 fw-bold mb-3">Consultation request</h1>
    @if(session('consultation_admin_success'))<div class="alert alert-success">{{ session('consultation_admin_success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="card mb-4"><div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><strong>Name</strong><p>{{ $lead->name }}</p></div>
            <div class="col-md-6"><strong>Company</strong><p>{{ $lead->company_name ?: '—' }}</p></div>
            <div class="col-md-6"><strong>Designation</strong><p>{{ $lead->designation ?: '—' }}</p></div>
            <div class="col-md-6"><strong>Submitted at</strong><p>{{ $lead->created_at?->format('d M Y, H:i') }}</p></div>
            <div class="col-md-6"><strong>Phone</strong><p>{{ $lead->phone ?: '—' }}</p></div>
            <div class="col-md-6"><strong>Email</strong><p>{{ $lead->email ?: '—' }}</p></div>
            <div class="col-12"><strong>Message</strong><p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $lead->message }}</p></div>
            <div class="col-12"><strong>Tracking ID</strong><p class="text-muted font-monospace">{{ $lead->public_id }}</p></div>
        </div>
    </div></div>
    <div class="card"><div class="card-header"><h2 class="h5 mb-0">Manage request</h2></div><div class="card-body">
        <form method="POST" action="{{ route('admin.consultation.requests.update', $lead) }}">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label" for="lead-status">Status</label><select class="form-select" name="status" id="lead-status">@foreach(['new','contacted','qualified','closed','spam'] as $status)<option value="{{ $status }}" {{ $lead->status===$status?'selected':'' }}>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <div class="mb-3"><label class="form-label" for="lead-notes">Internal notes (admin only)</label><textarea class="form-control" rows="5" id="lead-notes" name="internal_notes" maxlength="4000">{{ old('internal_notes', $lead->internal_notes) }}</textarea></div>
            <button class="btn btn-primary">Save Status &amp; Notes</button>
        </form>
    </div></div>
</section>
@endsection
