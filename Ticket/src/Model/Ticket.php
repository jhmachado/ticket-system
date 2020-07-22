<?php

namespace Ticket\Model;

use Illuminate\Database\Eloquent\Model;

final class Ticket extends Model
{
    private const TABLE_NAME = "ticket";
    
    protected $fillable = [
        "id",
        "title",
        "description",
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    public function getTable(): string
    {
        return self::TABLE_NAME;
    }

    public function getIncrementing(): bool
    {
        return false;
    }
}
