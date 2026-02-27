<?php
namespace App\Models;
use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;
class Loan extends Model { use BelongsToGroup; protected $fillable=['group_id','member_id','loan_product_id','status','principal','interest_rate','duration_months','disbursed_at','closed_at']; protected $casts=['disbursed_at'=>'datetime','closed_at'=>'datetime']; }
