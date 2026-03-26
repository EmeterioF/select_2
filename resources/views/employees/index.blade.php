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

</head>
<body>

    <h2>Employee Search</h2>

    <!--
        This is the dropdown.
        It will be converted into a Select2 searchable dropdown.
        The options are NOT loaded here because they will come from AJAX.
    -->
    <select class="employee-select" style="width:300px;">
        <option></option>
    </select>

<script>

    /*
        $(document).ready()
        This ensures the code runs only AFTER the page is fully loaded.
        Prevents errors like "element not found".
    */
    $(document).ready(function() {

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

    });

</script>

</body>
</html>