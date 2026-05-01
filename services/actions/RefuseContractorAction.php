<?php

namespace app\services\actions;

use app\models\Task;
use app\models\Response;

class RefuseContractorAction extends AbstractAction
{
  public function getName(): string
  {
    return 'Отказать';
  }

  public function getCodeName(): string
  {
    return 'refuse-contractor';
  }

  public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
  {
    return $status === Task::STATUS_NEW && $userId === $customerId;
  }

  public function execute(Task $task, array $params = []): bool
  {
    $responseId = $params['responseId'] ?? null;
    $response = Response::findOne($responseId);

    if ($response) {
      $response->STATUS = 'rejected';

      return $response->save();
    }

    return false;
  }
}