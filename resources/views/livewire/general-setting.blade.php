<div>
    <div class="container-fluid py-4">
        @if (session()->has('success'))
            <div class="row">
                <div class="col-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            {{ session('success') }}
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
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" placeholder="Enter company name" wire:model="name" >
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Company Flag</label>
                        <input type="text" class="form-control" placeholder="Enter company flag" wire:model="flag">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Company Email</label>
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
                        <label class="form-label">Website</label>
                        <input type="text" class="form-control" placeholder="Enter website URL" wire:model="website" >
                    </div>

                    <!-- Address -->
                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="2" wire:model="address" placeholder="Enter company address"></textarea>
                    </div>

                    <!-- Logo Upload (SAP Tile Style) -->
                    <!-- Logo Upload (SAP Tile Style) -->
                    <div class="col-md-4">
                        <label class="form-label">Company Logo</label>
                        <div class="upload-tile text-center" onclick="document.getElementById('companyLogo').click()">

                            @if ($logo)
                                {{-- Show temporary uploaded image --}}
                                <img id="logoPreview"
                                     src="{{ $logo->temporaryUrl() }}"
                                     alt="Preview"
                                     class="img-fluid mb-2">
                            @elseif($inv_data && $inv_data->logo)
                                {{-- Show saved company logo from DB --}}
                                <img id="logoPreview"
                                     src="{{ asset('storage/'.$inv_data->logo) }}"
                                     alt="Company Logo"
                                     class="img-fluid mb-2">
                            @else
                                {{-- Show default placeholder --}}
                                <img id="logoPreview"
                                     src="{{ asset('assets/img/no-image-available-picture-coming-600nw-2057829641.webp') }}"
                                     alt="No Image"
                                     class="img-fluid mb-2">
                            @endif

                            <p class="text-muted small m-0">
                                <i class="bi bi-upload me-1"></i> Click to upload
                            </p>
                            <input type="file" class="d-none" id="companyLogo" wire:model="logo" accept="image/*">
                        </div>
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
