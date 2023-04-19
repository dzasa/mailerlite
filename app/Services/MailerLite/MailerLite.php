<?php

namespace App\Services\MailerLite;

use App\Repositories\MailerLiteRepository;
use App\Services\MailerLite\Exceptions\ApiException;
use App\Services\MailerLite\Exceptions\ApiKeyNotFoundException;
use App\Services\MailerLite\Exceptions\ResourceNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MailerLite implements MailerLiteInterface
{
    private ?Client $client = null;
    private ?string $apiKey = null;

    public function __construct(MailerLiteRepository $mailerLiteRepository)
    {
        $this->mailerLiteRepository = $mailerLiteRepository;
    }

    private function getClient(): Client
    {
        if ($this->client instanceof Client) {
            return $this->client;
        }

        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getApiKey(),
                'X-MailerLite-ApiKey' => $this->getApiKey(),
            ],
        ]);

        return $this->client;
    }

    public function getSubscribers(int $limit = 25, string $cursor = null): array
    {
        try {
            $request = $this->getClient()->request('GET', sprintf('%s/%s', self::BASE_URL, self::SUBSCRIBERS_API_PATH), [
                'query' => [
                    'limit' => $limit,
                    'cursor' => $cursor,
                ],
            ]);

            $response = $request->getBody()->getContents();
            $responseData = json_decode($response, true);

            return $responseData;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($response, true);

                throw new ApiException($responseData['message']);
            }

            throw new ApiException(sprintf('Unknown API Exception %s', $e->getMessage()));
        }
    }

    public function createOrUpdateSubscriber(string $email, array $data): array
    {
        try {
            $request = $this->getClient()->request('POST', sprintf('%s/%s', self::BASE_URL, self::SUBSCRIBERS_API_PATH), [
                'json' => [
                    'email' => $email,
                    'fields' => $data,
                ],
            ]);

            $response = $request->getBody()->getContents();
            $responseData = json_decode($response, true);

            return $responseData;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($response, true);

                throw new ApiException($responseData['message']);
            }

            throw new ApiException(sprintf('Unknown API Exception %s', $e->getMessage()));
        }
    }

    public function deleteSubscriber(string $id):bool
    {
        try {
            $this->getClient()->request('DELETE', sprintf('%s/%s/%s', self::BASE_URL, self::SUBSCRIBERS_API_PATH, $id));
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($response, true);

                throw new ApiException($responseData['message']);
            }

            throw new ApiException(sprintf('Unknown API Exception %s', $e->getMessage()));
        }

        return true;
    }

    public function getSubscriber(string $identifier): array
    {
        try {
            $request = $this->getClient()->request('GET', sprintf('%s/%s/%s', self::BASE_URL, self::SUBSCRIBERS_API_PATH, urlencode($identifier)));

            $response = $request->getBody()->getContents();
            $responseData = json_decode($response, true);

            return $responseData;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($response, true);

                if ($responseData['message'] === 'Resource not found.') {
                    throw new ResourceNotFoundException('Resource not found.');
                }

                throw new ApiException($responseData['message']);
            }

            throw new ApiException(sprintf('Unknown API Exception %s', $e->getMessage()));
        }
    }

    public function getMe(): array
    {
        try {
            $request = $this->getClient()->request('GET', sprintf('%s/%s', self::BASE_URL_V2, self::ME_API_PATH));

            $response = $request->getBody()->getContents();
            $responseData = json_decode($response, true);

            return $responseData;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($response, true);

                throw new ApiException($responseData['error']['message']);
            }

            throw new ApiException(sprintf('Unknown API Exception %s', $e->getMessage()));
        }
    }

    public function getApiKey(): string
    {
        if ($this->apiKey === null) {
            /** @var \App\Models\MailerLite $apiKey */
            $apiKey = $this->mailerLiteRepository->getApiKey();
            if (!$apiKey instanceof \App\Models\MailerLite) {
                throw new ApiKeyNotFoundException('MailerLite ApiKey not found.');
            }

            $this->apiKey = $apiKey->api_key;
        }

        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
}
