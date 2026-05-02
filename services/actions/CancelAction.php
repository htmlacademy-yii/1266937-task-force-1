<?php

namespace app\services\actions;

use app\models\Task;

class CancelAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Отменить';
    }

    public function getCodeName(): string
    {
        return 'cancel';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        return $userId === $customerId && $contractorId === null;
    }

    public function isModal(): bool
    {
        return false;
    }
}
