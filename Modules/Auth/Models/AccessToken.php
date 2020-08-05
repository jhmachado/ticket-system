<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

final class AccessToken extends Model
{
    protected $fillable = [
        "access_token",
        "expires_in",
    ];
}
