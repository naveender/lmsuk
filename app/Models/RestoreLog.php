<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestoreLog extends Model
{
    protected $fillable = ['file_name', 'status','type','action','messsage', 'steps'];
    protected $casts = ['steps' => 'array'];

    public function addStep(string $step)
    {
        $this->steps = array_merge($this->steps ?? [], [$step]);
        $this->save();
    }
}
