<?php
    namespace App\Traits;
    use Illuminate\Http\Request;

    trait Filters
    {
        protected function getFilters(Request $request) : array { return $request->all()['filters'] ?? []; }

        protected function defaultFilters(): array { return []; }
    }
