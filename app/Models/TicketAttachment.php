<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ticket_id', 'user_id', 'file_name', 'file_path', 'mime_type', 'size'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
