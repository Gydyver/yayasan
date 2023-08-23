<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonController extends Controller
{
    public function executePythonScript()
    {
        $pythonScript = app_path() . '/pythonScript/test.py';
        $process = new Process(['python', $pythonScript]);
        // dd($process);
        try {
            $process->mustRun();
            $output = $process->getOutput();
            // Process the output as needed
            return response()->json(['output' => $output]);
        } catch (ProcessFailedException $exception) {
            $errorMessage = $exception->getMessage();
            // Handle the error as needed
            return response()->json(['error' => $errorMessage], 500);
        }
    }
}
