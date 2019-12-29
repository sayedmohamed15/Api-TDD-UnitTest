<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Product;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;


class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_create_a_product()
    {
        $this->withoutExceptionHandling();
        $faker = Factory::create();

        $response = $this->actingAs($this->create('User', [],false),'api')->json('post','api/products',[
            'name'=>$name=$faker->company,
            'slug'=> str_slug($name),
            'price'=>$price= random_int(10,100),
        ]);
//        Log::info('1 ',[$response->getContent()]);
//        $product = Product::all();
        $response->assertJsonStructure([
            'id','name','slug','price','created_at'
        ])
            ->assertJson([
                'name'=>$name,
                'slug'=>str_slug($name),
                'price'=>$price
            ])
            ->assertStatus(201);
//        $this->assertCount(1,$product);
        $this->assertDatabaseHas('products',[
            'name'=>$name,
            'slug'=> str_slug($name),
            'price'=>$price,
        ]);

    }
    public function test_will_fail_with_a_404_if_product_not_found(){
//        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->create('User', [],false),'api')->json('Get','api/products/-1');
        $response->assertStatus(404);
    }

    public function test_can_return_a_product(){
        $this->withoutExceptionHandling();
        $product = $this->create("Product");

        $response = $this->actingAs($this->create('User', [],false),'api')->json('Get',"api/products/$product->id");
//        Log::info('1 ',[$response->getContent()]);
        $response->assertStatus(200)
        ->assertExactJson([
            'id'=>$product->id,
            'name'=>$product->name,
            'slug'=>$product->slug,
            'price'=>$product->price,
            'created_at'=>$product->created_at,
        ]);
    }

    public function test_will_fail_if_product_want_to_update_not_found(){
//        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->create('User', [],false),'api')->json('put','api/products/-1');
        $response->assertStatus(404);
    }
    public function test_can_update_a_product(){
        $this->withoutExceptionHandling();
        $product = $this->create("Product");

        $response = $this->actingAs($this->create('User', [],false),'api')->json('patch',"api/products/$product->id",[
            'name'=>$product->name.'_updated',
            'slug'=>str_slug($product->name.'_updated'),
            'price'=>$product->price + 10,
        ]);
        $response->assertStatus(200)
            ->assertExactJson([
                'id'=>$product->id,
                'name'=>$product->name.'_updated',
                'slug'=>str_slug($product->name.'_updated'),
                'price'=>$product->price+10,
                'created_at'=>$product->created_at,
            ]);
        $this->assertDatabaseHas('products',[
            'id'=>$product->id,
            'name'=>$product->name.'_updated',
            'slug'=>str_slug($product->name.'_updated'),
            'price'=>$product->price+10,
            'created_at'=>$product->created_at,
            'updated_at'=>$product->updated_at,
        ]);


    }
    public function test_will_fail_if_product_want_to_Delete_not_found(){
//        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->create('User', [],false),'api')->json('Delete','api/products/-1');
        $response->assertStatus(404);
    }
    public function test_can_return_a_collection_of_paginated_products(){
        $this->withoutExceptionHandling();
        $product1 = $this->create('Product');
        $product2 = $this->create('Product');
        $product3 = $this->create('Product');

        $response=$this->actingAs($this->create('User', [],false),'api')->json('GET','api/products');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data'=>[
                '*'=>['id','name','slug','price','created_at']
            ],
            'links'=>['first','last','prev','next'],
            'meta'=>[
                'current_page','last_page','from','to','path','per_page','total'
            ]
        ]);

        Log::info('1 ',[$response->getContent()]);
    }
    public function test_can_delete_a_product()
    {
//        $this->withoutExceptionHandling();
        $product = $this->create("Product");
        $this->assertCount(1,Product::all());
        $response = $this->actingAs($this->create('User', [],false),'api')->json('delete', "api/products/$product->id");
        $response->assertStatus(204)->assertSee(null);

        $this->assertDatabaseMissing('products',['id'=>$product->id]);

//        $response = $this->delete(Book::first()->path());
//        $this->assertCount(0,Product::all());
    }


}
