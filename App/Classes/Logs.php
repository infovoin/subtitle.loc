<?php

namespace App\Classes;

use Exception;

class Logs
{
    private $log_file = __DIR__.'/../logs.txt';
    public $time;
    public $file;
    public $line;
    public $message;


    public function FillAndWrite( Exception $e){
        $this->time = date("Y-m-d H:i:s");
        $this->line = $e->getLine();
        $this->file = $e->getFile();
        $this->message = $e->getMessage();
        $this->write();
    }
    
    public function write()
    {
        $data = [
            'time' => $this->time,
            'file' => $this->file,
            'line' => $this->line,
            'message' => $this->message];
        $data = implode(' ===> ', $data);
        $data .= "\r\n";
        file_put_contents($this->log_file, $data, FILE_APPEND);
    }

    public function read()
    {
        return file_get_contents($this->log_file);
    }
    
    
}