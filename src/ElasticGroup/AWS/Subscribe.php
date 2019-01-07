<?php
/**
 * Created by PhpStorm.
 * User: trietl
 * Date: 1/3/19
 * Time: 10:55 AM
 */

namespace SpotInst\ElasticGroup\AWS;


use SpotInst\ElasticGroup\BaseApi;

class Subscribe extends BaseApi
{
    public function subscribe(string $elasticGroupId, string $topic, array $eventType, array $format, string $protocol = 'aws-sns') {
        $uri = 'events/subscription?accountId={ACCOUNT_ID}';
        $response = [];
        foreach ($eventType as $event) {
            $data = [
                'subscription' => [
                        'resourceId' => $elasticGroupId,
                        'protocol' => $protocol,
                        'endpoint' => $topic,
                        'eventType' => $event,
                        'eventFormat' => $format
                    ]
            ];
            $response[] = $this->client->post($uri, $data);
        }
        return $response;

    }
}