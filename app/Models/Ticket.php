<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'Open';
    public const STATUS_ASSIGNED = 'Assigned';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_CLOSED = 'Closed';

    protected $guarded = [];

    public function sla()
    {
        return $this->belongsTo(Sla::class);
    }

    public function emails()
    {
        return $this->hasMany(Log_email::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assignee', 'id');
    }
}
