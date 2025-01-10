
<!-- resources/views/orders/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Order</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Create Order</h1>

    <form action="{{ url('/orders') }}" method="POST">
        @csrf
        <div>
            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>
        </div>

        <div>
            <label for="department">Department:</label>
            <select id="department" name="department_id" required>
                <option value="">Select Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->description }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="district">District:</label>
            <select id="district" name="district_id" required>
                <option value="">Select District</option>
            </select>
        </div>

        <button type="submit">Submit</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#department').on('change', function() {
    var departmentId = $(this).val();
    $('#district').empty().append('<option value="">Select District</option>');

    if (departmentId) {
        $.ajax({
            url: '/fetch-districts',
            type: 'GET',
            data: { department_id: departmentId },
            success: function(data) {
                $.each(data, function(key, district) {
                    $('#district').append('<option value="' + district.id + '">' + district.description + '</option>');
                });
            },
            error: function(xhr) {
                console.error('Error fetching districts:', xhr.responseText);
            }
        });
    }
});

        });
    </script>
</body>
</html>

