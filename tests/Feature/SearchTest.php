<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Product;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // /**@test */
    use RefreshDatabase;
    public function test_food_search_page_is_accessible()
    {
        // Arrange
        Product::factory()->create();

        // Act
        $response = $this->get('/');

        // Assert
        $items = Product::get();

        $response->assertViewIs('search')->assertViewHas('items', $items);
    }

    public function test_search_page_shows_the_items()
    {
        Product::factory()->count(3)->create();
        $items = Product::get();
        $this->get('/')->assertSeeInOrder([
            $items[0]->name,
            $items[1]->name,
            $items[2]->name,
        ]);
    }

    public function test_food_can_be_searched_given_a_query()
    {
        Product::factory()->create([
            'name' => 'Taco'
        ]);
        Product::factory()->create([
            'name' => 'Pizza'
        ]);
        Product::factory()->create([
            'name' => 'BBQ'
        ]);

        $this->get('/?query=bbq')
            ->assertSee('BBQ')
            ->assertDontSeeText('Taco')
            ->assertDontSeeText('Pizza');
        // $this->get('/')->assertSeeInOrder(['Taco', 'Pizza', 'BBQ']);
    }
}
