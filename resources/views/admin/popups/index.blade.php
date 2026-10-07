@extends('admin.layouts.app')

@section('title', 'Promotional Pop-ups')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h3 class="fw-bold mb-1">Promotional Pop-ups</h3>
            <p class="text-muted mb-0">Create and manage special deal, discount, and promotional popups for customers.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('admin.popups.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add New Pop-up
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table_id">
                            <thead class="table-light">
                                <tr class="small text-uppercase text-muted">
                                    <th>Image</th>
                                    <th>Title & Offer</th>
                                    <th>Discount Code</th>
                                    <th>Target Page</th>
                                    <th>Delay</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($popups as $popup)
                                    <tr>
                                        <td>
                                            @if($popup->image)
                                                <img src="{{ asset($popup->image) }}" alt="{{ $popup->title }}" class="img-fluid rounded border" style="width: 60px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="badge bg-light text-secondary border p-2"><i class="fa-solid fa-image"></i> No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $popup->title }}</div>
                                            @if($popup->subtitle)
                                                <small class="text-muted">{{ $popup->subtitle }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($popup->discount_code)
                                                <span class="badge bg-success-subtle text-success border border-success fw-bold px-2 py-1 font-monospace">{{ $popup->discount_code }}</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info text-capitalize px-2 py-1">{{ $popup->target_page }} Pages</span>
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ $popup->delay_seconds }}s delay</span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.popups.toggle-status', $popup) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="badge border-0 {{ $popup->is_active ? 'bg-success' : 'bg-secondary' }}" style="cursor: pointer;">
                                                    {{ $popup->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-2">
                                                <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.popups.destroy', $popup) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this popup?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-bullhorn fs-2 text-muted d-block mb-2"></i>
                                            No promotional pop-ups found. Click <strong>"Add New Pop-up"</strong> to create your first discount modal!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($popups->hasPages())
                        <div class="p-3 border-top">
                            {{ $popups->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
