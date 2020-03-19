<?php
namespace {
    trait CodeMapTrait
    {
        /**
         * [
         *     int $gameId => [
         *         'master' => int $source,
         *         'source' => [
         *             int $source => string $code,
         *             ...
         *         ]
         *     ],
         *     ...
         * ]
         */
        protected $codeMap = [
            1 => [
                'master' => 1,
                'source' => [
                    1 => 'ssc',
                    2 => 'bjsyxw'
                ]
            ],
            2 => [
                'master' => 2,
                'source' => [
                    1 => 'cqssc',
                    2 => 'bj11x5'
                ]
            ]
        ];
    }
}
