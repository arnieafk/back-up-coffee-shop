<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    /**
     * Display all staff
     */
    public function index()
    {
        $staff = Staff::all();

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Store new staff
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:staff,username',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        Staff::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff created successfully');
    }

    /**
     * Update staff
     */
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:staff,username,' . $id . ',staffID',
            'role' => 'required',
        ]);

        $staff->name = $request->name;
        $staff->username = $request->username;
        $staff->role = $request->role;

        // Update password only if gi-fill
        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff updated successfully');
    }

    /**
     * Delete staff
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff deleted successfully');
    }
}
