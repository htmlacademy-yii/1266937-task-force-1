<?php

namespace Taskforce;

use Taskforce\Actions\AbstractAction;
use Taskforce\Actions\AcceptAction;
use Taskforce\Actions\CancelAction;
use Taskforce\Actions\CompleteAction;
use Taskforce\Actions\RefuseAction;
use Taskforce\Actions\RespondAction;

class Task
{
    // Статусы
    public const string STATUS_NEW = 'new';
    public const string STATUS_CANCELED = 'canceled';
    public const string STATUS_IN_PROGRESS = 'in_progress';
    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_FAILED = 'failed';

    private int $customerId;
    private ?int $contractorId;
    private string $currentStatus;

    public function __construct(int $customerId, ?int $contractorId = null, ?string $status = self::STATUS_NEW)
    {
        $this->customerId = $customerId;
        $this->contractorId = $contractorId;
        $this->currentStatus = $status;
    }

    /**
     * Возвращает карту статусов задания
     *
     * @return string[] Ассоциативный массив, где ключ - внутреннее имя, значение - название статуса
     */
    public static function getStatusMapping(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /**
     * Возвращает статус, в который перейдет задание после выполнения конкретного действия
     *
     * @param AbstractAction $action Объект класса-действия
     * @return string|null Следующий статус задания или null, если статус не меняется
     */
    public function getNextStatus(AbstractAction $action): ?string
    {
        // Возвращает имя класса объекта
        $actionClass = \get_class($action);

        return match ($actionClass) {
            AcceptAction::class => self::STATUS_IN_PROGRESS,
            CancelAction::class => self::STATUS_CANCELED,
            CompleteAction::class => self::STATUS_COMPLETED,
            RefuseAction::class => self::STATUS_FAILED,
            default => null
        };
    }

    /**
     * Возвращает массив объектов-действий для конкретного статуса
     *
     * @param string $status Статус
     * @return AbstractAction[] Массив объектов возможных действий для текущего статуса
     */
    private function getActionsByStatus(string $status): array
    {
        return match ($status) {
            self::STATUS_NEW => [
                new CancelAction(),
                new RespondAction(),
                new AcceptAction()
            ],
            self::STATUS_IN_PROGRESS => [
                new CompleteAction(),
                new RefuseAction()
            ],
            default => []
        };
    }

    /**
     * Возвращает массив объектов доступных действий для пользователя
     *
     * @param int $userId Id пользователя
     * @return AbstractAction[] Массив объектов доступных действий или пустой массив, если нет действий
     */
    public function getAvailableActions(int $userId): array
    {
        $actions = $this->getActionsByStatus($this->currentStatus);

        // Фильтр объектов в зависимости от роли пользователя
        return array_filter(
            $actions,
            fn($action) => $action->isAllowed($userId, $this->customerId, $this->contractorId)
        );
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getContractorId(): ?int
    {
        return $this->contractorId;
    }

    public function getCurrentStatus(): ?string
    {
        return $this->currentStatus;
    }
}
