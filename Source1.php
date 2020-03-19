<?php
namespace {
    class Source1 extends BaseSource
    {
        protected $uri = 'http://one.fake/v1';
        protected $method = 'GET';
        protected $params = ['gamekey' => 'code', 'issue' => 'issue'];

        protected function parseResult($response, string $code, string $issue): string
        {
            if (!is_array($response)) {
                throw new FetchFailureException(__CLASS__ . ": Response datatype error.");
            }

            if (!isset($response['errorCode']) || (int)$response['errorCode'] !== 0) {
                $errorCode = $response['errorCode'] ?? 'missing';
                throw new FetchFailureException(__CLASS__ . ": Response 'errorCode' {$errorCode}.");
            }

            if (!isset($response['result'])) {
                throw new FetchFailureException(__CLASS__ . ": Response 'result' missing.");
            } elseif (!is_array($response['result'])) {
                throw new FetchFailureException(__CLASS__ . ": Response 'result' datatype error.");
            }

            if (!isset($response['result']['data'])) {
                throw new FetchFailureException(__CLASS__ . ": Response 'result.data' missing.");
            } elseif (!is_array($response['result']['data'])) {
                throw new FetchFailureException(__CLASS__ . ": Response 'result.data' datatype error.");
            }

            foreach ($response['result']['data'] as $data) {
                if (isset($data['gid']) &&
                    $data['gid'] == $issue &&
                    isset($data['award']) &&
                    is_string($data['award'])
                ) {
                    return $data['award'];
                }
            }

            return '';
        }
    }
}
