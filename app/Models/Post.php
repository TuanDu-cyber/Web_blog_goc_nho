<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'image',
        'content',
        'published_at',
        'scheduled_at',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Tự tạo slug khi tạo bài; đuôi ngẫu nhiên để 2 bài cùng tiêu đề không trùng
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . Str::lower(Str::random(6));
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    // Nhãn trạng thái để hiển thị ở danh sách "Bài của tôi"
    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PUBLISHED => 'Đã đăng',
            self::STATUS_SCHEDULED => 'Hẹn giờ: ' . $this->scheduled_at?->format('d/m/Y H:i'),
            default                => 'Nháp',
        };
    }


    //Bài vt thuộc về 1 user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Bài vt thuộc về 1 danh mục
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //Bài vt có nhiều tag
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Bài vt có nhiều bình luận
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Route model binding và route() dùng slug thay cho id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
