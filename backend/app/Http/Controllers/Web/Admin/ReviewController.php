<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $reviews = Review::with(['user', 'reviewable'])
            ->when($status === 'pending',  fn($q) => $q->pending())
            ->when($status === 'approved', fn($q) => $q->approved())
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $pendingCount  = Review::pending()->count();
        $approvedCount = Review::approved()->count();

        return view('admin.reviews.index', compact('reviews', 'status', 'pendingCount', 'approvedCount'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
