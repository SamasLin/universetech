<?php
namespace {
    class CurlUtil
    {
        const CURL_METHODS = ['GET', 'POST', 'PUT', 'DELETE'];
        const CURL_DEFAULT_HEADERS = [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'application/json; charset=utf-8',
            'User-Agent' => 'xxxx/0.1'
        ];

        public static function curlApi(
            string $method,
            string $uri,
            array $params = [],
            array $headers = [],
            array $options = []
        ) {
            $method = strtoupper($method);

            $mergedHeader = array_merge(self::CURL_DEFAULT_HEADERS, $headers);
            $outputHeader = [];
            foreach ($mergedHeader as $key => $content) {
                $outputHeader[] = "{$key}: {$content}";
            }

            $defaultOptions = [
                CURLOPT_CUSTOMREQUEST => in_array($method, self::CURL_METHODS) ? $method : 'OPTIONS',
                CURLOPT_HTTPHEADER => $outputHeader,
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ];
            $finalOptions = $defaultOptions;
            foreach ($options as $optionKey => $option) {
                $finalOptions[$optionKey] = $option;
            }

            $curlHandler = curl_init($uri);
            curl_setopt_array($curlHandler, $finalOptions);
            $response = curl_exec($curlHandler);
            $info = curl_getinfo($curlHandler);
            if (curl_error($curlHandler)) {
                throw new FetchFailureException(sprintf('[%d]%s', curl_errno($curlHandler), curl_error($curlHandler)));
            }
            if ((int)$info['http_code'] !== 200) {
                throw new FetchFailureException(json_encode($response));
            }

            curl_close($curlHandler);
            $result = $response;
            $json = json_decode($response, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                $result = $json;
            }

            return $result;
        }
    }
}
