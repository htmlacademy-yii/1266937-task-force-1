<?php

namespace app\services\actions;

use Yii;
use app\models\Task;

class AcceptAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Принять';
    }

    public function getCodeName(): string
    {
        return 'accept';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        return $userId === $customerId && $status === Task::STATUS_NEW && $contractorId === null;
    }

    public function getType(): string
    {
        return self::TYPE_RESPONSE;
    }

    public function isModal(): bool
    {
        return false;
    }

    public function execute(Task $task, array $params = []): bool
    {
        $responseId = $params['responseId'] ?? null;
        $response = $task->getResponses()
            ->andWhere(['id' => $responseId])
            ->one();

        if (!$response) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->contractor_id = $response->contractor_id;
            $task->STATUS = $task->getNextStatus($this);

            if (!$task->save()) {
                throw new \Exception('Не удалось обновить статус задания');
            }

            $response->setSTATUSToAccepted();

            if (!$response->save()) {
                throw new \Exception('Не удалось принять отклик');
            }

            $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            Yii::error("Ошибка транзакции при принятии отклика: " . $e->getMessage());

            return false;
        }
    }
}
