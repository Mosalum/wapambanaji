<?php
namespace App\Models;
use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;
class Repayment extends Model { use BelongsToGroup; protected $fillable=['group_id','loan_id','member_id','amount','penalty_amount','paid_at','receipt_id','idempotency_key']; protected $casts=['paid_at'=>'datetime']; }
