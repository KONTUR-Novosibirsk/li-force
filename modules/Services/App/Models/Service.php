<?php

namespace Modules\Services\App\Models;

use App\Models\BaseModel;
use Galtsevt\LaravelSeo\App\Traits\HasSeo;
use Galtsevt\LaravelStorage\App\Interfaces\Imageable;
use Galtsevt\LaravelStorage\App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kontur\Dashboard\App\Casts\HtmlPurify;

class Service extends BaseModel implements Imageable
{
    use HasFactory, HasImages, HasSeo;

    protected $fillable = [
        'name',
        'alias',
        'description',
        'short_description',
        'price',
        'is_active',
        'sort',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'description' => HtmlPurify::class,
    ];

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function image()
    {
        return $this->getImagesByGroup('preview')?->first();
    }

    public function imageGroups(): array
    {
        return [
            'preview' => [
                'single' => true,
            ],
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->when(isset($filters['active']) && in_array($filters['active'], [0, 1]), function ($query, $active) use ($filters) {
            return $query->where('is_active', $filters['active']);
        });
    }
}
