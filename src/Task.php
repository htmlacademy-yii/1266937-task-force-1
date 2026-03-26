<?php

namespace Taskforce;

class Task
{
    // Статусы
    public const string STATUS_NEW = 'new';
    public const string STATUS_CANCELED = 'canceled';
    public const string STATUS_IN_PROGRESS = 'in_progress';
    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_FAILED = 'failed';

    // Действия
    public const string ACTION_CANCEL = 'cancel';
    public const string ACTION_RESPOND = 'respond';
    public const string ACTION_COMPLETE = 'complete';
    public const string ACTION_REFUSE = 'refuse';

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
     * Возвращает карту действий с заданием
     *
     * @return string[] Ассоциативный массив, где ключ - внутреннее имя, значение - название действия
     */
    public static function getActionMapping(): array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_COMPLETE => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    /**
     * Возвращает статус, в который перейдет задание после выполнения конкретного действия
     *
     * @param string $action Действие с заданием
     * @return string|null Следующий статус задания или null
     */
    public function getNextStatus(string $action): ?string
    {
        return match ($action) {
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_RESPOND => self::STATUS_IN_PROGRESS,
            self::ACTION_COMPLETE => self::STATUS_COMPLETED,
            self::ACTION_REFUSE => self::STATUS_FAILED,
            default => null
        };
    }

    /**
     * Возвращает массив доступных действий для указанного статуса задания
     *
     * @param string $status Статус задания
     * @return string[] Доступные действия или пустой массив
     */
    public function getAvailableActions(string $status): array
    {
        return match ($status) {
            self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPOND],
            self::STATUS_IN_PROGRESS => [self::ACTION_COMPLETE, self::ACTION_REFUSE],
            default => []
        };
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
