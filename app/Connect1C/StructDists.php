<?php

namespace App\Connect1C;

use Illuminate\Database\Eloquent\Model;

class StructDists extends Model
{
    protected $connection = 'mysql_1C';
    protected $table = 'struct_dists';
}
