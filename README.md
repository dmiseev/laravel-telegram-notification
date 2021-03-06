# Telegram Notifications for Laravel 5.6

This package makes it easy to send Telegram notification using [Telegram Bot API](https://core.telegram.org/bots) with Laravel 5.5.

## Installation

You can install the package via composer:

``` bash
composer require dmiseev/laravel-telegram-notification
```

## Setting up your Telegram Bot

Talk to [@BotFather](https://core.telegram.org/bots#6-botfather) and generate a Bot API Token.

Then, configure your Telegram Bot API Token:

```php
// config/services.php
...
'telegram' => [
    'token' => env('TELEGRAM_TOKEN', 'YOUR BOT TOKEN HERE')
],
...
```

## Usage

You can now use the channel in your `via()` method inside the Notification class.

``` php
use Dmiseev\TelegramNotification\TelegramChannel;
use Dmiseev\TelegramNotification\TelegramMessage;
use Illuminate\Notifications\Notification;

class WithdrawCreate extends Notification
{
    /**
     * @var Withdraw
     */
    private $withdraw;
    
    /**
     * @var User
     */
    private $user;

    /**
     * @param Withdraw $withdraw
     */
    public function __construct(Withdraw $withdraw, User $user)
    {
        $this->withdraw = $withdraw;
        $this->user = $user;
    }
    
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($this->user->telegram_user_id)
            ->content("*HI!* \n One of your withdraws has been created!")
            ->button('View Withdraw', url('/withdraws/' . $this->withdraw->id));
    }
}
```

### Routing a message

You can either send the notification by providing with the chat id of the recipient to the `to($chatId)` method like shown in the above example or add a `routeNotificationForTelegram()` method in your notifiable model:

``` php
...
/**
 * @return int
 */
public function routeNotificationForTelegram()
{
    return $this->telegram_user_id;
}
...
```

### Available Message methods

- `to($chatId)`: (integer) Recipient's chat id.
- `content('')`: (string) Notification message, supports markdown. For more information on supported markdown styles, check out these [docs](https://telegram-bot-sdk.readme.io/docs/sendmessage#section-markdown-style).
- `button($text, $url)`: (string) Adds an inline "Call to Action" button. You can add as many as you want and they'll be placed 2 in a row.
- `options([])`: (array) Allows you to add additional or override `sendMessage` payload (A Telegram Bot API method used to send message internally). For more information on supported parameters, check out these [docs](https://telegram-bot-sdk.readme.io/docs/sendmessage).

## Security

If you discover any security related issues, please email dmiseev@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
