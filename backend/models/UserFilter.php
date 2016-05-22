<?php

namespace backend\models;

use common\enums\Gender;
use common\enums\OAuthName;
use common\enums\UserStatus;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class BannersFilter
 * @package backend\models
 */
class UserFilter extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['status', 'in', 'range' => UserStatus::getValues()],
            [['username', 'email'], 'safe'],
        ];
    }


    public function search()
    {
        $query = self::find();

        $query->andFilterWhere([
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'status' => $this->status,
        ]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ]
        ]);
    }
}