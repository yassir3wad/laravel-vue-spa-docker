<?php

namespace App\Services;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class FileUploaderService
{
	public function validateAndUpload(?UploadedFile $file, $type): string
	{
		$config = config("upload.types.$type");

		\Validator::make(
			['file' => $file, 'type' => $type],
			[
				'file' => ['required', 'file', Rule::when($config, [File::types($config['mimetypes'])->max($config['max_size'])])],
				'type' => ['required', Rule::in(array_keys(config('upload.types')))]
			]
		)
			->validate();

		return $this->uploadFile($file, $config['folder'] ?? $type);
	}

	public function uploadFile(?UploadedFile $file, string $folder): string
	{
		return $file->store($folder);
	}

	public function deleteFile(string $filePath): string
	{
		return Storage::delete($filePath);
	}
}