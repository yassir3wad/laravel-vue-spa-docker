<?php

namespace App\Actions\Uploader;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class FileUploader
{
	public function upload(?UploadedFile $file, $type): string
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


		return $file->store($config['folder'] ?? $type);
	}
}