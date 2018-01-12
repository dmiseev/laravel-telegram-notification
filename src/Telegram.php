<?php
declare(strict_types=1);

namespace Dmiseev\TelegramNotification;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class Telegram
{
    /**
     * @var HttpClient
     */
    protected $http;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * @param HttpClient $httpClient
     * @param string|null $token
     */
    public function __construct(HttpClient $httpClient, string $token = null)
    {
        $this->http = $httpClient;
        $this->token = $token;
    }

    /**
     * @return HttpClient
     */
    protected function httpClient(): HttpClient
    {
        return $this->http;
    }

    /**
     * <code>
     * $params = [
     *   'chat_id'                  => '',
     *   'text'                     => '',
     *   'parse_mode'               => '',
     *   'disable_web_page_preview' => '',
     *   'disable_notification'     => '',
     *   'reply_to_message_id'      => '',
     *   'reply_markup'             => '',
     * ];
     * </code>
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param array $params
     *
     * @var int|string $params ['chat_id']
     * @var string     $params ['text']
     * @var string     $params ['parse_mode']
     * @var bool       $params ['disable_web_page_preview']
     * @var bool       $params ['disable_notification']
     * @var int        $params ['reply_to_message_id']
     * @var string     $params ['reply_markup']
     *
     * @return ResponseInterface
     * @throws TelegramException
     */
    public function sendMessage($params): ResponseInterface
    {
        return $this->sendRequest('sendMessage', $params);
    }

    /**
     * Send an API request and return response.
     * @param $endpoint
     * @param $params
     *
     * @return ResponseInterface
     * @throws TelegramException
     */
    protected function sendRequest($endpoint, $params): ResponseInterface
    {
        if (empty($this->token)) {
            throw TelegramException::telegramBotTokenNotProvided('You must provide your telegram bot token to make any API requests.');
        }

        $endPointUrl = 'https://api.telegram.org/bot'.$this->token.'/'.$endpoint;

        try {
            return $this->httpClient()->post($endPointUrl, [
                'form_params' => $params,
            ]);

        } catch (ClientException $exception) {
            throw TelegramException::telegramRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw TelegramException::couldNotCommunicateWithTelegram();
        }
    }
}