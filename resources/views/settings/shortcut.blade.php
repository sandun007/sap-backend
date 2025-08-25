<script>
    hotkeys('ctrl+g', function(e){
        e.preventDefault();
        window.location.href = "{{ route('setting.general') }}";
    });

    hotkeys('ctrl+z', function(e) {
        e.preventDefault(); // Prevent default undo behavior
        window.history.back(); // Go back to previous page
    });
    hotkeys('ctrl+d', function(e){
        e.preventDefault();
        window.location.href = "{{ route('dashboard') }}";
    });
    {{--hotkeys('ctrl+n, p', function(e) {--}}
    {{--    e.preventDefault();--}}
    {{--    window.location.href = "{{ route('create.product') }}";--}}
    {{--});--}}

</script>
