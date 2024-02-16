<?php

namespace App;

use App\Core\Request;
use App\Core\Response;
use App\Models\Subscribers;

/**
 * Handle methods described in Routes
 */
class Controller
{

    /**
     * List subscribers with pagination
     *
     * @param  Request $request
     * @return Response
     */
    public function handleListSubscribers(Request $request)
    {
        $page = (int)$request->query('page') ?: 1;
        $subscribers = Subscribers::paginate($page);

        return Response::getInstance()->send($subscribers);
    }
    
    /**
     * Get Subscriber by email
     *
     * @param  Request $request
     * @return Response
     */
    public function handleGetSubscriber(Request $request)
    {
        $subscriber = Subscribers::findByEmail($request->get('email'));

        if ($subscriber) {
            return Response::getInstance()->send($subscriber);
        }

        return Response::getInstance()->send('Subscriber not found', 404);
    }
    
    /**
     * Handle POST request for inserting subscribers.
     *
     * This will first look at Cache and try to find if the key exists
     * If exists, it will throw error.
     *
     * If it does not exist, it will add to Cache. Then, send success response
     * to the user and finally insert the data to MySql
     *
     * @param  Request $request
     * @throws Exception if the email already exists
     */
    public function handlePostSubscribers(Request $request)
    {
        $data = $request->only(Subscribers::FIELDS);

        try {
            Subscribers::insertCache($data);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return Response::send('Error! Email already exists.', 400);
            }
            return Response::send($e->getMessage(), 400);
        }

        Response::send([
            'status' => 'SUCC_SUBSCRIBER_INSERTED',
            'message' => 'Success! subscriber inserted successfully.'
        ]);

        // Prevents echo, print, and flush from killing the script
        ignore_user_abort(true);
        // Returns 200 to the user, and processing continues
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
            Subscribers::insertDB($data);
        }

        Subscribers::insertDB($data, false);
    }
}
