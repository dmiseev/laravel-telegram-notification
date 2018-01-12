<?php
declare(strict_types=1);

namespace Dmiseev\TelegramNotification\Tests;

use Dmiseev\TelegramNotification\Telegram;
use Dmiseev\TelegramNotification\TelegramChannel;
use Dmiseev\TelegramNotification\TelegramMessage;
use Illuminate\Notifications\Notifiable;
use Orchestra\Testbench\TestCase;
use Mockery;
use Illuminate\Notifications\Notification;
use Mockery\Mock;

class ChannelTest extends TestCase
{
    /**
     * @var Mock
     */
    protected $telegram;

    /**
     * @var TelegramChannel
     */
    protected $channel;

    public function setUp()
    {
        parent::setUp();
        $this->telegram = Mockery::mock(Telegram::class);
        $this->channel = new TelegramChannel($this->telegram);
    }

    /**
     * @test
     */
    public function it_can_send_a_message()
    {
        $this->telegram->shouldReceive('sendMessage')->once()->with([
            'text' => 'Laravel Notification Channels are awesome!',
            'parse_mode' => 'Markdown',
            'chat_id' => 12345,
        ]);

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /**
     * @test
     * @expectedException \Dmiseev\TelegramNotification\TelegramException
     */
    public function it_throws_an_exception_when_it_could_not_send_the_notification_because_no_chat_id_provided()
    {
        $this->channel->send(new TestNotifiable(), new TestNotificationNoChatId());
    }
}

class TestNotifiable
{
    use Notifiable;

    /**
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return false;
    }
}

class TestNotification extends Notification
{
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create('Laravel Notification Channels are awesome!')->to(12345);
    }
}

class TestNotificationNoChatId extends Notification
{
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create();
    }
}