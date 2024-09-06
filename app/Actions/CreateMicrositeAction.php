<?php

namespace App\Actions;

use App\Constants\AclActions;
use App\Contracts\Executable;
use App\Models\Acl;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Throwable;

class CreateMicrositeAction implements Executable
{
    /**
     * @throws Throwable
     */
    public static function execute(array $data, ?Model $microsite = null): Model|false
    {
        try {
            $microsite = new Microsite();
            $microsite->name = $data['name'];
            $microsite->category_id = $data['category_id'];
            $microsite->expiration = $data['expiration'];
            $microsite->type_site_id = $data['type_site_id'];
            $microsite->user_id = Auth::id();

            if (isset($data['logo']) && $data['logo']) {
                $disk = 'public_upload';
                $filename = time().'.'.$data['logo']->extension();

                $data['logo']->storeAs('microsite/logo', $filename, $disk);
                $microsite->logo = $filename;
            }

            if (isset($data['optional_fields'])) {
                $microsite->optional_fields = $data['optional_fields'];
            }
            $microsite->save();

            if (isset($data['currency'])) {
                $microsite->currencies()->sync($data['currency']);
            }

            $acl = new Acl();
            $acl->user_id = $microsite->user_id;
            $acl->status = AclActions::allowed;
            $acl->model()->associate($microsite);
            $acl->save();

            return $microsite;
        } catch (Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
