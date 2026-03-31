<?php

namespace Taskforce\Actions;

abstract class AbstractAction
{
    abstract public function getName(): string;

    abstract public function getCodeName(): string;

    abstract public function isAllowed(int $userId, int $customerId, ?int $contractorId): bool;
}
