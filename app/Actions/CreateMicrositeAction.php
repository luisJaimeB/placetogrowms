<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Throwable;

class CreateMicrositeAction implements Executable
{
    public static function execute(array $data, Model|null $microsite = null): Model|false
    {
        try {
            $microsite = new Microsite();
            $microsite->name = $data['name'];
            $microsite->category_id = $data['category'];
            $microsite->expiration = $data['expiration'];
            $microsite->type_site_id = $data['siteType'];
            $microsite->user_id = Auth::id();

            if ($data['logo']) {
                $disk = "public_upload";
                $filename = time() . "." . $data['logo']->extension();

                $data['logo']->storeAs("microsite/logo", $filename, $disk);
                $microsite->logo = $filename;
            }

            $microsite->save();

            $microsite->currencies()->sync($data['currency']);

            return $microsite;
        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }
}
