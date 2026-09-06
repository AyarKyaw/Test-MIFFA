<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'name'          => 'required|string|max:255',
            'bio'           => 'nullable|string',
            'cropped_image' => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->filled('cropped_image')) {
            $validated['image'] = $this->saveBase64Image($request->input('cropped_image'));
        } elseif ($request->hasFile('image')) {
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
            'name'          => 'required|string|max:255',
            'bio'           => 'nullable|string',
            'cropped_image' => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->filled('cropped_image')) {
            // Delete old image if exists
            $this->deleteInstructorImage($instructor->image);

            $validated['image'] = $this->saveBase64Image($request->input('cropped_image'));
        } elseif ($request->hasFile('image')) {
            // Delete old image if exists
            $this->deleteInstructorImage($instructor->image);

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
        $this->deleteInstructorImage($instructor->image);

        $instructor->delete();

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }

    /**
     * Helper to process and store base64 cropped images.
     */
    private function saveBase64Image(string $base64Data): string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc.

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                $type = 'jpg';
            }

            $data = base64_decode($data);
            $filename = 'instructors/' . Str::random(40) . '.' . $type;

            Storage::disk('public')->put($filename, $data);

            return $filename;
        }

        return '';
    }

    /**
     * Helper to safely remove images from storage.
     */
    private function deleteInstructorImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}