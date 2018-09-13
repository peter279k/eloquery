<?php

namespace SehrGut\EloQuery\Tests;

use Illuminate\Http\Request;
use SehrGut\EloQuery\OperationCollection;
use SehrGut\EloQuery\Operations\Filter;
use SehrGut\EloQuery\Operations\Paginate;
use SehrGut\EloQuery\Operations\Sort;
use SehrGut\EloQuery\Operators;
use SehrGut\EloQuery\RequestParser;

class RequestParserTest extends TestCase
{
    public function test_it_extracts_paginate_operations()
    {
        $request = new Request(['page' => 3, 'limit' => 13]);
        $parser = new RequestParser($request);

        $operations = $parser->extractOperations();
        $this->assertInstanceOf(OperationCollection::class, $operations);

        $operation = $operations->dump()[0];
        $this->assertInstanceOf(Paginate::class, $operation);
        $this->assertEquals(13, $operation->limit);
        $this->assertEquals(3, $operation->page);
    }

    public function test_it_extracts_sort_operations()
    {
        $request = new Request([
            'sort' => [
                [
                    'key' => 'field_one',
                    'direction' => 'asc',
                ],
                [
                    'key' => 'field_two',
                    'direction' => 'desc',
                ],
            ]
        ]);
        $parser = new RequestParser($request);

        $operations = $parser->extractOperations();
        $this->assertInstanceOf(OperationCollection::class, $operations);

        $operationOne = $operations->dump()[0];
        $this->assertInstanceOf(Sort::class, $operationOne);
        $this->assertEquals('field_one', $operationOne->attribute);
        $this->assertTrue($operationOne->ascending);

        $operationTwo = $operations->dump()[1];
        $this->assertInstanceOf(Sort::class, $operationTwo);
        $this->assertEquals('field_two', $operationTwo->attribute);
        $this->assertFalse($operationTwo->ascending);
    }

    public function test_it_extracts_filter_operations()
    {
        $request = new Request([
            'filter' => [
                [
                    'key' => 'field_one',
                    'value' => 'value_one',
                    'operator' => 'equals',
                ],
                [
                    'key' => 'field_two',
                    'value' => 'value_two',
                    'operator' => 'contains',
                    'negated' => true,
                ],
            ]
        ]);
        $parser = new RequestParser($request);

        $operations = $parser->extractOperations();
        $this->assertInstanceOf(OperationCollection::class, $operations);

        $operationOne = $operations->dump()[0];
        $this->assertInstanceOf(Filter::class, $operationOne);
        $this->assertEquals('field_one', $operationOne->attribute);
        $this->assertEquals('value_one', $operationOne->value);
        $this->assertEquals('EQUALS', $operationOne->operator);
        $this->assertFalse($operationOne->negated);

        $operationTwo = $operations->dump()[1];
        $this->assertInstanceOf(Filter::class, $operationTwo);
        $this->assertEquals('field_two', $operationTwo->attribute);
        $this->assertEquals('value_two', $operationTwo->value);
        $this->assertEquals('CONTAINS', $operationTwo->operator);
        $this->assertTrue($operationTwo->negated);
    }
}
