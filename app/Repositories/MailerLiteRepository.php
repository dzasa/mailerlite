<?php

namespace App\Repositories;

use App\Models\MailerLite;

class MailerLiteRepository
{
    public function getApiKey()
    {
        return MailerLite::whereNotNull('api_key')->where('api_key', '<>', '')->first();
    }

    public function saveApiAKey(array $data)
    {
        return MailerLite::updateOrCreate($data);
    }
}
