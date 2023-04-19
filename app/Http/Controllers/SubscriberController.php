<?php

namespace App\Http\Controllers;

use App\Services\MailerLite\Exceptions\ApiException;
use App\Services\MailerLite\Exceptions\ResourceNotFoundException;
use App\Services\MailerLite\MailerLiteInterface;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    private MailerLiteInterface $mailerLite;

    public function __construct(MailerLiteInterface $mailerLite)
    {
        $this->mailerLite = $mailerLite;
    }

    public function index()
    {
        return view('subscribers');
    }

    public function all(Request $request)
    {
        try {
            $query = $request->get('search')['value'];
            $limit = $request->get('length');
            $cursor = $request->get('cursor');

            if (!$query) {
                $subscribers = $this->mailerLite->getSubscribers($limit, $cursor);
            } else {
                try {
                    $subscriber = $this->mailerLite->getSubscriber($query)['data'];

                    $subscribers = [
                        'data' => [$subscriber],
                        'links' => [],
                        'meta' => [],
                    ];
                } catch (ResourceNotFoundException $exception) {
                    $subscribers = [];
                }
            }

            return response()->json($subscribers);
        } catch (ApiException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ])->setStatusCode(400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => sprintf('Something went wrong: %s', $e->getMessage()),
            ])->setStatusCode(400);
        }
    }

    public function single(string $id)
    {
        try {
            $subscriber = $this->mailerLite->getSubscriber($id);

            return response()->json($subscriber);
        } catch (ApiException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ])->setStatusCode(400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Something went wrong',
            ])->setStatusCode(400);
        }
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'country' => 'required',
        ]);

        $email = $request->post('email');
        $data = [
            'name' => $request->post('name'),
            'country' => $request->post('country'),
        ];

        try {
            $subscriber = $this->mailerLite->createOrUpdateSubscriber($email, $data);

            return response()->json($subscriber);
        } catch (ApiException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ])->setStatusCode(400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Something went wrong',
            ])->setStatusCode(400);
        }
    }

    public function delete(string $id)
    {
        try {
            $this->mailerLite->deleteSubscriber($id);

            return response()->json([
                'result' => 'ok',
            ]);
        } catch (ApiException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ])->setStatusCode(400);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Something went wrong',
            ])->setStatusCode(400);
        }
    }
}
