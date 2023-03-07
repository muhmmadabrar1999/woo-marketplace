<?php

namespace Woo\Location\Http\Requests;

use Woo\Support\Http\Requests\Request;

class BulkImportRequest extends Request
{
    public function rules(): array
    {
        $mimeType = implode(',', config('plugins.location.general.bulk-import.mime_types', []));

        return [
            'file' => 'required|file|mimetypes:' . $mimeType,
            'type' => 'required|in:all,countries,states,cities',
        ];
    }
}
