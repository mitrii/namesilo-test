<?php

namespace App\Controllers\Params;

use App\Utils\Domains;
use yii\base\Model;

class GetDomainPricesParams extends Model
{
    /** @var string */
    public $search;

    public function rules()
    {
        return [
            ['search', 'required'],
            ['search', function ($attribute, $params) {
                if (!Domains::valid($this->$attribute)) {
                    $this->addError($attribute, 'Invalid domain');
                }
            }]
        ];
    }

}
