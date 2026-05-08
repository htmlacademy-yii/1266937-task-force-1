<?php

namespace app\services\actions;

use Yii;
use app\models\Response;

class RespondAction extends AbstractAction
{
    private ?Response $model = null;

    public function getModel(): Response
    {
        if ($this->model === null) {
            $this->model = new Response();
        }

        return $this->model;
    }
    public function getName(): string
    {
        return 'Откликнуться';
    }

    public function getCodeName(): string
    {
        return 'act_response';
    }

    public function isAllowed(int $userId, int $customerId, ?int $contractorId, string $status): bool
    {
        if (!Yii::$app->authManager->checkAccess($userId, 'contractor')) {
            return false;
        }

        if ($userId === $customerId || $contractorId !== null) {
            return false;
        }

        $taskId = Yii::$app->request->get('id');

        $isUserResponded = Response::find()
            ->where(['task_id' => $taskId, 'contractor_id' => $userId])
            ->exists();

        if ($isUserResponded) {
            return false;
        }

        return true;
    }

    public function execute($task, $params = []): bool
    {
        $response = $this->getModel();

        if ($response->load($params)) {
            $response->task_id = $task->id;
            $response->contractor_id = Yii::$app->user->id;
            $response->setSTATUSToNew();

            if ($response->validate()) {
                return $response->save(false);
            }
        }

        return false;
    }
}
