@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Article Header -->
            <header class="mb-4">
                <h1 class="fw-bolder mb-1">{{ $article->title }}</h1>
                <div class="text-muted fst-italic mb-2">
                    Diposting pada {{ $article->created_at->format('d M Y') }} oleh {{ $article->user->name }}
                </div>
                
                <!-- Category -->
                <a class="badge bg-primary text-decoration-none link-light" href="#!">
                    {{ $article->category->name }}
                </a>
                
                <!-- Tags -->
                @foreach($article->tags as $tag)
                    <a class="badge bg-secondary text-decoration-none link-light" href="#!">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </header>

            <!-- Preview image figure -->
            <figure class="mb-4">
                @if($article->image)
                    <img class="img-fluid rounded w-100" 
                         src="{{ asset('storage/' . $article->image) }}" 
                         alt="{{ $article->title }}" 
                         style="max-height: 500px; object-fit: cover;" />
                @else
                    <img class="img-fluid rounded w-100" 
                         src="https://via.placeholder.com/900x400" 
                         alt="Placeholder Image" />
                @endif
            </figure>

            <!-- Post content -->
            <section class="mb-5 fs-5 lh-base">
                {!! nl2br(e($article->content)) !!}
            </section>

            <!-- Author Info Box -->
            <div class="card bg-light border-0 mb-5">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            @if($article->user->profile && $article->user->profile->avatar)
                                <img class="rounded-circle" 
                                     src="{{ asset('storage/' . $article->user->profile->avatar) }}" 
                                     alt="{{ $article->user->name }}" 
                                     width="80" height="80" 
                                     style="object-fit: cover;">
                            @else
                                <img class="rounded-circle" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($article->user->name) }}&background=random" 
                                     alt="{{ $article->user->name }}" 
                                     width="80" height="80">
                            @endif
                        </div>
                        <div class="ms-4">
                            <h5 class="fw-bold mb-1">{{ $article->user->name }}</h5>
                            <div class="text-muted mb-2">
                                <i class="bi bi-telephone-fill me-1"></i> 
                                {{ $article->user->profile->phone ?? 'Tidak ada nomor telepon' }}
                            </div>
                            <p class="mb-0">
                                {{ $article->user->profile->bio ?? 'Penulis belum menulis biografi.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge { margin-right: 5px; }
    section { text-align: justify; }
</style>
@endpush
