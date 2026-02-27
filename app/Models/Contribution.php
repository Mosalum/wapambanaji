<?php
namespace App\Models;
use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;
class Contribution extends Model { use BelongsToGroup; protected $fillable=['group_id','member_id','meeting_id','contribution_type_id','amount','paid_at','receipt_id']; protected $casts=['paid_at'=>'datetime']; }
