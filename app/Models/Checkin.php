
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = ['user_id','check_in','check_out'];
}
