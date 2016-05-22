<?php
/**
 * Created by PhpStorm.
 * User: kkurkin
 * Date: 4/20/15
 * Time: 5:36 PM
 */

namespace console\controllers;


use common\enums\ParticipantType;
use common\enums\TnGameScoreRating;
use common\enums\TnScoreRating;
use common\enums\VoteOptionType;
use common\models\Event;
use common\models\FeedItem;
use common\models\GameScoreRating;
use common\models\Participant;
use common\models\ProfileAction;
use common\models\Push;
use common\models\PushLog;
use common\models\ScoreRating;
use common\models\User;
use common\models\VoteOption;
use common\models\Widget;
use console\components\Controller;
use yii\helpers\ArrayHelper;

class ServiceController  extends Controller
{

    public function actionTest()
    {
        $user = User::findB()->where([''])->all();
        $count = count($participants);
        $this->profile("Found $count participants");
        $cnt = 0;
        foreach($participants as $participant) {
            $oldPath = \Yii::getAlias('@upload')."/images/participants/orig/{$participant->thumbnail}";
            $newPath = \Yii::getAlias('@upload')."/images/participants/thumbs/{$participant->thumbnail}";
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);
                $cnt++;
            }
        }
        $this->log("$cnt thumbs moved");
        $this->endProfile();
    }

    public function actionDeleteTalentThumbs()
    {
        $count = Participant::updateAll(['thumbnail' => null], ['type' => ParticipantType::TALENT]);
        $this->log("$count talent thumbs dropped");
        $this->endProfile();
    }

    public function actionParticipantsStripTags()
    {
        /** @var Participant[] $models */
        $models = Participant::find()->all();
        foreach($models as $model) {
            $model->updateAttributes(['bio' => strip_tags($model->bio)]);
        }
        $this->log(count($models)." participants updated");
        $this->endProfile();
    }

    public function actionCorrectActionGames()
    {
        $this->startProfile();
        $this->log('Starting update...');

        $count = \Yii::$app->db->createCommand("
            update profile_action
            set game_id = t.game_id
            from
            (
                select distinct profile_action.widget_id, widget.game_id
                from profile_action
                join widget on profile_action.widget_id = widget.id
                where profile_action.game_id is null and widget.game_id is not null
            )t
            where profile_action.widget_id = t.widget_id and profile_action.game_id is null"
        )->execute();

        $this->profile("$count actions updated");
        $this->endProfile();
    }

    public function actionResetSystem($pass)
    {
        if ($pass !== 'fight') {
            return;
        }

        \Yii::$app->db->transaction(function(){
            ScoreRating::deleteAll();
            GameScoreRating::deleteAll();
            ProfileAction::deleteAll();
//            Profile::deleteAll();

            PushLog::deleteAll();
            Push::deleteAll();


            Event::deleteAll();
            FeedItem::deleteAll();
            Widget::deleteAll();

//            Device::deleteAll();
        });

        $this->endProfile();

    }

    public function actionHideParticipants()
    {
        Participant::updateAll(['published' => false], ['type' => ParticipantType::TALENT]);

        $this->endProfile();
    }

    public function actionUpgradeVotes()
    {
        /** @var VoteOption[] $options */
        $options = VoteOption::find()->where(['name' => null])->with('participant')->all();
        $this->profile("Found ".count($options)." options");
        $count = 0;
        foreach($options as $option) {
            if ($option->type) {
                $option->updateAttributes(['name' => VoteOptionType::getName($option->type)]);
                $count++;
            } elseif ($option->participant_id) {
                $option->updateAttributes(['name' => $option->participant->name]);
                $count++;
            }
        }
        $this->log("$count options updated");
        $this->endProfile();
    }

    public function actionInitScoreRam($tableSpace = 'ram')
    {
        $tables = ArrayHelper::merge(TnGameScoreRating::getValues(), TnScoreRating::getValues());
        foreach ($tables as $table) {
            $command = "ALTER TABLE $table SET TABLESPACE $tableSpace";
            \Yii::$app->db->createCommand($command)->execute();
            $this->profile("$command");
            $command = "ALTER INDEX {$table}_profile_secret_idx SET TABLESPACE $tableSpace";
            \Yii::$app->db->createCommand($command)->execute();
            $this->profile("$command");
        }
        $this->endProfile();

    }
}