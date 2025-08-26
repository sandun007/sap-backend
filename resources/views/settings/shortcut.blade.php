<script>
    hotkeys('ctrl+g', function (e) {
        e.preventDefault();
        window.location.href = "{{ route('setting.general') }}";
    });

    hotkeys('ctrl+z', function (e) {
        e.preventDefault(); // Prevent default undo behavior
        window.history.back(); // Go back to previous page
    });
    hotkeys('ctrl+d', function (e) {
        e.preventDefault();
        window.location.href = "{{ route('dashboard') }}";
    });

</script>
<script>
    let keysPressed = {};

    document.addEventListener("keydown", function (e) {
        keysPressed[e.key.toLowerCase()] = true;

        // Alt + N + P
        if (keysPressed['alt'] && keysPressed['p']) {
            e.preventDefault();
            window.location.href = "{{ route('product.create') }}";
        }
        if (keysPressed['alt'] && keysPressed['b']) {
            e.preventDefault();
            window.location.href = "{{ route('brand.create') }}";
        }
        if (keysPressed['alt'] && keysPressed['c'] && keysPressed['t']) {
            e.preventDefault();
            window.location.href = "{{ route('category.create') }}";
        }
        if (keysPressed['alt'] && keysPressed['b'] && keysPressed['r']) {
            e.preventDefault();
            window.location.href = "{{ route('branch.create') }}";
        }
    });

    document.addEventListener("keyup", function (e) {
        keysPressed[e.key.toLowerCase()] = false;
    });
</script>

