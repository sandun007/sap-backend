@extends('app')
@push('title')
    Brand
@endpush
@push('path')
    Brand Details
@endpush
@push('css')
    <style>
        /* ===== SAP Fiori Inspired Styling ===== */
        body {
            background-color: #f5f6f8;
        }

        .sap-card {
            border: 1px solid #dfe3e6;
            border-radius: 10px;
            background: #fff;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .sap-header {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0a3d62;
            margin-bottom: 1rem;
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
            box-shadow: 0 0 0 0.15rem rgba(10, 61, 98, 0.15);
        }

        .form-label {
            font-weight: 600;
            color: #0a3d62;
            font-size: 0.85rem;
        }

        /* SAP-style button */
        .btn-sap {
            background-color: #0a3d62;
            color: white;
            border-radius: 6px;
            font-weight: 600;
            padding: 0.45rem 1.2rem;
        }

        .btn-sap:hover {
            background-color: #08324f;
            color: #fff;
        }

        .btn-light {
            font-weight: 600;
            color: #333;
        }

        /* Table */
        .table {
            border: none;
            margin: 0;
        }

        .table thead {
            background: #f0f6fa;
        }

        .table thead th {
            border: none;
            font-weight: 600;
            color: #0a3d62;
            font-size: 0.9rem;
        }

        .table tbody td,
        .table tbody th {
            vertical-align: middle;
            border-top: 1px solid #e0e0e0;
        }

        /* Action buttons */
        .btn-warning.btn-sm,
        .btn-danger.btn-sm {
            font-size: 0.8rem;
            padding: 0.3rem 0.7rem;
            border-radius: 4px;
            font-weight: 500;
        }
        .table-primary{
            background-color: #0a3d62 !important;
        }
    </style>
@endpush

@section('content')
    @livewire('brand-create')
@endsection
