@extends('app')
@push('title')
    Create Product
@endpush
@push('path')
    Create New Product
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
                    <!-- Product Info -->
                    <div class="col-md-4">
                        <label class="form-label">Product Code</label>
                        <input type="text" class="form-control" placeholder="Enter Product Code">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" placeholder="Enter Product Name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Barcode</label>
                        <input type="text" class="form-control" placeholder="Enter Barcode">
                    </div>

                    <!-- Category, Brand, Branch -->
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select class="form-select">
                            <option>Select Category</option>
                            <option>Electronics</option>
                            <option>Furniture</option>
                            <option>Clothing</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Brand</label>
                        <select class="form-select">
                            <option>Select Brand</option>
                            <option>Brand A</option>
                            <option>Brand B</option>
                            <option>Brand C</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Branch</label>
                        <select class="form-select">
                            <option>Select Branch</option>
                            <option>Colombo</option>
                            <option>Kandy</option>
                            <option>Galle</option>
                        </select>
                    </div>

                    <!-- Price & Stock -->
                    <div class="col-md-4">
                        <label class="form-label">Purchase Price</label>
                        <input type="number" class="form-control" placeholder="Enter Purchase Price">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Selling Price</label>
                        <input type="number" class="form-control" placeholder="Enter Selling Price">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" placeholder="Enter Stock Quantity">
                    </div>

                    <!-- Logo Upload -->
                    <div class="col-md-4">
                        <label class="form-label">Product Image</label>
                        <div class="upload-tile text-center" onclick="document.getElementById('productImage').click()">
                            <img id="imagePreview"
                                 src="{{asset('assets/img/no-image-available-picture-coming-600nw-2057829641.webp')}}"
                                 alt="Product Image" class="img-fluid mb-2">
                            <p class="text-muted small m-0"><i class="bi bi-upload me-1"></i> Click to upload</p>
                            <input type="file" class="d-none" id="productImage" accept="image/*">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-8">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Enter Product Description"></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-light border me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-sap">
                        <i class="bi bi-save2 me-1"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById("productImage").addEventListener("change", function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById("imagePreview");
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
@endpush
