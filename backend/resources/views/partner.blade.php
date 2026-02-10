@extends('layouts.app')

@section('content')
<section class="grid lg:grid-cols-2 gap-8">
	<div class="card">
		<p class="text-sm text-brand font-semibold mb-2">For Local Businesses</p>
		<h1 class="text-3xl md:text-4xl font-bold mb-4">Partner with DealMindanao</h1>
		<p class="text-gray-600 mb-6">
			Reach customers looking for local savings. We highlight your best deals while keeping payment simple and
			offline. You handle fulfillment, we handle discovery.
		</p>

		<div class="grid gap-3 text-sm text-gray-600">
			<div class="card">Feature limited-time offers and promos.</div>
			<div class="card">Get verified as a trusted local partner.</div>
			<div class="card">Receive order requests directly.</div>
		</div>
	</div>

	<div class="card">
		<h2 class="text-lg font-semibold mb-4">Partnership Request</h2>
		<form class="grid gap-4">
			<div>
				<label class="text-sm">Business Name</label>
				<input class="input" placeholder="Your business">
			</div>
			<div>
				<label class="text-sm">Contact Person</label>
				<input class="input" placeholder="Your name">
			</div>
			<div>
				<label class="text-sm">Email</label>
				<input type="email" class="input" placeholder="you@email.com">
			</div>
			<div>
				<label class="text-sm">City</label>
				<input class="input" placeholder="City / Province">
			</div>
			<div>
				<label class="text-sm">Tell us about your deals</label>
				<textarea class="textarea" rows="4" placeholder="Types of offers, products, or services"></textarea>
			</div>
			<button class="btn-primary" type="submit">Submit Request</button>
		</form>
	</div>
</section>
@endsection
