<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::all();
    }

    public function show($id)
    {
        return Review::findOrFail($id);
    }
    public function create(Request $request)
    {
        $data = $request->validate([
            'user_id'=>'required',
            'car_id'=>'required',
            'rating'=>'required', //(1-5)
            'comment'=>'nullable',
        ]);

        $review = Review::make($data);

        return $review;
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id'=>'required',
            'user_id'=>'nullable',
            'car_id'=>'nullable',
            'rating'=>'nullable',
            'comment'=>'nullable',
        ]);

        $review = Review::findOrFail($data['id']);
        $review->edit($data);

        return response("Review Updated", "200")
            ->header("Content-Type","plain/text");
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'id'=>'required'
        ]);

        $review = Review::find($data['id']);
        $review->delete();

        return response("Review Deleted", "200")
            ->header("Content-Type", "plain/text");
    }
}
