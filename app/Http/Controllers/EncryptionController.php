<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class EncryptionController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    public function index(Request $request)
    {
        return view('auth.beda');
    }

    public function encryptID(Request $request)
    {
        $id = $request->input('id');

        // Panggil fungsi Python untuk melakukan enkripsi
        $encryptedID = $this->encryptWithPython($id);

        return response()->json(['encrypted_id' => $encryptedID]);
    }

    private function encryptWithPython($id)
    {
        // Menjalankan skrip python dengan argumen ID sebagai input
        $process = new Process(["python", "path/to/encrypt_script.py", $id]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return trim($process->getOutput());
    }
}
