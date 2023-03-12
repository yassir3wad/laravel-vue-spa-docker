<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\FileUploaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
	private FileUploaderService $uploader;

	public function __construct(FileUploaderService $uploader)
	{
		$this->uploader = $uploader;
	}

	public function store(Request $request)
	{
		$path = $this->uploader->validateAndUpload($request->file('file'), $request->type);

		return response([
			'url' => Storage::url($path),
			'path' => $path
		], 201);
	}
}