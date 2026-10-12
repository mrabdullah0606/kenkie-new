<?php

test('contact page and footers show updated address, amazon store link, and social channels', function () {
    $response = $this->get(route('contact.us'));

    $response->assertStatus(200);
    $response->assertSee('Walsall, WS2 9AD, United Kingdom');
    $response->assertDontSee('Flat A 51 Bescot road');
    $response->assertDontSee('Flat A, 51 Bescot road');
    $response->assertDontSee('Step 4 Humanity Charity');
    $response->assertSee('https://www.amazon.co.uk/stores/KENKIE/page/487BF6DD-5825-481E-8D57-0FB35B9DC83E');
    $response->assertSee('https://www.facebook.com/share/1JC6RQXui7/?mibextid=wwXIfr');
    $response->assertSee('https://youtube.com/@kenkieofficial?si=oNQG40unIDlNw2rH');
    $response->assertSee('https://www.tiktok.com/@kenkieofficial');
});

test('storefront footer displays amazon store link and removes charity', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertDontSee('Step 4 Humanity Charity');
    $response->assertSee('https://www.amazon.co.uk/stores/KENKIE/page/487BF6DD-5825-481E-8D57-0FB35B9DC83E');
    $response->assertSee('https://www.facebook.com/share/1JC6RQXui7/?mibextid=wwXIfr');
});

test('cart page renders with gbp currency symbol', function () {
    $response = $this->get(route('cart.index'));

    $response->assertStatus(200);
    $response->assertSee('Total (GBP)');
    $response->assertDontSee('Total (USD)');
});
