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
            'name'                 => 'required|string|max:255',
            'bio'                  => 'nullable|string',
            'cropped_image'        => 'nullable|string',
            'cropped_banner_image' => 'nullable|string',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // 1. Process Profile Image
        if ($request->filled('cropped_image')) {
            $validated['image'] = $this->saveBase64Image($request->input('cropped_image'), 'instructors/profiles');
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('instructors/profiles', 'public');
        }

        // 2. Process Banner Image
        if ($request->filled('cropped_banner_image')) {
            $validated['banner_image'] = $this->saveBase64Image($request->input('cropped_banner_image'), 'instructors/banners');
        } elseif ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('instructors/banners', 'public');
        }

        Instructor::create($validated);

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Teacher profile created successfully.');
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
            'name'                 => 'required|string|max:255',
            'bio'                  => 'nullable|string',
            'cropped_image'        => 'nullable|string',
            'cropped_banner_image' => 'nullable|string',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // 1. Update Profile Image
        if ($request->filled('cropped_image')) {
            $this->deleteInstructorImage($instructor->image);
            $validated['image'] = $this->saveBase64Image($request->input('cropped_image'), 'instructors/profiles');
        } elseif ($request->hasFile('image')) {
            $this->deleteInstructorImage($instructor->image);
            $validated['image'] = $request->file('image')->store('instructors/profiles', 'public');
        }

        // 2. Update Banner Image
        if ($request->filled('cropped_banner_image')) {
            $this->deleteInstructorImage($instructor->banner_image);
            $validated['banner_image'] = $this->saveBase64Image($request->input('cropped_banner_image'), 'instructors/banners');
        } elseif ($request->hasFile('banner_image')) {
            $this->deleteInstructorImage($instructor->banner_image);
            $validated['banner_image'] = $request->file('banner_image')->store('instructors/banners', 'public');
        }

        $instructor->update($validated);

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Teacher profile updated successfully.');
    }

    /**
     * Remove the specified instructor from storage.
     */
    public function destroy(Instructor $instructor)
    {
        $this->deleteInstructorImage($instructor->image);
        $this->deleteInstructorImage($instructor->banner_image);

        $instructor->delete();

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Teacher profile deleted successfully.');
    }

    /**
     * Helper to process and store base64 cropped images.
     */
    private function saveBase64Image(string $base64Data, string $folder = 'instructors'): string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc.

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                $type = 'jpg';
            }

            $data = base64_decode($data);
            $filename = rtrim($folder, '/') . '/' . Str::random(40) . '.' . $type;

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