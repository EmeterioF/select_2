<!DOCTYPE html>
<html>
<head>
    <title>Select2 Employee Search</title>

    <!--
        jQuery is REQUIRED because Select2 depends on it.
        Without jQuery, Select2 will NOT work.
    -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--
        Select2 CSS:
        This controls the styling (design) of the dropdown.
        Without this, it will look like a normal select box.
    -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

    <!--
        Select2 JavaScript:
        This is the main library that adds search functionality,
        AJAX loading, and advanced dropdown features.
    -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 24px auto;
            line-height: 1.4;
        }

        .employee-select {
            width: 360px;
        }

        .employee-details {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1fr 260px;
            gap: 28px;
            align-items: start;
        }

        .field {
            margin-bottom: 12px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .field input {
            width: 100%;
            border: 1px solid #cfcfcf;
            border-radius: 4px;
            padding: 9px 10px;
            font-size: 14px;
            box-sizing: border-box;
            background: #f7f7f7;
        }

        .profile-box {
            width: 230px;
            height: 230px;
            border: 2px solid #bdbdbd;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fafafa;
        }

        .profile-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .profile-placeholder {
            color: #777;
            font-weight: 700;
            letter-spacing: 1px;
        }

        @media (max-width: 760px) {
            .employee-details {
                grid-template-columns: 1fr;
            }

            .profile-box {
                width: 100%;
                max-width: 260px;
            }
        }
    </style>
</head>
<body>
    <h2>Employee Search</h2>

    <!--
        This is the dropdown.
        It will be converted into a Select2 searchable dropdown.
        The options are NOT loaded here because they will come from AJAX.
    -->
    <select class="employee-select">
        <option></option>
    </select>

    <div class="employee-details">
        <div>
            <h2>Add Employee</h2>

            <div class="field">
                <label for="employee-email">Email:</label>
                <input id="employee-email" type="text" readonly>
            </div>

            <div class="field">
                <label for="employee-phone">Phone Number:</label>
                <input id="employee-phone" type="text" readonly>
            </div>

            <div class="field">
                <label for="employee-department">Department:</label>
                <input id="employee-department" type="text" readonly>
            </div>
        </div>

        <div class="profile-box">
            <img id="employee-photo" alt="Employee profile photo">
            <span class="profile-placeholder" id="photo-placeholder">PIC</span>
        </div>
    </div>

<script>

    /*
        $(document).ready()
        This ensures the code runs only AFTER the page is fully loaded.
        Prevents errors like "element not found".
    */
    $(document).ready(function() {
        const $email = $('#employee-email');
        const $phone = $('#employee-phone');
        const $department = $('#employee-department');
        const $photo = $('#employee-photo');
        const $placeholder = $('#photo-placeholder');

        function clearEmployeeDetails() {
            $email.val('');
            $phone.val('');
            $department.val('');
            $photo.attr('src', '').hide();
            $placeholder.show();
        }

        function populateEmployeeDetails(employee) {
            $email.val(employee.email || '');
            $phone.val(employee.phone_number || '');
            $department.val(employee.department || '');

            if (employee.profile_photo) {
                $photo.attr('src', employee.profile_photo).show();
                $placeholder.hide();
            } else {
                $photo.attr('src', '').hide();
                $placeholder.show();
            }
        }

        /*
            Initialize Select2 on the dropdown.
            This transforms the normal select into a searchable AJAX dropdown.
        */
        $('.employee-select').select2({
            /*
                Placeholder:
                Text shown when nothing is selected.
            */
            placeholder: "Search Employee",

            /*
                allowClear:
                Adds an "x" button so the user can clear selection.
            */
            allowClear: true,

            /*
                AJAX configuration:
                This tells Select2 to fetch data from the server
                instead of loading all records at once.
            */
            ajax: {

                /*
                    URL of your Laravel route.
                    This sends the search request to your controller.
                */
                url: "{{ route('employees.search') }}",

                /*
                    Data type expected from server.
                    We expect JSON response.
                */
                dataType: 'json',

                /*
                    Delay before sending request (250ms).
                    This prevents too many requests while typing.
                    It improves performance.
                */
                delay: 250,

                /*
                    This function sends data to the server.
                    params.term = what the user typed.
                    params.page = current page number (for pagination).
                */
                data: function (params) {
                    return {
                        // Search keyword
                        search: params.term,

                        // Page number (default is 1)
                        page: params.page || 1
                    };
                },

                /*
                    This function receives data from Laravel
                    and converts it into Select2 format.
                */
                processResults: function (data, params) {
                    // Ensure page number exists
                    params.page = params.page || 1;

                    return {
                        /*
                            results:
                            This is the array of employees
                            that will appear in the dropdown.
                        */
                        results: data.results,

                        /*
                            pagination.more:
                            Tells Select2 if more records exist.
                            If true -> allows infinite scrolling.
                        */
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },

                /*
                    cache:
                    Saves results temporarily to improve speed.
                */
                cache: true
            }
        });

        $('.employee-select').on('select2:select', function (event) {
            populateEmployeeDetails(event.params.data);
        });

        $('.employee-select').on('select2:clear', function () {
            clearEmployeeDetails();
        });

        clearEmployeeDetails();
    });

</script>

</body>
</html>
