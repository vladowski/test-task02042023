<?php

namespace app\File;

use RuntimeException;
use SplFileObject;

class InputFileParser
{
    public function getRows(string $filename): iterable
    {
        if (! file_exists($filename)) {
            throw new RuntimeException(sprintf('File %s does not exists', $filename));
        }

        foreach (new SplFileObject($filename) as $lineNumber => $line) {
            $result = json_decode($line);

            if (! $result) {
                throw new RuntimeException(sprintf('Error while reading line #%b', $lineNumber));
            }

            yield new FileRow($result->bin, $result->amount, $result->currency);
        }
    }
}