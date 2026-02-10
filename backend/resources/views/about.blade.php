@extends('layouts.app')

@section('content')
<div class="space-y-10">
	<section class="grid lg:grid-cols-2 gap-10 items-center">
		<div class="card">
			<p class="text-sm text-brand font-semibold mb-2">About DealMindanao</p>
			<h1 class="text-3xl md:text-4xl font-bold mb-4">Local deals you can trust, with payments kept offline.</h1>
			<p class="text-gray-600 leading-relaxed">
				DealMindanao connects shoppers with verified local partners. We highlight value-first offers while keeping
				transactions simple: reserve online, pay offline. This keeps your options flexible while supporting nearby
				businesses.
			</p>
			<div class="mt-6 flex flex-wrap gap-3">
				<a href="/shop" class="btn-primary">Browse Deals</a>
				<a href="/partner" class="btn-secondary">Become a Partner</a>
			</div>
		</div>

		<div class="grid gap-4">
			<div class="card">
				<h2 class="text-lg font-semibold mb-2">What we focus on</h2>
				<ul class="text-sm text-gray-600 space-y-2">
					<li>Verified partners across Mindanao.</li>
					<li>Transparent pricing and offline payments.</li>
					<li>Fast local support and easy order requests.</li>
				</ul>
			</div>
			<div class="card">
				<h2 class="text-lg font-semibold mb-2">Why it works</h2>
				<p class="text-sm text-gray-600">
					We keep the process light: discover, request, and confirm. No hidden fees, no online payment pressure.
				</p>
			</div>
		</div>
	</section>

	<section class="grid md:grid-cols-3 gap-4">
		<div class="card">
			<p class="text-sm text-gray-500">Partners onboarded</p>
			<p class="text-2xl font-bold">120+</p>
		</div>
		<div class="card">
			<p class="text-sm text-gray-500">Customer requests</p>
			<p class="text-2xl font-bold">4.8k</p>
		</div>
		<div class="card">
			<p class="text-sm text-gray-500">Average response</p>
			<p class="text-2xl font-bold">Under 2 hrs</p>
		</div>
	</section>
</div>
@endsection
