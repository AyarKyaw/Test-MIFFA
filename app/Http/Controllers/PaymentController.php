<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show the QR code payment page.
     */
    public function showQr($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();

        // Pass course info and mock transaction reference
        $orderRef = 'ORD-' . strtoupper(uniqid());

        return view('course.payment.qr', compact('course', 'orderRef'));
    }

    /**
     * Complete payment and attach course to user (called via JS or success redirect).
     */
    public function confirmPayment(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();

        // Attach course to user in pivot table
        $user->courses()->syncWithoutDetaching([$course->id]);

        return response()->json([
            'status' => 'success',
            'redirect' => route('courses.index')
        ]);
    }
}