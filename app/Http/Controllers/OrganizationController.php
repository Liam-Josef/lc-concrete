<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Organization;
use App\Models\State;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{


    public function index() {
        $lessons = Lesson::all();
        return view('admin.organizations.index', [
            'organizations' => Organization::where('is_active', true)->orderBy('name')->get(),
            'lessons' => $lessons,
            'states' => State::all()
        ]);
    }
    public function inactive() {
        return view('admin.organizations.inactive', [
            'organizations' => Organization::where('is_active', false)->orderBy('name')->get()
        ]);
    }
    public function create() {
        return view('admin.organizations.create', [
            'organizations' => Organization::all(),
            'states' => State::all()
        ]);
    }

    public function view(Organization $organization)
    {
        $organization = Organization::with([
            'lessons' => function ($query) {
                $query->withCount('videos');
            },
            'videos',
            'contacts'
        ])->findOrFail($organization->id);

        return view('admin.organizations.view', compact('organization'));
    }

    public function edit(Organization $organization ) {
        return view('admin.organizations.edit', [
            'organization' => $organization,
            'states' => State::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'mex_association' => 'required|boolean',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'City' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|numeric|digits_between:4,10',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Save to database
        Organization::create([
            'is_active' => $validated['is_active'],
            'mex_association' => $validated['mex_association'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'address_1' => $validated['address_1'],
            'address_2' => $validated['address_2'],
            'city' => $validated['City'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
        ]);

        return redirect()->route('organization.index')->with('success', 'Organization added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'mex_association' => 'required|boolean',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|numeric|digits_between:4,10',
        ]);

        $organization = Organization::findOrFail($id);

        $organization->update([
            'is_active' => $validated['is_active'],
            'mex_association' => $validated['mex_association'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            'address_1' => $validated['address_1'],
            'address_2' => $validated['address_2'],
            'city' => $validated['city'], // match with form's name
            'state' => $validated['state'],
            'zip' => $validated['zip'],
        ]);

        return redirect()->route('organization.view', $organization->id)->with('success', 'Organization updated successfully!');
    }

    public function activate(Organization $organization)
    {

        $organization->update([
            'is_active' => '1',
        ]);

        return redirect()->route('organization.index')->with('success', 'Organization Activated!');
    }
    public function deactivate(Organization $organization)
    {

        $organization->update([
            'is_active' => '0',
        ]);

        return redirect()->route('organization.index')->with('success', 'Organization Deactivated!');
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:190',
        ]);

        $org = \App\Models\Organization::create([
            'name'      => $data['name'],
            'is_active' => true,
        ]);

        // If the caller asked for JSON (our fetch does), return JSON.
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'  => 1,
                'org' => ['id' => $org->id, 'name' => $org->name],
            ]);
        }

        // Otherwise behave like the step-1 wizard (existing behavior).
        return redirect()
            ->route('admin.courses.create.details', ['org' => $org->id])
            ->with('success', 'Organization created.');
    }




}
