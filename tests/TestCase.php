<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        TestResponse::macro('data', function($key){
            return $this->original->getData()[$key];
        });

        TestResponse::macro('assertTrue', function($key){
            Assert::assertTrue($key);
        });
        EloquentCollection::macro('assertEquals', function($items){
            Assert::assertCount($items->count(), $this);
            
            $this->zip($items)->each(function($itemPair){
                Assert::assertTrue($itemPair[0]->is($itemPair[1]));
            });
        });
    }
}
