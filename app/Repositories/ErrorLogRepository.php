<?php

namespace App\Repositories;

use App\ErrorLog;

class ErrorLogRepository
{
    /**
     * It stores the exceptions in ExceptionLog
     * @param \Exception $exception
     */
    public function store(\Exception $exception)
    {
        $errorMessage = $exception->getMessage();
        $errorFileName = $exception->getFile();
        $lineNumber = $exception->getLine();

        ErrorLog::create([
            'error_message' => $errorMessage,
            'line_number' => $lineNumber,
            'file_name' => $errorFileName
        ]);
    }

}
