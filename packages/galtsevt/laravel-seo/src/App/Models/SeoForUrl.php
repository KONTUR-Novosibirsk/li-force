<?php

namespace Galtsevt\LaravelSeo\App\Models;

use App\Models\BaseModel;
use Galtsevt\LaravelSeo\App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoForUrl extends BaseModel
{
    use HasFactory, HasSeo;

    protected $with = ['seo'];

    protected $table = 'url_seo';

    protected $guarded = false;
}
