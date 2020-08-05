<?php

namespace Modules\Ticket\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

final class Ticket extends Model
{
    private const TABLE_NAME = "ticket";

    protected $fillable = [
        "id",
        "title",
        "description",
        "closed_at",
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'closed_at' => 'date:Y-m-d H:i:s',
    ];

    public function getTable(): string
    {
        return self::TABLE_NAME;
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function close(): void {
        $this->closed_at = Date::now();
    }
}
