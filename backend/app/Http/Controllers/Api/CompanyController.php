<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies
     */
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    /**
     * Display the specified company
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
        ]);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
        ]);

        $company->update($validated);

        return response()->json($company);
    }

    /**
     * Remove the specified company
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully',
        ]);
    }
}
