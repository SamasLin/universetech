<?php
namespace {
    class Source2 extends BaseSource
    {
        protected $uri = 'http://one.fake/v1';
        protected $method = 'GET';
        protected $params = ['code' => 'code'];

        protected function parseResult($response, string $code, string $issue): string
        {
            if (!is_array($response)) {
                throw new FetchFailureException(__CLASS__ . ": Response datatype error.");
            }

            if (!isset($response['code']) || $response['code'] != $code) {
                throw new FetchFailureException(__CLASS__ . ": Response code '{$response['code']}' != '{$code}'.");
            }

            if (!isset($response['data'])) {
                throw new FetchFailureException(__CLASS__ . ": Response 'data' missing.");
            } elseif (!is_array($response['data'])) {
                throw new FetchFailureException(__CLASS__ . ": Response 'data' datatype error.");
            }

            foreach ($response['data'] as $data) {
                if (isset($data['expect']) &&
                    $data['expect'] == $issue &&
                    isset($data['opencode']) &&
                    is_string($data['opencode'])
                ) {
                    return $data['opencode'];
                }
            }

            return '';
        }
    }
}
