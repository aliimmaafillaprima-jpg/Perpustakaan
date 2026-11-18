<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'type', 'url', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    public function icon(): string
    {
        return match($this->type) {
            'success' => '✅',
            'error' => '❌',
            'warning' => '⚠️',
            default => 'ℹ️',
        };
    }

    public function color(): string
    {
        return match($this->type) {
            'success' => 'text-emerald-600 bg-emerald-50',
            'error' => 'text-rose-600 bg-rose-50',
            'warning' => 'text-amber-600 bg-amber-50',
            default => 'text-indigo-600 bg-indigo-50',
        };
    }

    // Helper: kirim notifikasi ke user
    public static function send(int $userId, string $title, string $message, string $type = 'info', ?string $url = null): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'url' => $url,
        ]);
    }
}