<?php

namespace app\services\actions;

use app\models\Task;

abstract class AbstractAction
{
    abstract public function getName(): string;

    abstract public function getCodeName(): string;

    abstract public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool;

    public function execute(Task $task, array $params = [])
    {
        $nextStatus = $task->getNextStatus($this);

        if ($nextStatus) {
            $task->STATUS = $nextStatus;
        }

        return $task->save();
    }
}
