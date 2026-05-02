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
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->STATUS = $task->getNextStatus($this);

            if (!$task->save()) {
                throw new \Exception('Не удалось обновить статус задачи');
            }

            if ($review->load($params)) {
                $review->task_id = $task->id;
                $review->customer_id = $task->customer_id;
                $review->contractor_id = $task->contractor_id;

                if ($review->validate() && $review->save(false)) {
                    $transaction->commit();

                    return true;
                }
            }

            throw new \Exception('Ошибка валидации или загрузки');
        } catch (\Throwable $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
