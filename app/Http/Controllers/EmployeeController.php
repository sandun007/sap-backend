<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{
    private function formatEmployee($employee)
    {
        if ($employee->nic_front && str_starts_with($employee->nic_front, 'assets/')) {
            $employee->nic_front_url = asset($employee->nic_front);
        } else {
            $employee->nic_front_url = $employee->nic_front
                ? Storage::disk('public')->url($employee->nic_front)
                : null;
        }

        if ($employee->nic_back && str_starts_with($employee->nic_back, 'assets/')) {
            $employee->nic_back_url = asset($employee->nic_back);
        } else {
            $employee->nic_back_url = $employee->nic_back
                ? Storage::disk('public')->url($employee->nic_back)
                : null;
        }

        return $employee;
    }
    public function index()
{
    try {
        $user = auth()->user();
        if($user && $user->role === 'admin') {

            $employees = Employee::with('department')->get();

            $employees->transform(fn($emp) => $this->formatEmployee($emp));

            return response()->json([
                'success' => true,
                'message' => 'Employees retrieved successfully',
                'data' => $employees
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve employees',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function store(Request $request)
    {
        try {
            if(auth()->user()->role === 'admin') {
                $data = $request->validate([
                    'employee_code'=>'nullable|string',
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'phone_num1' => 'nullable|string|max:20',
                    'phone_num2' => 'nullable|string|max:20',
                    'nic_num' => 'nullable|string|max:20',
                    'nic_front' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'nic_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'hire_date' => 'nullable|date',
                    'position' => 'nullable|string|max:255',
                    'salary' => 'nullable|numeric',
                    'address' => 'nullable|string',
                    'date_of_birth' => 'nullable|date',
                    'department_id' => 'nullable|exists:departments,department_id',
                    'is_active' => 'nullable|in:0,1',
                    'password' => 'nullable|string|min:6',
                ]);


                if ($request->hasFile('nic_front')) {
                    $file = $request->file('nic_front');
                    $filename = 'EF-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/emp/nic/front'), $filename);
                    $data['nic_front'] = 'assets/img/emp/nic/front/' . $filename;
                }

                // NIC Back
                if ($request->hasFile('nic_back')) {
                    $file = $request->file('nic_back');
                    $filename = 'EB-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/emp/nic/back'), $filename);
                    $data['nic_back'] = 'assets/img/emp/nic/back/' . $filename;
                }


                $user = User::create([
                    'name'  => $data['first_name'] . ' ' . $data['last_name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => 'employee',
                ]);

                unset($data['password']);

                $employee = Employee::create($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Employee created successfully',
                    'user_id' => $user->id,
                    'data' => $employee
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::with('department')->find($id);

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
                    'data' => null
                ], 404);
            }

            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 403);
            }

            $data = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone_num1' => 'nullable|string|max:20',
                'phone_num2' => 'nullable|string|max:20',
                'nic_num' => 'nullable|string|max:20',
                'nic_front' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'nic_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'hire_date' => 'nullable|date',
                'position' => 'nullable|string|max:255',
                'salary' => 'nullable|numeric',
                'address' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
                'department_id' => 'nullable|exists:departments,department_id',
                'is_active' => 'nullable|in:0,1',
            ]);

            if ($request->hasFile('nic_front')) {
                if ($employee->nic_front && file_exists(public_path($employee->nic_front))) {
                    @unlink(public_path($employee->nic_front));
                }

                $file = $request->file('nic_front');
                $filename = 'EF-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/img/emp/nic/front'), $filename);
                $data['nic_front'] = 'assets/img/emp/nic/front/' . $filename;
            } else {
                unset($data['nic_front']);
            }

            if ($request->hasFile('nic_back')) {
                if ($employee->nic_back && file_exists(public_path($employee->nic_back))) {
                    @unlink(public_path($employee->nic_back));
                }

                $file = $request->file('nic_back');
                $filename = 'EB-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/img/emp/nic/back'), $filename);
                $data['nic_back'] = 'assets/img/emp/nic/back/' . $filename;
            } else {
                unset($data['nic_back']);
            }

            $employee->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'data' => $this->formatEmployee($employee)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
                    'data' => null
                ], 404);
            }

            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 403);
            }

            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function import(Request $request)
    {
        $employees = $request->input('employees', []);

        $imported = 0;
        $skipped = 0;

        foreach ($employees as $data) {

            $exists = Employee::where('employee_code', $data['employee_code'])
                ->orWhere('employee_name', $data['employee_name'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Brand::create($data);
            $imported++;
        }

        return response()->json([
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => "Imported $imported employee, skipped $skipped duplicates"
        ]);
    }

}
