<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Tweets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweets::latest()->get();
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Sesuaikan validasi gambar sesuai kebutuhan
        ]);

        $tweet = new Tweets([
            'post' => $request->input('post'),
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);

            $tweet->image = $imageName;
        }

        $tweet->save();

        return redirect()
            ->route('tweets.index')
            ->with('success', 'Post Added Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweets $tweet)
    {
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweets $tweet)
    {
        // Perform authorization check to ensure the authenticated user owns the tweet
        if (Auth::user()->id !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tweets.edit', compact('tweet'));
    }

    public function update(Request $request, Tweets $tweet)
    {
        // Perform authorization check to ensure the authenticated user owns the tweet
        if (Auth::user()->id !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'post' => 'required',
        ]);

        $tweet->update([
            'post' => $request->input('post'),
        ]);

        return redirect()
            ->route('tweets.index')
            ->with('success', 'Tweet Updated Successfully.');
    }

    public function destroy(Tweets $tweet)
    {
        // Perform authorization check to ensure the authenticated user owns the tweet
        if (Auth::user()->id !== $tweet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $tweet->delete();

        return redirect()
            ->route('tweets.index')
            ->with('success', 'Tweet Deleted Successfully.');
    }

    public function storeReply(Request $request, Tweets $tweet)
    {
        $request->validate([
            'reply' => 'required',
        ]);

        $tweet->replies()->create([
            'reply' => $request->input('reply'),
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('tweets.index', $tweet->id)
            ->with('success', 'Reply added successfully.');
    }
    
}
