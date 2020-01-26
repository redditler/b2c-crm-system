<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeedLabel extends Model
{
    /**
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|Model[]|null
     */
    public function getLabels($id)
    {
        return Model::find($id);
    }
}
