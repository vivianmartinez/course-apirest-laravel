<?php

namespace App\Models;

use App\Models\Scopes\FilterScope;
use App\Models\Scopes\SortScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([
    FilterScope::class,
    SortScope::class
])]
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by',
        'assigned_to',
        'category_id'
    ];

    // public function user(){
    //     return $this->belongsTo(User::class,'user_id','id');
    // }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeGetOrPaginate($query)
    {
        return request('per_page') ? $query->paginate(request('per_page')) : $query->get();
    }
}
