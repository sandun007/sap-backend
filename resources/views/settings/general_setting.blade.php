@extends('app')
@push('title')
    General Setting
@endpush
@push('path')
    <i class="bi bi-gear text-white" style="font-size: 1.25rem;"></i>
    General Setting
@endpush

@push('css')
    <style>
        /* ===== SAP Inspired Styling ===== */
        .sap-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Input fields */
        .form-control,
        .form-select,
        textarea {
            border: 1px solid #0a3d62;
            border-radius: 6px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #0a3d62;
            box-shadow: 0 0 0 0.15rem rgba(10, 61, 98, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: #0a3d62;
            font-size: 0.9rem;
        }

        /* SAP-style button */
        .btn-sap {
            background-color: #0a3d62;
            color: white;
            border-radius: 4px;
            font-weight: 600;
        }

        .btn-sap:hover {
            background-color: #08324f;
            color: #fff;
        }

        /* Logo upload tile */
        .upload-tile {
            border: 2px dashed #90a4ae;
            border-radius: 8px;
            background-color: #fafafa;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-tile:hover {
            border-color: #0a3d62;
            background: #f0f6fa;
        }

        .upload-tile img {
            max-height: 120px;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        <div class="sap-card shadow-sm">

            <form>
                <div class="row g-4">
                    <!-- Company Info -->
                    <div class="col-md-4">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" placeholder="Enter company name" value="{{$inv_data->company_name}}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Company Flag</label>
                        <input type="text" class="form-control" placeholder="Enter company flag" value="{{$inv_data->company_flag}}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Company Email</label>
                        <input type="email" class="form-control" placeholder="Enter email address" value="{{$inv_data->email}}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone Number 01</label>
                        <input type="text" class="form-control" placeholder="Enter phone number" value="{{$inv_data->phone_no_01}}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone Number 02</label>
                        <input type="text" class="form-control" placeholder="Enter phone number" value="{{$inv_data->phone_no_02}}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Website</label>
                        <input type="text" class="form-control" placeholder="Enter website URL">
                    </div>

                    <!-- Address -->
                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="2" placeholder="Enter company address">{{$inv_data->company_address}}</textarea>
                    </div>

                    <!-- Logo Upload (SAP Tile Style) -->
                    <div class="col-md-4">
                        <label class="form-label">Company Logo</label>
                        <div class="upload-tile text-center" onclick="document.getElementById('companyLogo').click()">
                            <img id="logoPreview"
                                 src="{{asset('assets/img/no-image-available-picture-coming-600nw-2057829641.webp')}}"
                                 alt="Company Logo"
                                 class="img-fluid mb-2">
                            <p class="text-muted small m-0">
                                <i class="bi bi-upload me-1"></i> Click to upload
                            </p>
                            <input type="file" class="d-none" id="companyLogo" accept="image/*">
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
@endsection

@push('script')
    <script>
        document.getElementById("companyLogo").addEventListener("change", function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById("logoPreview");
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
@endpush
