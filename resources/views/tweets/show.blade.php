@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="card-body">
                        <!-- Display existing replies -->
                        <div class="existing-replies">
                            @forelse ($tweet->replies as $reply)
                                <div>
                                    <strong>{{ $reply->user->name ?? 'Unknown User' }}</strong>: {{ $reply->reply }}
                                </div>
                            @empty
                                <p>No replies yet.</p>
                            @endforelse
                        </div>

                        <!-- Form for adding a new reply -->
                        @if (Auth::check())
                            <form action="{{ route('tweets.storeReply', $tweet->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="reply" class="form-label">Your Reply</label>
                                    <textarea name="reply" id="reply" cols="30" rows="5" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Reply</button>
                                </div>
                            </form>
                        @else
                            <p>Login to leave a reply.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
