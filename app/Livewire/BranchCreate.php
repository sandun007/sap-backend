<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\InvPara;
use Livewire\Component;

class BranchCreate extends Component
{
    public $code,$name,$flag,$address,$location,$email,$phone_no_01,$phone_no_02,$website;

    public function render()
    {
        return view('livewire.branch-create');
    }

    public function save()
    {
        try {
            $inv_date = InvPara::query()->first();
            Branch::query()->create([
                'code'=>$this->code,
                'name'=>$this->name,
                'flag'=>$this->flag,
                'address'=>$this->address,
                'location'=>$this->location,
                'email'=>$this->email,
                'phone_no_01'=>$this->phone_no_01,
                'phone_no_02'=>$this->phone_no_02,
                'website'=>$this->website,
                'company_id'=>$inv_date->id,
                'status'=>'active'
            ]);
            $this->form_clear();
            session()->flash('create_success', 'Branch Create successfully ✅');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function form_clear()
    {
        $this->code='';
        $this->name='';
        $this->flag='';
        $this->address='';
        $this->location='';
        $this->email='';
        $this->phone_no_01='';
        $this->phone_no_02='';
        $this->website='';
    }
}
