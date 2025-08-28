<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'sender_id', 'product_id', 'message', 'attachment_path', 'attachment_type', 'is_read', 'read_at'];

    /**
     * The relationships that should always be touched.
     *
     * @var array
     */
    protected $touches = ['conversation'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
