<?php

namespace App\Libraries;

use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Notification\Android;
use Gomoob\Pushwoosh\Model\Notification\IOS;
use Gomoob\Pushwoosh\Exception;

class Push
{

    const APPLICATION = '0240C-F21F3';
    const BEARER = 'e55GkubTetiSWcEiONN5TmhPGDxKmKDV1TloxKfirH9K8Yu6DfamBZmArFlGEepC37ec4ABbuOfAGGDi7JPK';

    public static function sendNotificationToMany($message, $body, $registration_ids)
    {
        try
        {
            $pushwoosh = self::createPush();
            $notification = self::createNotification($message, $body, $registration_ids);
            $response = self::executeRequest($pushwoosh, $notification);

            if ($response['status'] == 'success')
            {
                $responseData['status'] = 'success';
            }
            else
            {
                $responseData['status'] = 'error';
                $responseData['data'] = $response['data'];
            }

            return $responseData;
        }
        catch (Exception $e)
        {
            $responseData['status'] = 'error';
            $responseData['data'] = $e->getMessage();
            return $responseData;
        }
        catch (\Exception $e)
        {
            $responseData['status'] = 'error';
            $responseData['data'] = $e->getMessage();
            return $responseData;
        }
    }

    private static function createPush()
    {
        return Pushwoosh::create()
                        ->setApplication(self::APPLICATION)
                        ->setAuth(self::BEARER);
    }

    private static function createNotification($message, $body, $registration_ids)
    {
        $notification = Notification::create();
        $notification->setDevices($registration_ids);

        if ($message != null)
        {
            $notification->setContent($message);
        }
        else
        {
            $notification->setAndroid(
                    Android::create()->setRootParams(["silent" => "true"])
            );

            $notification->setIOS(
                    IOS::create()->setRootParams(["aps" => ["content-available" => "1"]])
            );
        }

        $notification->setData($body);

        return $notification;
    }

    private static function executeRequest($pushwoosh, $notification)
    {
        $request = CreateMessageRequest::create()
                ->addNotification($notification);

        $response = $pushwoosh->createMessage($request);

        if ($response->isOk())
        {
            return ['status' => 'success', 'data' => []];
        }
        else
        {
            return ['status' => 'error', 'data' =>
                ['code' => $response->getStatusCode(),
                    'message' => $response->getStatusMessage()]
            ];
        }
    }

}
