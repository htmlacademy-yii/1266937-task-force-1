<?php

namespace Taskforce\Actions;

class RefuseAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Отказаться';
    }

    public function getCodeName(): string
    {
        return 'refuse';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool
    {
        return $userId === $contractorId;
    }
}
