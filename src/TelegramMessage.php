<?php
declare(strict_types=1);

namespace Dmiseev\TelegramNotification;

class TelegramMessage
{
    /**
     * @var array Params payload.
     */
    public $payload = [];

    /**
     * @var array Inline Keyboard Buttons.
     */
    protected $buttons = [];

    /**
     * @param string $content
     * @return static
     */
    public static function create($content = ''): TelegramMessage
    {
        return new static($content);
    }

    /**
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content($content);
        $this->payload['parse_mode'] = 'Markdown';
    }

    /**
     * @param $chatId
     *
     * @return $this
     */
    public function to($chatId): TelegramMessage
    {
        $this->payload['chat_id'] = $chatId;

        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function content($content): TelegramMessage
    {
        $this->payload['text'] = $content;

        return $this;
    }

    /**
     * @param string $text
     * @param string $url
     *
     * @return $this
     */
    public function button($text, $url): TelegramMessage
    {
        $this->buttons[] = compact('text', 'url');
        $replyMarkup['inline_keyboard'] = array_chunk($this->buttons, 2);
        $this->payload['reply_markup'] = json_encode($replyMarkup);

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function options(array $options): TelegramMessage
    {
        $this->payload = array_merge($this->payload, $options);

        return $this;
    }

    /**
     * @return bool
     */
    public function toNotGiven(): bool
    {
        return !isset($this->payload['chat_id']);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}