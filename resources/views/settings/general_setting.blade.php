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
    @livewire('general-setting')
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
