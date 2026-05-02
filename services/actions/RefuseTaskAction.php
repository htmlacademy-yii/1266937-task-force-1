<?php

namespace app\services\actions;

use app\models\Task;

class RefuseTaskAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Отказаться';
    }

    public function getCodeName(): string
    {
        return 'refusal';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        return $status === Task::STATUS_IN_PROGRESS && $userId === $contractorId;
    }
}
