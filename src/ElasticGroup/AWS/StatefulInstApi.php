<?php
namespace SpotInst\ElasticGroup\AWS;

use SpotInst\ElasticGroup\BaseApi;
use SpotInst\SpotInstClientInterface;

class StatefulInstApi extends BaseApi
{
    /**
     * @var SpotInstClientInterface
     */

    /**
     * Pause Staful Instance
     * @param $groupId
     * @param $statefulInstId
     * @return mixed
     */
    public function pauseStatefulInst($groupId, $statefulInstId) {
        $uri = 'aws/ec2/group/{GROUP_ID}/statefulInstance/{STATEFUL_INSTANCE_ID}/pause?accountId={ACCOUNT_ID}';
        $uri = str_replace(['{GROUP_ID}', '{STATEFUL_INSTANCE_ID}'], [$groupId, $statefulInstId], $uri);
        return  $this->client->put($uri, []);
    }

    /**
     * Resume Stateful Instance
     * @param $groupId
     * @param $statefulInstId
     * @return mixed
     */
    public function resumeStatefulInst($groupId, $statefulInstId) {
        $uri = 'aws/ec2/group/{GROUP_ID}/statefulInstance/{STATEFUL_INSTANCE_ID}/resume?accountId={ACCOUNT_ID}';
        $uri = str_replace(['{GROUP_ID}', '{STATEFUL_INSTANCE_ID}'], [$groupId, $statefulInstId], $uri);
        return  $this->client->put($uri, []);
    }

    /**
     * Get a list of stateful instance
     * @param $groupId
     * @return mixed
     */
    public function listStatefulInst($groupId) {
        $uri = 'aws/ec2/group/{GROUP_ID}/statefulInstance?accountId={ACCOUNT_ID}';
        $uri = str_replace(['{GROUP_ID}'], [$groupId], $uri);
        return  $this->client->get($uri, []);
    }

    /**
     * Get a list of stateful instance
     * @param $groupId
     * @return mixed
     */
    public function terminateStatefulInst($groupId, $statefulInstId) {
        $uri = 'aws/ec2/group/{GROUP_ID}/statefulInstance/{STATEFUL_INSTANCE_ID}/deallocate?accountId={ACCOUNT_ID}';
        $uri = str_replace(['{GROUP_ID}','{STATEFUL_INSTANCE_ID}'], [$groupId, $statefulInstId], $uri);
        return  $this->client->put($uri, []);
    }


}