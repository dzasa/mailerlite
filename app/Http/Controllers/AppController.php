<?php

namespace App\Http\Controllers;

use App\Repositories\MailerLiteRepository;
use App\Services\MailerLite\MailerLiteInterface;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function initApiKey()
    {
        return view('api-key');
    }

    public function saveApiKey(
        Request $request,
        MailerLiteRepository $mailerLiteRepository,
        MailerLiteInterface $mailerLite
    ) {
        $apiKey = $request->input('api_key');
        $mailerLite->setApiKey($apiKey);

        try {
            $mailerLite->getMe();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ])->setStatusCode(400);
        }

        $mailerLiteRepository->saveApiAKey(['api_key' => $apiKey]);

        return response()->json([
            'result' => 'ok',
        ]);
    }
}
