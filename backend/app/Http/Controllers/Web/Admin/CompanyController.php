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
        Company::create($request->validated());
        
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
        
        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company updated successfully!');
    }
    
    public function destroy(Company $company)
    {
        if ($company->products()->count() > 0) {
            return back()->with('error', 'Cannot delete company with existing products!');
        }
        
        $company->delete();
        
        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
    
    public function toggleStatus(Company $company)
    {
        $company->update(['is_active' => !$company->is_active]);
        
        return back()->with('success', 'Company status updated!');
    }
}
