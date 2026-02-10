@extends('layouts.app')

@section('content')
<div class="grid lg:grid-cols-[1.2fr_1fr] gap-8">
	<div class="card">
		<h1 class="text-3xl font-bold mb-2">Contact Us</h1>
		<p class="text-sm text-gray-500 mb-6">Have a question or need help? Send us a message.</p>

		<form class="grid gap-4">
			<div class="grid md:grid-cols-2 gap-4">
				<div>
					<label class="text-sm">Full Name</label>
					<input class="input" placeholder="Your name">
				</div>
				<div>
					<label class="text-sm">Email</label>
					<input type="email" class="input" placeholder="you@email.com">
				</div>
			</div>

			<div>
				<label class="text-sm">Subject</label>
				<input class="input" placeholder="Inquiry about a deal">
			</div>

			<div>
				<label class="text-sm">Message</label>
				<textarea class="textarea" rows="5" placeholder="Tell us how we can help"></textarea>
			</div>

			<button class="btn-primary" type="submit">Send Message</button>
		</form>
	</div>

	<div class="grid gap-4">
		<div class="card">
			<h2 class="text-lg font-semibold mb-2">Quick Info</h2>
			<p class="text-sm text-gray-600">Email: hello@dealmindanao.com</p>
			<p class="text-sm text-gray-600">Phone: +63 912 345 6789</p>
			<p class="text-sm text-gray-600">Location: Davao City, PH</p>
		</div>
		<div class="card">
			<h2 class="text-lg font-semibold mb-2">Support Hours</h2>
			<p class="text-sm text-gray-600">Mon - Sat: 9:00 AM - 7:00 PM</p>
			<p class="text-sm text-gray-600">Sunday: Limited support</p>
		</div>
	</div>
</div>
@endsection
