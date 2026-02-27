<?php
namespace App\Models;
use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;
class Expense extends Model { use BelongsToGroup; protected $fillable=['group_id','expense_category_id','amount','narration','incurred_at','receipt_path']; protected $casts=['incurred_at'=>'datetime']; }
