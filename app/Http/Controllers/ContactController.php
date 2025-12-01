<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\Contact;


class ContactController extends Controller
{

    public function index() {
        $contacts = Contact::where('is_active', true)
            ->with('organizations')
            ->get();

        return view('admin.contacts.index', compact('contacts'));
    }
    public function inactive() {
        return view('admin.contacts.inactive', [
            'contacts' => Contact::where('is_active', false)->get()
        ]);
    }
    public function create() {
        return view('admin.contacts.create', [
            'contacts' => Contact::all(),
            'organizations' => Organization::all(),
        ]);
    }

    public function view(Contact $contact)
    {
        abort_unless($contact->is_active, 404);

        $contact->load('organizations');

        return view('admin.contacts.view', compact('contact'));
    }


    public function edit(Contact $contact ) {
        return view('admin.contacts.edit', [
            'contact' => $contact
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'is_lead' => 'required|boolean',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'organization_id' => 'nullable|integer|exists:organizations,id',
        ]);

        $organizationId = $validated['organization_id'] ?? null;

        $organizationName = null;

        if ($validated['is_lead']) {
            // For leads, allow freeform organization name (optional)
            $organizationName = $request->input('organization');
        } else {
            $organization = \App\Models\Organization::find($organizationId);
            $organizationName = $organization?->name;
        }

        $contact = \App\Models\Contact::create([
            'is_active' => $validated['is_active'],
            'is_lead' => $validated['is_lead'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'organization' => $organizationName,
            'organization_id' => $organizationId,
        ]);

        // ✅ Attach to pivot table if org ID exists
        if ($organizationId) {
            $contact->organizations()->attach($organizationId);
        }

        return redirect($request->input('redirect_url', route('contact.index')))
            ->with('success', 'Contact added successfully!');
    }



    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'is_lead' => 'required|boolean',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'organization' => 'nullable|string|max:255',
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update([
            'is_active' => $validated['is_active'],
            'is_lead' => $validated['is_lead'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'organization' => $validated['organization'],
        ]);

        return redirect()->route('contact.view', $contact->id)->with('success', 'Contact updated successfully!');
    }

    public function activate(Contact $contact)
    {

        $contact->update([
            'is_active' => '1',
        ]);

        return redirect()->route('contact.index')->with('success', 'Contact Activated!');
    }
    public function deactivate(Contact $contact)
    {

        $contact->update([
            'is_active' => '0',
        ]);

        return redirect()->route('contact.index')->with('success', 'Contact Deactivated!');
    }


}
