<div>
    <div class="container-fluid py-4">
        @if (session()->has('create_success'))
            <div class="row">
                <div class="col-12">
                    @if (session()->has('create_success'))
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            {{ session('create_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                </div>
            </div>
        @endif
        <div class="sap-card shadow-sm">

            <form wire:submit.prevent="save()">
                <div class="row g-4">
                    <!-- Company Info -->
                    <div class="col-md-4">
                        <label class="form-label">Branch Code</label>
                        <input type="text" class="form-control" placeholder="Enter Branch Code" wire:model="code" >
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Name</label>
                        <input type="text" class="form-control" placeholder="Enter Branch Name" wire:model="name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Flag</label>
                        <input type="text" class="form-control" placeholder="Enter Branch Name" wire:model="flag">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Address</label>
                        <textarea class="form-control" rows="2" wire:model="address" placeholder="Enter company address"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Location</label>
                        <input type="text" class="form-control" placeholder="Enter Location" wire:model="location" >
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch Email</label>
                        <input type="email" class="form-control" placeholder="Enter email address" wire:model="email" >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone Number 01</label>
                        <input type="text" class="form-control" placeholder="Enter phone number" wire:model="phone_no_01" >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone Number 02</label>
                        <input type="text" class="form-control" placeholder="Enter phone number" wire:model="phone_no_02" >
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> Branch Website</label>
                        <input type="text" class="form-control" placeholder="Enter website URL" wire:model="website" >
                    </div>
                </div>
                <!-- Actions -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-light border me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-sap">
                        <i class="bi bi-save2 me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
