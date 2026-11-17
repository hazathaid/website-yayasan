<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'program_id',
        'donor_name',
        'email',
        'phone',
        'amount',
        'method',
        'proof_path',
        'status',
        'note',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Donation $donation) {
            if ($donation->status === 'verified') {
                $donation->adjustProgramCollected($donation->program_id, (float) $donation->amount);
            }
        });

        static::updated(function (Donation $donation) {
            if (! $donation->wasChanged(['status', 'program_id', 'amount'])) {
                return;
            }

            $originalStatus = $donation->getOriginal('status');
            $originalProgramId = $donation->getOriginal('program_id');
            $originalAmount = (float) $donation->getOriginal('amount');

            if ($originalStatus === 'verified') {
                $donation->adjustProgramCollected($originalProgramId, -$originalAmount);
            }

            if ($donation->status === 'verified') {
                $donation->adjustProgramCollected($donation->program_id, (float) $donation->amount);
            }
        });

        static::deleted(function (Donation $donation) {
            if ($donation->status === 'verified') {
                $donation->adjustProgramCollected($donation->program_id, -(float) $donation->amount);
            }
        });
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    protected function adjustProgramCollected(?int $programId, float $amount): void
    {
        if (! $programId || $amount === 0.0) {
            return;
        }

        $program = Program::find($programId);

        if (! $program) {
            return;
        }

        $program->update([
            'collected_amount' => max(0, (float) $program->collected_amount + $amount),
        ]);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
        };
    }
}
