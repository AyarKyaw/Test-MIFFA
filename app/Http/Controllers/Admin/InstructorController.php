<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    /**
     * Display a listing of instructors.
     */
    public function index()
    {
        $instructors = Instructor::latest()->paginate(10);
        return view('dashboard.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new instructor.
     */
    public function create()
    {
        return view('dashboard.instructors.create');
    }

    /**
     * Store a newly created instructor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('instructors', 'public');
        }

        Instructor::create($validated);

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor created successfully.');
    }

    /**
     * Show the form for editing the specified instructor.
     */
    public function edit(Instructor $instructor)
    {
        return view('dashboard.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified instructor in storage.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
                Storage::disk('public')->delete($instructor->image);
            }
            $validated['image'] = $request->file('image')->store('instructors', 'public');
        }

        $instructor->update($validated);

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor updated successfully.');
    }

    /**
     * Remove the specified instructor from storage.
     */
    public function destroy(Instructor $instructor)
    {
        if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
            Storage::disk('public')->delete($instructor->image);
        }

        $instructor->delete();

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }
}