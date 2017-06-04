<?php 

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UploadController extends BaseController
{
	public function uploadImage(Request $request, Response $response)
	{
		$upload = new \App\Extensions\Uploads\UploadHandler('uploads/images');

	    $rules = [
	        'extension' => [
	            'allowed'   => ['jpg', 'jpeg', 'png'],
	            'message'   => '{label} must be a valid image (jpg, jpeg, png)',
	        ],
	        'size'      => [
	            'max'       => '1M',
	            'message'   => '{label} must be less than {max}',
	        ],
	    ];

	    $upload->setRules($rules, 'Picture');
	    $upload->setName(uniqid());

	    $submit = $upload->upload($_FILES['image']);

	    if (is_array($submit)) {
	        return $this->responseDetail('errors', 400, $submit);
	    }

	    return $this->responseDetail('success', 200, $submit);
	}
}