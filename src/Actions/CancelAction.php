<?php

namespace Taskforce\Actions;

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

    public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool
    {
        return $userId === $customerId && $contractorId === null;
    }
}
