<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\InvPara;
use Livewire\Component;

class CategoryCreate extends Component
{
    public $code, $name;

    public function render()
    {
        $categories = Category::all();
        return view('livewire.category-create',compact('categories'));
    }

    public function save()
    {
        try {
            $inv_data = InvPara::query()->first();
            Category::query()->create([
                'code' => $this->code,
                'name' => $this->name,
                'company_id' => $inv_data->id,
            ]);
            $this->form_clear();
            session()->flash('create_success', 'Category Create successfully ✅');
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
