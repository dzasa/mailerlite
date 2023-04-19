<?php

namespace App\Http\Middleware;

use App\Repositories\MailerLiteRepository;
use Closure;
use Illuminate\Http\Request;

class EnsureMailLiteApiKeyExist
{
    /**
     * @var MailerLiteRepository
     */
    private $mailerLiteRepository;

    public function __construct(MailerLiteRepository $mailerLiteRepository)
    {
        $this->mailerLiteRepository = $mailerLiteRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $this->mailerLiteRepository->getApiKey();

        if (!$apiKey) {
            return redirect('/api-key');
        }

        return $next($request);
    }
}
