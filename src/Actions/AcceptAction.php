<?php

namespace Taskforce\Actions;

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

    public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool
    {
        return $userId === $customerId && $contractorId === null;
    }
}
