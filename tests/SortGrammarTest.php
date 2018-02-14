<?php

namespace SehrGut\EloQuery\Tests;

use Illuminate\Http\Request;
use Mockery;
use SehrGut\EloQuery\Grammar\SortGrammar;
use SehrGut\EloQuery\Operations\Sort;

class SortGrammarTest extends TestCase
{
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
    }

    public function test_it_extracts_sorts_from_request()
    {
        $this->request->shouldReceive('get')
            ->once()
            ->with('sort')
            ->andReturn([
                ['key' => 'someField', 'direction' => 'asc'],
                ['key' => 'otherField', 'direction' => 'desc'],
            ]);

        $grammar = new SortGrammar();
        $result = $grammar->extract($this->request);

        $this->assertEquals([
            [
                'key' => 'someField',
                'direction' => 'asc',
            ],
            [
                'key' => 'otherField',
                'direction' => 'desc',
            ],
        ], $result);
    }

    public function test_it_fills_defaults_when_extracting_sorts_from_request()
    {
        $this->request->shouldReceive('get')
            ->once()
            ->with('sort')
            ->andReturn([
                ['key' => 'someField']
            ]);

        $grammar = new SortGrammar();
        $result = $grammar->extract($this->request);

        $this->assertEquals([
            [
                'key' => 'someField',
                'direction' => null,
            ],
        ], $result);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_it_bails_when_sorts_are_not_an_array()
    {
        $this->request->shouldReceive('get')
            ->once()
            ->with('sort')
            ->andReturn('a bad return value');

        $grammar = new SortGrammar();
        $result = $grammar->extract($this->request);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_it_bails_when_key_is_missing_from_sorts()
    {
        $this->request->shouldReceive('get')
            ->once()
            ->with('sort')
            ->andReturn([
                ['key' => 'not missing', 'direction' => 'asc'],
                ['direction' => 'asc'],
            ]);

        $grammar = new SortGrammar();
        $result = $grammar->extract($this->request);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_it_bails_when_direction_is_invalid()
    {
        $this->request->shouldReceive('get')
            ->once()
            ->with('sort')
            ->andReturn([
                ['key' => 'someKey', 'direction' => 'asc'],
                ['key' => 'someKey', 'direction' => 'descc'],  // Bad direction
            ]);

        $grammar = new SortGrammar();
        $result = $grammar->extract($this->request);
    }
}
