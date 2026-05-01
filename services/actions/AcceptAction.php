<?php

namespace app\services\actions;

use Yii;
use app\models\Task;
use app\models\Response;

// отклики
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
        return $userId === $customerId && $contractorId === null;
    }

    public function execute(Task $task, array $params = [])
    {
        $responseId = $params['responseId'] ?? null;
        $response = Response::findOne($responseId);

        if (!$response) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $task->contractor_id = $response->contractor_id;
            $task->STATUS = $task->getNextStatus($this);

            if (!$task->save()) {
                throw new \Exception();
            }

            $response->STATUS = 'accepted';

            if (!$response->save()) {
                throw new \Exception();
            }

            $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
