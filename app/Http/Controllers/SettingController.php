<?php

namespace App\Http\Controllers;

use App\Models\InvPara;
use Illuminate\Http\Request;
use Exception;

class SettingController extends Controller
{
    public function index()
    {
        try {
            $settings = InvPara::first();
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $settings = InvPara::first();
            if (!$settings) {
                $settings = new InvPara();
            }

            // Only update fields that were actually sent in the request
            $data = $request->all();
            $updateData = [];
            
            // List of fields we allow updating via this generic method
            $fillable = (new InvPara())->getFillable();
            
            foreach ($data as $key => $value) {
                if (in_array($key, $fillable)) {
                    // Handle booleans from FormData (which sends them as strings)
                    if ($value === 'true' || $value === '1') {
                        $updateData[$key] = true;
                    } elseif ($value === 'false' || $value === '0') {
                        $updateData[$key] = false;
                    } else {
                        $updateData[$key] = $value;
                    }
                }
            }

            // Handle logo upload separately
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/logos', $filename);
                $updateData['logo'] = str_replace('public/', 'storage/', $path);
                
                // Delete old logo if it exists
                if ($settings->logo) {
                    $oldPath = str_replace('storage/', 'public/', $settings->logo);
                    \Illuminate\Support\Facades\Storage::delete($oldPath);
                }
            }

            $settings->fill($updateData);
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $settings
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function general_setting()
    {
        $inv_data = InvPara::query()->first();
        return view('settings.general_setting',compact('inv_data'));
    }
}
