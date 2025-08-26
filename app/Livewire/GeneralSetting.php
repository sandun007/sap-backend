<?php

namespace App\Livewire;

use App\Http\Controllers\ImageUploader;
use App\Models\InvPara;
use Livewire\Component;
use Livewire\WithFileUploads;

class GeneralSetting extends Component
{
    use WithFileUploads;

    public $name, $flag, $email, $phone_no_01, $phone_no_02, $website, $address, $logo;
    public $inv_data;

    public function mount()
    {
        $this->inv_data = InvPara::first(); // assume only one record exists

        if ($this->inv_data) {
            $this->name        = $this->inv_data->company_name;
            $this->flag        = $this->inv_data->company_flag;
            $this->email       = $this->inv_data->email;
            $this->phone_no_01 = $this->inv_data->phone_no_01;
            $this->phone_no_02 = $this->inv_data->phone_no_02;
            $this->website     = $this->inv_data->website;
            $this->address     = $this->inv_data->company_address;
        }
    }

    public function save()
    {

        $this->validate([
            'name'        => 'required|string|max:255',
            'flag'        => 'nullable|string|max:10',
            'email'       => 'nullable|email|max:255',
            'phone_no_01' => 'nullable|string|max:20',
            'phone_no_02' => 'nullable|string|max:20',
            'website'     => 'nullable|string|max:255',
            'address'     => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        // update existing record
        $this->inv_data->update([
            'company_name'    => $this->name,
            'company_flag'    => $this->flag,
            'email'           => $this->email,
            'phone_no_01'     => $this->phone_no_01,
            'phone_no_02'     => $this->phone_no_02,
            'website'         => $this->website,
            'company_address' => $this->address,
        ]);
        // update logo if uploaded
        if ($this->logo) {
            $path = ImageUploader::uploadImage($this->logo, 'company/logo');
            $this->inv_data->update(['logo' => $path]);
        }

        session()->flash('success', 'Company settings updated successfully ✅');
    }

    public function resetForm()
    {
        $this->mount(); // reload DB values
    }

    public function render()
    {
        return view('livewire.general-setting');
    }
}
