<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\InvPara;
use Livewire\Component;

class BrandCreate extends Component
{
    public $code, $name;

    public function render()
    {
        $brands = Brand::all();
        return view('livewire.brand-create',compact('brands'));
    }

    public function save()
    {
        try {
            $inv_data = InvPara::query()->first();
            Brand::query()->create([
                'brand_code' => $this->code,
                'brand_name' => $this->name,
                'company_id' => $inv_data->id,
            ]);
            $this->form_clear();
            session()->flash('create_success', 'Brand Create successfully ✅');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function form_clear()
    {
        $this->name ='';
        $this->code ='';
    }
}
