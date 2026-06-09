<div>
    <div class="container-fluid ">
        @if (session()->has('create_success'))
            <div class="row">
                <div class="col-12">
                    @if (session()->has('create_success'))
                        <div class="alert alert-success alert-dismissible fade show py-1 mb-2" role="alert" style="font-size: 0.875rem;">
                            {{ session('create_success') }}
                            <button type="button" class="btn-close btn-sm p-1" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                </div>
            </div>
        @endif
        <!-- Brand Form -->
        <div class="sap-card shadow-sm">
            <form wire:submit.prevent="save()">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="sap-header">Create / Update Brand</div>
                <div>
                    <button type="reset" class="btn btn-light border me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-sap">
                        <i class="bi bi-save2 me-1"></i> Save Brand
                    </button>
                </div>
            </div>
                <div class="row g-4">
                    <!-- Product Info -->
                    <div class="col-md-6">
                        <label class="form-label">Brand Code</label>
                        <input type="text" class="form-control" placeholder="Enter Brand Code" wire:model="code" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Brand Name</label>
                        <input type="text" class="form-control" placeholder="Enter Brand Name" required wire:model="name">
                    </div>
                </div>

            </form>
        </div>

        <!-- Brand List -->
        <div class="sap-card shadow-sm">
            <div class="sap-header">Brand List</div>
            <table id="brandTable" class="table table-sm table-bordered text-nowrap">
                <thead class="table-primary small">
                <tr>
                    <th class="small">Brand Code</th>
                    <th class="small">Brand Name</th>
                    <th class="small">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $brand)
                <tr class="small">
                    <td>{{$brand->brand_code}}</td>
                    <td>{{$brand->brand_name}}</td>
                    <td>
                        <button class="btn btn-warning btn-xs btn-sm py-0 px-2">Update</button>
                        <button class="btn btn-danger btn-xs btn-sm py-0 px-2">Delete</button>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
