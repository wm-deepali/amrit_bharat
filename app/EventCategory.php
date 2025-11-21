<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventCategory extends Model
{
    use HasFactory;

    protected $table = 'event_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    /**
     * Automatically generate slug when creating a category
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Check if category is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Scope for only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
