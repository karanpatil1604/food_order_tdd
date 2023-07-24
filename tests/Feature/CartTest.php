<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_item_can_be_added_to_the_cart(): void
    {
        Product::factory()->count(3)->create();
        $this->post('/cart', [
            'id' => 1
        ])->assertRedirect('/cart')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('cart.0', ['id' => 1, 'qty' => 1]);
    }
    public function test_same_item_cannot_be_added_to_the_cart_twice()
    {
        Product::factory()->create([
            'name' => 'Taco',
            'cost' => 1.5,
        ]);
        Product::factory()->create([
            'name' => 'Pizza',
            'cost' => 2.1,
        ]);
        Product::factory()->create([
            'name' => 'BBQ',
            'cost' => 3.2,
        ]);

        $this->post('/cart', ['id' => 1]);
        $this->post('/cart', ['id' => 1]);
        $this->post('/cart', ['id' => 2]);

        $this->assertEquals(2, count(session('cart')));
    }
    public function test_cart_page_can_be_accessed()
    {
        Product::factory()->count(3)->create();
        $response = $this->get('/cart');
        $response->assertViewIs('cart');
    }
}
