<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UpdateMicrositeAction implements Executable
{

    const DISK = 'public_upload';

    /**
     * @var array $data
     * @var Model|Microsite $microsite
     */
    public static function execute(array $data, Model|null $microsite = null): Model
    {
        //dd($data);
        $microsite->name = $data['name'];
        $microsite->category_id = $data['category'];
        $microsite->expiration = $data['expiration'];
        $microsite->type_site_id = $data['siteType'];

        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            if ($microsite->logo) {
                Storage::delete('public/microsite/logo/' . $microsite->logo);
            }

            $disk = self::DISK;
            $filename = time() . "." . $data['logo']->extension();

            $data['logo']->storeAs("microsite/logo", $filename, $disk);

            $microsite->logo = $filename;
        }

        $microsite->save();

        $microsite->currencies()->sync($data['currency']);

        return $microsite;
    }
}