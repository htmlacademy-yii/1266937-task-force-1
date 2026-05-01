<?php

namespace app\services\actions;

use Yii;
use app\models\Task;
use app\models\Review;

class CompleteAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Завершить';
    }

    public function getCodeName(): string
    {
        return 'complete';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        return $status === Task::STATUS_IN_PROGRESS && $userId === $customerId;
    }

    public function execute(Task $task, array $params = [])
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->STATUS = $task->getNextStatus($this);

            $review = new Review();

            // валидация
            if ($review->load($params)) {
                $review->task_id = $task->id;
                $review->customer_id = $task->customer_id;
                $review->contractor_id = $task->contractor_id;

                if (!$review->save()) {
                    throw new \Exception();
                }
            }

            $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
