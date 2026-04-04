<?php

namespace Taskforce\Actions;

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

    public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool
    {
        return $userId !== $customerId && $contractorId === null;
    }
}
