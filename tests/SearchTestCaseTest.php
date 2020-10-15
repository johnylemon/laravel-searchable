<?php

namespace Johnylemon\Searchable\Tests;

class SearchTestCaseTest extends TestCase
{
    /** @test */
    public function visit_no_search_route()
    {
        $response = $this->get('/no-searchable');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function visit_test_route()
    {
        $response = $this->get('/searchable-test');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function test_exact_search_one_property()
    {
        $response = $this->get('/searchable-test?first_name=Janko');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['first_name' => 'Janko']);

        $response = $this->get('/searchable-test?last_name=Doe');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['first_name' => 'John']);
        $response->assertJsonFragment(['first_name' => 'Jane']);
    }

    /** @test */
    public function test_exact_search_multiple_properties()
    {
        $response = $this->get('/searchable-test?first_name=Janko&last_name=Walski');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['first_name' => 'Janko', 'last_name' => 'Walski']);
    }

    /** @test */
    public function test_exact_search_invalid_property()
    {
        $response = $this->get('/searchable-test?aa=Janko');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function test_exact_search_multiple_with_one_invalid()
    {
        $response = $this->get('/searchable-test?first_name=John&invalid=Walski');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['first_name' => 'John', 'last_name' => 'Doe']);
    }

    /** @test */
    public function test_search_alias()
    {
        $response = $this->get('/searchable-test?name=Janko%20Walski');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['first_name' => 'Janko', 'last_name' => 'Walski']);

        $response = $this->get('/searchable-test?surname=Doe');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['nick' => 'JohnnyD']);
        $response->assertJsonFragment(['nick' => 'JanneD']);
    }

    /** @test */
    public function test_search_callable()
    {
        $response = $this->get('/searchable-test?full_name=Jane%20Doe');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['first_name' => 'Jane', 'last_name' => 'Doe']);
    }

    /** @test */
    public function test_builtin_alias_like()
    {
        $response = $this->get('/searchable-test-like?nick=nn');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['nick' => 'JohnnyD']);
        $response->assertJsonFragment(['nick' => 'JanneD']);

        $response = $this->get('/searchable-test-like?nick=nw');
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /** @test */
    public function test_builtin_alias_like_begin()
    {
        $response = $this->get('/searchable-test-like?first_name=jo');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['first_name' => 'John']);

        $response = $this->get('/searchable-test-like?first_name=je');
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /** @test */
    public function test_builtin_alias_like_end()
    {
        $response = $this->get('/searchable-test-like?last_name=ki');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['last_name' => 'Walski']);

        $response = $this->get('/searchable-test-like?last_name=ko');
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

}
