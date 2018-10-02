<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusUpdated;

class ApiController extends Controller
{
    /**
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data)
    {
        return response()->json($data, 200);
    }

    /**
     * @param $data
     * @param int $errorCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($data, $errorCode = 500)
    {
        return response()->json($data, $errorCode);
    }

    // Send mail
    public function sendMail($to, $cc, $data, $type) {

        if ($cc == null || $cc == '') {
            Mail::to($to)->queue(new StatusUpdated($data, $type));
        } else {
            Mail::to($to)
                ->cc($cc)
                ->queue(new StatusUpdated($data, $type));
        }

    }
}
