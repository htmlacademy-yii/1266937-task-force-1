<?php

namespace Taskforce\Actions;

class RefuseTaskAction extends AbstractAction
{
    public function getName(): string
    {
        return 'Отказаться';
    }

    public function getCodeName(): string
    {
        return 'refuse-task';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool
    {
        return $userId === $contractorId;
    }
}
