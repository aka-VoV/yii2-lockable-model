<?php
/**
 * Created by PhpStorm.
 * User: vov
 * Date: 8/16/18
 * Time: 3:45 PM
 */

namespace dkit\lockable\common\components;


use dkit\lockable\common\models\Lockable;
use yii\base\Behavior;
use Yii;
use yii\db\ActiveRecord;

class LockableBehavior extends Behavior
{
    public $modelName;
    public $modelId;
    public $timeDuraion;
    public $lockableMessage;



    /**
     * @var
     */
    private $_lockableRecord;

    /**
     * @var
     */
    private $_current;


    /**
     *
     * @return events
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'unlock',
        ];
    }

    /**
     * @return \DateTime
     */
    public function getCurrent()
    {
        return $this->_current = new \DateTime('now');
    }


    /**
     * @return array|\dkit\lockable\common\models\Lockable|null|\yii\db\ActiveRecord
     */
    public function getLockableRecord()
    {
        return $this->_lockableRecord = Lockable::find()
            ->where([
                'model_name' => $this->modelName,
                'model_id'   => $this->owner->{$this->modelId},
            ])
            ->orderBy(['unlock_at' => SORT_DESC])
            ->one();
    }


    /**
     * @return bool
     */
    public function lock()
    {
        $lockable = ($this->lockableRecord !== null) ? $this->lockableRecord : new Lockable();
        $lockable->model_name = $this->modelName;
        $lockable->model_id = $this->owner->{$this->modelId};
        $lockable->user_id = \Yii::$app->user->id;
        $lockable->unlock_at = $this->unlockAt();

        return $lockable->save();
    }

    public function unlockAt()
    {
        $duration = $this->current;
        $duration->add(new \DateInterval('PT' . $this->timeDuraion . 'S'));

        return Yii::$app->formatter->asTimestamp($duration);

    }

    public function isLocked()
    {

        if ($this->lockableRecord && $this->checkLockedAt()) {

            if ($this->checkLockedBy()) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function checkLockedAt()
    {
        if (Yii::$app->formatter->asTimestamp($this->current) < $this->lockableRecord->unlock_at) {
            return true;
        }

        return false;
    }

    public function checkLockedBy()
    {
        if (Yii::$app->user->id === $this->lockableRecord->user_id) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function unlock()
    {
        if ($this->lockableRecord->delete()) {
            return true;
        }

        return false;
    }


    public function jsInterval()
    {
        return '
        setInterval(function(){
            $.post( window.location,{updateLockable:\'true\'}, function( data ) {
                console.log( data );
            });
        }, 10000);';
    }


}