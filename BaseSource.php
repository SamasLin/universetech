<?php
namespace {
    abstract class BaseSource
    {
        protected $uri = '';
        protected $method = '';
        protected $params = [];

        abstract protected function parseResult($response, string $code, string $issue): string;

        protected function getResult(string $code, string $issue): string
        {
            $params = $this->params;
            foreach ($params as $key => &$value) {
                if ($value === 'code') {
                    $value = $code;
                } elseif (isset($lottery->$value)) {
                    $value = $lottery->$value};
                }
            }
            $response = CurlUtil::curlApi($this->method, $this->uri, $params);
            return $this->parseResult($resonse, $code, $issue);
        }
    }
}
