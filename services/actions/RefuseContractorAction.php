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

  public function getType(): string
  {
    return self::TYPE_RESPONSE;
  }

  public function isModal(): bool
  {
    return true;
  }

  public function execute(Task $task, array $params = []): bool
  {
    $responseId = $params['responseId'] ?? null;
    $response = Response::findOne($responseId);

    if ($response) {
      $response->setSTATUSToRejected();

      return $response->save();
    }

    return false;
  }
}