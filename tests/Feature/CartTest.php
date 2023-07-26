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

    public function test_items_added_to_the_cart_can_be_seen_in_the_cart_page()
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

        // Action
        $this->post('/cart', [
            'id' => 1, // Taco
        ]);
        $this->post('/cart', [
            'id' => 3, // BBQ
        ]);
        $cart_items = [
            [
                'id' => 1,
                'name' => "Taco",
                'qty' => 1,
                'image' => 'some-image.jpg',
                'cost' => 1.5,
                'subtotal' => 1.5,
            ],
            [
                'id' => 3,
                'name' => "BBQ",
                'qty' => 1,
                'image' => 'some-image.jpg',
                'cost' => 3.2,
                'subtotal' => 3.2,
            ],
        ];

        // Assert
        $this->get('/cart')
            ->assertViewHas('cart_items', $cart_items)
            ->assertSeeTextInOrder([
                'Taco',
                'BBQ',
            ])
            ->assertDontSeeText(['Pizza']);
    }

    public function test_item_can_be_removed_from_the_cart()
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

        // Action 
        session(['cart' => [
            ['id' => 2, 'qty' => 1],
            ['id' => 3, 'qty' => 3],
        ]]);

        // Assert 
        $this->delete('/cart/2')
            ->assertRedirect('/cart')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('cart', [
                ['id' => 3, 'qty' => 3]
            ]);

        // Assert
        $this->get('/cart')
            ->assertSeeInOrder([
                'BBQ',
                'Rs. 3.2',
                '3'
            ])->assertDontSeeText('Pizza');
    }

    public function test_cart_item_qty_can_be_updated()
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


        session(['cart' => [
            ['id' => 1, 'qty' => 1],
            ['id' => 3, 'qty' => 1],
        ]]);

        // Action 
        $this->patch('/cart/3', [
            'qty' => 5,
        ])->assertRedirect('/cart')
            ->assertSessionHasNoErrors()
            ->assertSessionHas('cart', [
                ['id' => 1, 'qty' => 1],
                ['id' => 3, 'qty' => 5],
            ]);

        $this->get('/cart')
            ->assertSeeInOrder([
                // Item #1 
                'Taco',
                'Rs. 1.5',
                '1',
                // Item #2 
                'BBQ',
                'Rs. 3.2',
                '5'
            ]);
    }
}
