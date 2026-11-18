<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

        const FINE_PER_DAY = 5000; // Rp5.000/hari

    protected $fillable = [
        'user_id', 'book_id', 'loan_date', 'return_date',
        'actual_return_date', 'status', 'condition',
        'late_days', 'fine', 'payment_status', 'paid_at',
        'payment_method', 'payment_notes', 'notes',
        'payment_proof'
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
        'paid_at' => 'datetime',
        'fine' => 'decimal:2',
    ];

    // ============ RELASI ============
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // ============ HELPER ============
    public function calculateLateDays(): int
    {
        if (!$this->actual_return_date) return 0;
        $late = $this->return_date->diffInDays($this->actual_return_date, false);
        return $late > 0 ? $late : 0;
    }

    public function calculateFine(): float
    {
        return $this->calculateLateDays() * self::FINE_PER_DAY;
    }

    public function isLate(): bool
    {
        return $this->status === 'approved' && $this->return_date < now();
    }

    public function hasFine(): bool
    {
        return $this->fine > 0;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Dipinjam',
            'rejected' => 'Ditolak',
            'returned' => 'Dikembalikan',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-emerald-100 text-emerald-700',
            'rejected' => 'bg-rose-100 text-rose-700',
            'returned' => 'bg-slate-100 text-slate-700',
            default => 'bg-slate-100 text-slate-700',
        };
    }
}