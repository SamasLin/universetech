<?php
namespace {
    class xxxx
    {
        use CodeMapTrait;

        protected $lottery;

        public __construct(Lottery $lottery)
        {
            $this->lottery = $lottery;
        }

        public function getWinningNumber(): string
        {
            $gameId = $this->lottery->gameId;

            if (!isset($this->codeMap[$gameId])) {
                throw new FetchFailureException("No config of gameId {$gameId}.");
            }

            if (isset($this->codeMap[$gameId]['master'])) {
                if (isset($this->codeMap[$gameId]['source'][$this->codeMap[$gameId]['master']])) {
                    $winningNumber = $this->getSourceResult(
                        $this->codeMap[$gameId]['master'],
                        $this->codeMap[$gameId]['source'][$this->codeMap[$gameId]['master']]
                    );
                    if (!empty($winningNumber)) {
                        return $winningNumber;
                    }
                }
            }

            unset($this->codeMap[$gameId]['source'][$this->codeMap[$gameId]['master']]);
            foreach ($this->codeMap[$gameId]['source'] as $source => $code) {
                $winningNumber = $this->getSourceResult($source, $code);
                if (!empty($winningNumber)) {
                    return $winningNumber;
                }
            }

            throw new FetchFailureException('No result.');
        }

        private function getSourceResult(int $source, string $code): string
        {
            $className = "Source{$source}";
            if (!class_exists($className)) {
                throw new FetchFailureException("Source {$source} class is missing.");
            }

            $sourceObj = new $className;
            return $sourceObj->getResult($code, $this->lottery->issue) ?: '';
        }
    }
}
