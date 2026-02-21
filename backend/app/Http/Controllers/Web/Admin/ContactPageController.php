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
        'contact_meta_title',
        'contact_meta_description',
        'contact_meta_keywords',
        'contact_canonical',
    ];

    private array $defaults = [
        'contact_badge'           => 'Contact Support',
        'contact_title'           => "We're here to",
        'contact_title_highlight' => 'help',
        'contact_description'     => 'Need help with an order or have questions? Contact our support team and include your order number for faster assistance.',
        'contact_email'           => 'hello@dealmindanao.ph',
        'contact_address'           => 'Poblacion District, Davao City, 8000',
        'contact_meta_title'        => 'Contact Us - DealMindanao',
        'contact_meta_description'  => 'Get in touch with DealMindanao. Our support team is here to help with your orders, payments, and delivery questions.',
        'contact_meta_keywords'     => 'contact DealMindanao, customer support, Davao City',
        'contact_canonical'         => '',
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
            'contact_address'           => 'required|string|max:200',
            'contact_meta_title'        => 'nullable|string|max:70',
            'contact_meta_description'  => 'nullable|string|max:160',
            'contact_meta_keywords'     => 'nullable|string|max:300',
            'contact_canonical'         => 'nullable|string|max:300',
        ]);

        foreach ($this->allKeys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.contact_page.index')
            ->with('success', 'Contact page updated successfully.');
    }
}
