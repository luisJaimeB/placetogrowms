<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\Microsite;
use Illuminate\Support\Facades\Storage;

class CreateMicrositeAction implements Executable
{
    public function __construct(private array $data)
    {
        $this->data = $data;
    }

    public function execute(): Microsite
    {
        $microsite = new Microsite();
        $microsite->name = $this->data['name'];
        $microsite->category_id = $this->data['category'];
        $microsite->expiration = $this->data['expiration'];
        $microsite->type_site_id = $this->data['siteType'];
        
        if ($this->data['logo']) {
            $disk = "public_upload";
            $filename = time() . "." . $this->data['logo']->extension();
            
            $this->data['logo']->storeAs("microsite/logo", $filename, $disk);
            
            $microsite->logo = $filename;
        }

        $microsite->save();

        return $microsite;
    }
}