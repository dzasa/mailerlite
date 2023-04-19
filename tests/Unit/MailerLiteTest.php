<?php

namespace Tests\Unit;

use App\Repositories\MailerLiteRepository;
use App\Services\MailerLite\Exceptions\ApiException;
use App\Services\MailerLite\MailerLite;
use PHPUnit\Framework\TestCase;

class MailerLiteTest extends TestCase
{
    private MailerLite $mailerLite;
    private MailerLiteRepository $mailerLiteRepository;
    private string $apiKey;

    protected function setUp(): void
    {
        $this->mailerLiteRepository = $this->createMock(MailerLiteRepository::class);
        $this->mailerLite = new MailerLite($this->mailerLiteRepository);
        $this->apiKey = env('MAILER_LITE_API_KEY');
    }

    public function testInvalidApiKey()
    {
        $apiKey = 'some_api_key';
        /* @var MailerLite $mailerLite */
        $this->mailerLite->setApiKey($apiKey);

        $this->expectException(ApiException::class);
        $this->mailerLite->getMe();
    }

    public function testValidApiKey()
    {
        $this->mailerLite->setApiKey($this->apiKey);

        $me = $this->mailerLite->getMe();
        $this->assertArrayHasKey('account', $me);
    }

    public function testGetSubscribers()
    {
        $this->mailerLite->setApiKey($this->apiKey);

        $subscribers = $this->mailerLite->getSubscribers();
        $this->assertArrayHasKey('data', $subscribers);
    }

    public function testCreateSubscriber()
    {
        $this->mailerLite->setApiKey($this->apiKey);

        $email = 'testingemail@test.com';
        $data = [
            'name' => 'Tsting Test',
            'country' => 'USA',
        ];

        $subscriber = $this->mailerLite->createOrUpdateSubscriber($email, $data);
        $this->assertArrayHasKey('data', $subscriber);
    }

    public function testCreateSubscriberWithInvalidEmail()
    {
        $this->mailerLite->setApiKey($this->apiKey);

        $email = '.testingemail@test.com';
        $data = [
            'name' => 'Tsting Test',
            'country' => 'USA',
        ];

        $this->expectException(ApiException::class);
        $this->mailerLite->createOrUpdateSubscriber($email, $data);
    }

    public function testDeleteSubscriber()
    {
        $this->mailerLite->setApiKey($this->apiKey);

        $email = 'testingemail@test.com';
        $subscriber = $this->mailerLite->getSubscriber($email);

        $this->assertTrue($this->mailerLite->deleteSubscriber($subscriber['data']['id']));
    }
}
