<?php

namespace Taskforce\Actions;

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

    public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool
    {
        return $userId === $customerId && $contractorId === null;
    }
}
