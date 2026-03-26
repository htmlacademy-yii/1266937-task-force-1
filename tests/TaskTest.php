<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Task.php';

class TaskTest extends TestCase
{
    public function testGetNextStatus(): void
    {
        $task = new Task(customerId: 1);

        $this->assertEquals(Task::STATUS_CANCELED, $task->getNextStatus(Task::ACTION_CANCEL));
        $this->assertEquals(Task::STATUS_IN_PROGRESS, $task->getNextStatus(Task::ACTION_RESPOND));
        $this->assertEquals(Task::STATUS_COMPLETED, $task->getNextStatus(Task::ACTION_COMPLETE));
        $this->assertEquals(Task::STATUS_FAILED, $task->getNextStatus(Task::ACTION_REFUSE));

        $this->assertNull($task->getNextStatus('unknown_action'));
    }

    public function testGetAvailableActions(): void
    {
        $task = new Task(customerId: 1);

        $actionNew = $task->getAvailableActions(Task::STATUS_NEW);
        $this->assertContains(Task::ACTION_CANCEL, $actionNew);
        $this->assertContains(Task::ACTION_RESPOND, $actionNew);

        $actionInProgress = $task->getAvailableActions(Task::STATUS_IN_PROGRESS);
        $this->assertContains(Task::ACTION_COMPLETE, $actionInProgress);
        $this->assertContains(Task::ACTION_REFUSE, $actionInProgress);

        $this->assertEmpty($task->getAvailableActions(Task::STATUS_COMPLETED));
    }
}
