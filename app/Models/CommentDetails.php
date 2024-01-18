<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'customer_phone',
        'customer_name',
        'content',
        'like_qty',
        'unlike_qty',
        'status',
    ];
    protected $table = 'comment_details';
    protected $primaryKey = 'id';
    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
