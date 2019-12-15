<?php

namespace App\Controllers\Params;

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
                if (!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]){1,126}$/', $this->$attribute)) {
                    $this->addError($attribute, 'Invalid domain');
                }
            }]
        ];
    }

}
