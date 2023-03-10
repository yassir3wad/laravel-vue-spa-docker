<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Uploader\FileUploader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
	private FileUploader $uploader;

	public function __construct(FileUploader $uploader)
	{
		$this->uploader = $uploader;
	}

	public function store(Request $request)
	{
		$path = $this->uploader->upload($request->file('file'), $request->type);

		return response([
			'url' => Storage::url($path),
			'path' => $path
		], 201);
	}
}