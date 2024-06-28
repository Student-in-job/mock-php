<?php

interface Storage
{
    public function addLine($line, $newLine = false): bool;
    public function flush();
}
class FileStorage implements Storage
{
    private string $filename;
    private $stream;
    private $opened;
    public function __construct($file)
    {
        $this->filename = $file;
        try
        {
            $this->stream = fopen($file, 'w');
            $this->opened = true;
        }
        catch (Exception $exp)
        {
            print($exp->getMessage());
        }
    }

    public function addLine($line, $newLine = false): bool
    {
        if (!$this->opened)
            return false;
        fwrite($this->stream, $line);
        if ($newLine)
            fwrite($this->stream, PHP_EOL);
        return true;
    }

    public function flush()
    {
        fclose($this->stream);
        $this->opened = false;
    }
}