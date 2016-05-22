<?php
/* @var $this  View*/
/* @var $filter UserFilter */

use backend\components\View;
use backend\models\UserFilter;
use common\enums\UserRole;
use common\enums\UserStatus;
use common\models\User;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = $this->title;

?>

<?=Html::a("Новый пользователь", ['/users/create'], ['class' => 'btn btn-success'])?>
<br><br>

<?= GridView::widget([
    'dataProvider' => $filter->search(),
    'filterModel' => $filter,
    'rowOptions' => function(User $model) {
        if ($model->status == UserStatus::DELETED) {
            return ['class' => 'danger'];
        } else {
            return [];
        }
    },
    'columns' => [
        [
            'attribute' => 'id',
            'format' => 'html',
            'value' => function (User $model) {
                return Html::a($model->id, $model->getAdminUrl());
            },
            'contentOptions' => [
                'class' => 'id'
            ]
        ],
        [
            'attribute' => 'created_at',
            'filter' => false,
            'contentOptions' => [
                'class' => 'centred'
            ]
        ],
        [
            'attribute' => 'role',
            'value' => function (User $model) {
                return UserRole::getName($model->role);
            },
            'filter' => UserRole::getList(),
        ],
        [
            'attribute' => 'username',
        ],
        [
            'attribute' => 'email',
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{update} {delete}',
            'contentOptions' => [
                'class' => 'centred'
            ]
        ]
    ],
]);?>