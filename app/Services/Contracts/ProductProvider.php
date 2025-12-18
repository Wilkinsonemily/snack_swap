<?php

namespace App\Services\Contracts;

interface ProductProvider
{
    /** @return array{items:array,next:?string,error:?string} */
    public function search(string $query, int $page = 1, int $pageSize = 24, array $opts = []): array;

    /** @return ?array{id:string,name:string,brand:?string,image:?string,category:?string,calories:?float,sugar:?float,fat:?float,sodium:?int,source:string} */
    public function detail(string $id): ?array;
}