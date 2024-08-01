<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SwalController extends BaseController
{   
    protected $helpers = ['url', 'form', 'CIMail','CIFunctions '];
    public function showAlert($type, $message)
{
    $encodedMessage = urlencode($message);
    return view('backend/pages/swal-alert', [
        'alertType' => $type,
        'alertMessage' => $encodedMessage
    ]);
}

}
