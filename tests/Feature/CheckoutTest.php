<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_cart_items_can_be_seen_from_the_checkout_page()
    {

        // Arrange
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

        session([
            'cart' => [
                ['id' => 2, 'qty' => 1], // Pizza
                ['id' => 3, 'qty' => 2], // BBQ
            ],
        ]);

        // Act
        $checkout_items = [
            [
                'id' => 2,
                'qty' => 1,
                'name' => 'Pizza',
                'cost' => 2.1,
                'subtotal' => 2.1,
                'image' => 'some-image.jpg',
            ],
            [
                'id' => 3,
                'qty' => 2,
                'name' => 'BBQ',
                'cost' => 3.2,
                'subtotal' => 6.4,
                'image' => 'some-image.jpg',
            ],
        ];

        $this->get('/checkout')
            ->assertViewIs('checkout')
            ->assertViewHas('checkout_items', $checkout_items)
            ->assertSeeTextInOrder([
                // Item #1
                'Pizza',
                'Rs.2.1',
                '1',
                'Rs.2.1',

                // Item #2
                'BBQ',
                'Rs.3.2',
                '2',
                'Rs.6.4',
            ]);
    }


    public function test_order_can_be_created()
    {
        // Arrange
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

        // Act

        // Add items to the cart
        $this->post('/cart', [
            'id' => 1, // Taco
        ]);
        $this->post('/cart', [
            'id' => 2, // Pizza
        ]);
        $this->post('/cart', [
            'id' => 3, // BBQ
        ]);
        $this->patch('/cart/1', [
            'qty' => 5,
        ]);
        $this->delete('/cart/2');

        $this->post('/checkout')
            ->assertSessionHasNoErrors()
            ->assertRedirect('/summary');
        $this->assertDatabaseHas('orders', [
            'total' => 10.7
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => 1,
            'product_id' => 1,
            'cost' => 1.5,
            'qty' => 5,
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => 1,
            'product_id' => 3,
            'cost' => 3.2,
            'qty' => 1,
        ]);
    }
}
