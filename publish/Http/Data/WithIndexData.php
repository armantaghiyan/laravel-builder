<?php

namespace App\Http\Data;

use Spatie\LaravelData\Attributes\Validation\Max;

trait WithIndexData {

	public ?string $id = '';
	public ?string $search = '';
	#[Max(100)]
	public ?int $page_rows = 7;
	public ?int $page = 1;
	public ?string $sort = 'id';
	public ?string $sort_type = 'desc';
}
