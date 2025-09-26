<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TechnicianController extends Controller
{
    // Show all technicians
    public function index()
    {
        $technicians = Technician::all();
        return view('SchedulePreventive.assign-tech', compact('technicians'));
    }

    // Show create form
    public function create()
    {
        return view('SchedulePreventive.create-tech');
    }

    // Store new technician
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate image
        ]);

        $data = $request->only('name', 'email');

        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/technicians'), $imageName);
            $data['image'] = 'uploads/technicians/'.$imageName;
        }

        Technician::create($data);

        return redirect()->route('technicians.index')
            ->with('success', 'Technician added successfully!');
    }

    // Show edit form
    public function edit(Technician $technician)
    {
        return view('SchedulePreventive.edit-tech', compact('technician'));
    }

    // Update existing technician
    public function update(Request $request, Technician $technician)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email,' . $technician->technicians_id . ',technicians_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'email');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($technician->image && File::exists(public_path($technician->image))) {
                File::delete(public_path($technician->image));
            }

            $imageName = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/technicians'), $imageName);
            $data['image'] = 'uploads/technicians/'.$imageName;
        }

        $technician->update($data);

        return redirect()->route('technicians.index')
            ->with('success', 'Technician updated successfully!');
    }

    // Delete technician
    public function destroy(Technician $technician)
    {
        // Delete image file if exists
        if ($technician->image && File::exists(public_path($technician->image))) {
            File::delete(public_path($technician->image));
        }

        $technician->delete();

        return redirect()->route('technicians.index')
            ->with('success', 'Technician deleted successfully!');
    }
    public function uploadImage(Request $request, Technician $technician)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Delete old image if exists
    if ($technician->image && File::exists(public_path($technician->image))) {
        File::delete(public_path($technician->image));
    }

    $imageName = time().'_'.$request->file('image')->getClientOriginalName();
    $request->file('image')->move(public_path('uploads/technicians'), $imageName);
    $technician->image = 'uploads/technicians/'.$imageName;
    $technician->save();

    return response()->json(['success' => true]);
}
}
