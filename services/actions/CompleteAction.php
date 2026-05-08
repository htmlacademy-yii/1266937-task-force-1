<?php

namespace app\services\actions;

use Yii;
use app\models\Task;
use app\models\Review;

class CompleteAction extends AbstractAction
{

    private ?Review $model = null;

    public function getModel(): Review
    {
        if ($this->model === null) {
            $this->model = new Review();
        }
        return $this->model;
    }

    public function getName(): string
    {
        return 'Завершить';
    }

    public function getCodeName(): string
    {
        return 'completion';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        return $status === Task::STATUS_IN_PROGRESS && $userId === $customerId;
    }

    public function execute(Task $task, array $params = []): bool
    {
        $review = $this->getModel();

        if (!$review->load($params) && !$review->load($params, '')) {
            return false;
        }

        $review->task_id = $task->id;
        $review->customer_id = $task->customer_id;
        $review->contractor_id = $task->contractor_id;

        if (!$review->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->STATUS = $task->getNextStatus($this);

            if (!$task->save()) {
                throw new \Exception('Не удалось обновить статус задания');
            }

            if (!$review->save(false)) {
                throw new \Exception('Не удалось сохранить отзыв');
            }

            $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            Yii::error("Ошибка транзакции при завершении задания: " . $e->getMessage());

            return false;
        }
    }
}
