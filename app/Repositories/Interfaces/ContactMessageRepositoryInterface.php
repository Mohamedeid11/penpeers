<?php
namespace App\Repositories\Interfaces;
use App\Models\ContactMessage;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContactMessageRepositoryInterface{
    public function paginateCustomOrder(string $by, $per_page): LengthAwarePaginator;
}
        