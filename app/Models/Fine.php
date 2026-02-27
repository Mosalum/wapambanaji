<?php
namespace App\Models;
use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;
class Fine extends Model { use BelongsToGroup; protected $fillable=['group_id','member_id','fine_type_id','amount','reason','status','applied_at']; protected $casts=['applied_at'=>'datetime']; }
