<?php
/**
 * Created by PhpStorm.
 * User: kkurkin
 * Date: 4/21/15
 * Time: 4:02 PM
 */

namespace backend\components;


use common\models\User;
use Yii;
use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    protected function setFlash($type, $message)
    {

        Yii::$app->getSession()->setFlash($type, $message);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return Yii::$app->user->identity;
    }
}