<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Team
 *
 * @property integer user_id
 * @property string name
 * @package App\Models
 */
class Team extends Model
{
    public $table = "teams";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'name',
    ];

    public static $rules = [
        'name' => 'required|unique:teams'
    ];

    /**
     * @return BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class);
    }
}
