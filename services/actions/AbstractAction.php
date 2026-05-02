<?php

namespace app\services\actions;

use app\models\Task;
use yii\base\Model;

abstract class AbstractAction
{
    public const string TYPE_TASK = 'task';
    public const string TYPE_RESPONSE = 'response';

    public function getModel(): ?Model
    {
        return null;
    }

    abstract public function getName(): string;

    abstract public function getCodeName(): string;

    abstract public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool;

    public function getType(): string
    {
        return self::TYPE_TASK;
    }

    public function getUrl(int $taskId, ?int $responseId = null): array
    {
        $params = [
            'tasks/handle',
            'id' => $taskId,
            'actionCodeName' => $this->getCodeName()
        ];

        if ($responseId && $this->getType() === self::TYPE_RESPONSE) {
            $params['responseId'] = $responseId;
        }

        return $params;
    }

    public function isModal(): bool
    {
        return true;
    }

    public function execute(Task $task, array $params = []): bool
    {
        $nextStatus = $task->getNextStatus($this);

        if ($nextStatus) {
            $task->STATUS = $nextStatus;
        }

        return $task->save();
    }
}
