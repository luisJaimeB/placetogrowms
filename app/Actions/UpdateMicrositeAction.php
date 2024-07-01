<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\Microsite;
use Illuminate\Support\Facades\Storage;

class UpdateMicrositeAction implements Executable
{
    protected Microsite $microsite;

    public function __construct(private array $data, Microsite $microsite)
    {
        $this->data = $data;
        $this->microsite = $microsite;
    }

    public function execute(): Microsite
    {
        $this->microsite->name = $this->data['name'];
        $this->microsite->category_id = $this->data['category'];
        $this->microsite->expiration = $this->data['expiration'];
        $this->microsite->type_site_id = $this->data['siteType'];
        
        if ($this->data['logo'] =! $this->microsite->logo) {
            if ($this->microsite->logo) {
                Storage::delete('public/microsite/logo/' . $this->microsite->logo);
            }

            $disk = "public_upload";
            $filename = time() . "." . $this->data['logo']->extension();
            
            $this->data['logo']->storeAs("microsite/logo", $filename, $disk);
            
            $this->microsite->logo = $filename;
        } else {

        }

        $this->microsite->save();

        return $this->microsite;
    }
}