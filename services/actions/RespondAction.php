<?php

namespace app\services\actions;

use Yii;
use app\models\Response;

class RespondAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Откликнуться';
    }

    public function getCodeName(): string
    {
        return 'respond';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        return $userId !== $customerId && $contractorId === null;
    }

    public function execute($task, $params = []): bool
    {
        $response = new Response();

        if ($response->load($params) && $response->validate()) {
            $response->task_id = $task->id;
            $response->contractor_id = Yii::$app->user->id;
            $response->STATUS = 'new';

            return $response->save(false);
        }

        return false;
    }
}
