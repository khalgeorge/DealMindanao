<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::withCount('products');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        
        $companies = $query->latest()->paginate(15);
        
        return view('admin.companies.index', compact('companies'));
    }
    
    public function create()
    {
        return view('admin.companies.create');
    }
    
    public function store(CompanyRequest $request)
    {
        $company = Company::create($request->validated());
        
        if ($request->wantsJson()) {
            return response()->json(['company' => $company], 201);
        }
        
        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company created successfully!');
    }
    
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }
    
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->validated());
        
        if ($request->wantsJson()) {
            return response()->json(['company' => $company]);
        }
        
        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company updated successfully!');
    }
    
    public function destroy(Company $company)
    {
        if ($company->products()->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Cannot delete company with existing products!'], 400);
            }
            return back()->with('error', 'Cannot delete company with existing products!');
        }
        
        $company->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Company deleted successfully!']);
        }
        
        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
    
    public function toggleStatus(Company $company)
    {
        $company->update(['is_active' => !$company->is_active]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $company->is_active,
                'message' => 'Company status updated!'
            ]);
        }
        
        return back()->with('success', 'Company status updated!');
    }
}
