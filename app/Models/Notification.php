<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'link',
        'data',
        'read_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if notification is unread.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Create a notification for maintenance log created.
     */
    public static function maintenanceCreated(MaintenanceLog $log, int $userId): self
    {
        $komponen = $log->komponenPerangkat;
        $unit = $komponen->unitKomputer;
        $lab = $unit->laboratorium;

        return self::create([
            'user_id' => $userId,
            'type' => 'maintenance_created',
            'title' => 'Laporan Baru',
            'message' => "Laporan maintenance baru untuk {$komponen->kategori->nama} di {$lab->nama} - {$unit->nama}",
            'icon' => 'wrench-screwdriver',
            'link' => route('laboran.maintenance-log.show', $log),
            'data' => [
                'maintenance_log_id' => $log->id,
                'laboratorium' => $lab->nama,
                'unit' => $unit->nama,
            ],
        ]);
    }

    /**
     * Create a notification for maintenance status updated.
     */
    public static function maintenanceStatusUpdated(MaintenanceLog $log, string $oldStatus, int $userId): self
    {
        $statusLabels = [
            'pending' => 'Menunggu',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            'tidak_bisa_diperbaiki' => 'Tidak Bisa Diperbaiki',
        ];

        $komponen = $log->komponenPerangkat;
        $newStatusLabel = $statusLabels[$log->status] ?? $log->status;

        $icon = match ($log->status) {
            'selesai' => 'check-circle',
            'tidak_bisa_diperbaiki' => 'x-circle',
            'proses' => 'arrow-path',
            default => 'bell',
        };

        return self::create([
            'user_id' => $userId,
            'type' => 'maintenance_status_updated',
            'title' => 'Status Diperbarui',
            'message' => "Status maintenance {$komponen->kategori->nama} berubah menjadi {$newStatusLabel}",
            'icon' => $icon,
            'link' => route('laboran.maintenance-log.show', $log),
            'data' => [
                'maintenance_log_id' => $log->id,
                'old_status' => $oldStatus,
                'new_status' => $log->status,
            ],
        ]);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for recent notifications.
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->latest()->limit($limit);
    }
}
