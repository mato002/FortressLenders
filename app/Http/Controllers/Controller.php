<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Return a query-string filter value, or null when empty / "all".
     */
    protected function queryFilterValue(Request $request, string $key): ?string
    {
        if (! $request->filled($key)) {
            return null;
        }

        $value = trim((string) $request->input($key));

        if ($value === '' || $value === 'all') {
            return null;
        }

        return $value;
    }

    /**
     * Whether a query-string filter is present and not the "all" sentinel.
     */
    protected function hasQueryFilter(Request $request, string $key): bool
    {
        if (! $request->filled($key)) {
            return false;
        }

        return trim((string) $request->input($key)) !== 'all';
    }
}
