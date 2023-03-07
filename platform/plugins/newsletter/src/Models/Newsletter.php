<?php

namespace Woo\Newsletter\Models;

use Woo\Base\Casts\SafeContent;
use Woo\Base\Models\BaseModel;
use Woo\Newsletter\Enums\NewsletterStatusEnum;

class Newsletter extends BaseModel
{
    protected $table = 'newsletters';

    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'status' => NewsletterStatusEnum::class,
    ];
}
