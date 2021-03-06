<?php

namespace SehrGut\Eloquery\Grammar;

use Illuminate\Http\Request;

/**
 * Rules for extracting pagination parameters from a request.
 *
 * Syntax: `?page=1&limit=10`
 */
class PaginateGrammar extends AbstractGrammar
{
    /**
     * The default limit when none is given.
     *
     * @var int
     */
    const DEFAULT_LIMIT = 10;

    /**
     * The maximum allowed limit.
     *
     * @var int
     */
    const MAX_LIMIT = 100;

    /**
     * The default page assumed when query param missing.
     *
     * @var int
     */
    const DEFAULT_PAGE = 1;

    /**
     * Extract pagination parameters from a request.
     *
     * @param Request $request
     * @return void
     */
    public function extract(Request $request): array
    {
        return [
            'page' => $request->get('page', 1),
            'limit' => min($request->get('limit', static::DEFAULT_LIMIT), static::MAX_LIMIT),
        ];
    }
}
