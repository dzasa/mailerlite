<?php

namespace App\Services\MailerLite;

interface MailerLiteInterface
{
    public const BASE_URL = 'https://connect.mailerlite.com';
    public const BASE_URL_V2 = 'https://api.mailerlite.com';
    public const SUBSCRIBERS_API_PATH = 'api/subscribers';
    public const ME_API_PATH = 'api/v2/me';

    public function getSubscribers(int $limit = 25, string $cursor = null): array;

    public function getSubscriber(string $identifier): array;

    public function createOrUpdateSubscriber(string $email, array $data): array;

    public function deleteSubscriber(string $id):bool;

    public function getMe(): array;
}
