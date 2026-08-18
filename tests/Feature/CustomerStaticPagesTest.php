<?php

test('customer information pages are publicly available', function (string $routeName, string $copy) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($copy);
})->with([
    ['about', 'Time deserves'],
    ['contact', 'Myanmar Plaza'],
    ['faqs', 'Questions,'],
]);

test('the storefront footer links to customer information pages', function () {
    $this->view('customer.partials.footer')
        ->assertSee(route('about'), false)
        ->assertSee(route('contact'), false)
        ->assertSee(route('faqs'), false);
});
