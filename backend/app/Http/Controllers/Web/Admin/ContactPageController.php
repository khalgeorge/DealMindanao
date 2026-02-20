<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    private array $allKeys = [
        'contact_badge',
        'contact_title',
        'contact_title_highlight',
        'contact_description',
        'contact_email',
        'contact_address',
    ];

    private array $defaults = [
        'contact_badge'           => 'Contact Support',
        'contact_title'           => "We're here to",
        'contact_title_highlight' => 'help',
        'contact_description'     => 'Need help with an order or have questions? Contact our support team and include your order number for faster assistance.',
        'contact_email'           => 'hello@dealmindanao.ph',
        'contact_address'         => 'Poblacion District, Davao City, 8000',
    ];

    public function index()
    {
        $raw = Setting::getMany($this->allKeys);
        $s   = [];
        foreach ($this->allKeys as $key) {
            $s[$key] = $raw[$key] ?? $this->defaults[$key];
        }
        return view('admin.contact-page.index', ['s' => $s]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_badge'           => 'nullable|string|max:100',
            'contact_title'           => 'required|string|max:120',
            'contact_title_highlight' => 'required|string|max:120',
            'contact_description'     => 'required|string|max:600',
            'contact_email'           => 'required|string|max:120',
            'contact_address'         => 'required|string|max:200',
        ]);

        foreach ($this->allKeys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.contact_page.index')
            ->with('success', 'Contact page updated successfully.');
    }
}
