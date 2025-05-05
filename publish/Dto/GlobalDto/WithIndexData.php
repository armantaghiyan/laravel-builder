<?php

namespace App\Dto\GlobalDto;

trait WithIndexData {

    public function __construct(
        public string $id = '',
        public string $search = '',
        public int    $page_rows = 7,
        public int    $page = 1,
        public string $sort = 'id',
        public string $sort_type = 'desc',
    ) {

    }
}
