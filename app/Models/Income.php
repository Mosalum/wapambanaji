<?php
namespace App\Models;
use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;
class Income extends Model { use BelongsToGroup; protected $fillable=['group_id','amount','source','received_at']; protected $casts=['received_at'=>'datetime']; }
