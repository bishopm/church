<?php
namespace Bishopm\Church\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Wireable;

class BulksmsService implements Wireable
{
    public $username;
    public $password;
    protected $baseUrl = 'https://api.bulksms.com/v1/';

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function toLivewire()
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value['username'], $value['password']);
    }

    public function checkcell($cell)
    {
        return strlen($cell) === 10 && preg_match("/^[0-9]+$/", $cell);
    }

    protected function client()
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->timeout(20)
            ->connectTimeout(10)
            ->acceptJson()
            ->baseUrl($this->baseUrl);
    }

    public function send_message($messages)
    {
        if (!is_array($messages) || !isset($messages[0])) {
            $messages = [$messages];
        }

        try {
            // Log::info('Sending SMS via BulkSMS', ['messages' => $messages]);

            $response = $this->client()
                ->throw()
                ->post('messages?auto-unicode=true&longMessageMaxParts=30', $messages);

            // Log::info('BulkSMS response', ['status' => $response->status(),'body' => $response->body()]);
            return $response->json();
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('BulkSMS request failed', [
                'message' => $e->getMessage(),
                'response' => optional($e->response)->body(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Unexpected BulkSMS exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function get_credits()
    {
        try {
            $response = $this->client()->get('profile');

            if ($response->failed()) {
                Log::error('Error fetching credits', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return data_get($response->json(), 'credits.balance');
        } catch (\Throwable $e) {
            Log::error('Exception fetching credits: ' . $e->getMessage());
            return null;
        }
    }
}
