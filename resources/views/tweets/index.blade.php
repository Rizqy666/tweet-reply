@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger  alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="card-body">
                        <form action="{{ route('tweets.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="post" class="form-label">Tweets</label>
                                <textarea name="post" id="post" cols="30" rows="10" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Post</button>
                            </div>
                        </form>
                    </div>
                </div>

                @forelse ($tweets as $tweet)
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $tweet->user->name ?? 'User Tidak Dikenal' }}</h5>
                            <hr>
                            <p class="card-text">{{ $tweet->post }}</p>

                            <!-- Menampilkan balasan untuk setiap tweet -->
                            <div class="replies">
                                @foreach ($tweet->replies as $reply)
                                    <div class="reply">
                                        @php
                                            $userName = \App\Models\User::find($reply->user_id)->name ?? 'User Tidak Dikenal';
                                        @endphp
                                        <strong>{{ $userName }}</strong>:
                                        {{ $reply->reply }}
                                    </div>
                                @endforeach
                            </div>


                            <div class="d-flex justify-content-between align-items-end">
                                <small class="text-muted">{{ $tweet->created_at->diffForHumans() }}</small>
                                <div class="btn-group" role="group" aria-label="Tindakan Tweet">
                                    @if (Auth::check() && Auth::user()->id === $tweet->user_id)
                                        <a href="{{ route('tweets.edit', $tweet->id) }}"
                                            class="btn btn-warning btn-sm me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('tweets.destroy', $tweet->id) }}"
                                            class="btn btn-danger btn-sm me-1"
                                            onclick="event.preventDefault(); document.getElementById('delete-form-{{ $tweet->id }}').submit();">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </a>
                                        <form id="delete-form-{{ $tweet->id }}"
                                            action="{{ route('tweets.destroy', $tweet->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm reply-btn">
                                        <i class="fas fa-reply"></i> Balas
                                    </button>
                                </div>
                            </div>

                            <!-- Form balasan untuk setiap tweet -->
                            <form action="{{ route('tweets.storeReply', $tweet->id) }}" method="POST"
                                class="mt-3 reply-form" style="display: none;">
                                @csrf
                                <div class="mb-3">
                                    <label for="reply" class="form-label">Balasan Anda</label>
                                    <textarea name="reply" id="reply" cols="30" rows="2" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-reply"></i> Kirim
                                        Balasan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada tweet yang tersedia.</p>
                @endforelse



            </div>
        </div>
    </div>
@endsection
