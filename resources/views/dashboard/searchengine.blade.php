@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/css/autoComplete.min.css">
    <link href="{{ asset('assets/css/searchengine.css') }}" rel="stylesheet">
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="search-engine">
            <div class="text-center search-bar">
                <div class="paywatch-search-bar">
                    <input id="autoComplete" tabindex="1" placeHolder="Search anything or action you want to perform">
                </div>
            </div>
            <div class="text-center paywatch-quick-actions">
                <div class="chip">
                    <a href="{{ route('staff-main') }}"><figure class="avatar avatar-sm" data-initial="S"></figure>Manage Staffs</a>
                </div>
                <div class="chip">
                    <a href="{{ route('staff-transfer') }}"><figure class="avatar avatar-sm" data-initial="ST"></figure>Staff Transfer</a>
                </div>
                <div class="chip">
                    <a href="{{ route('staff-transfer') }}"><figure class="avatar avatar-sm" data-initial="MW"></figure>Staff Work Status</a>
                </div>
                <div class="chip">
                    <a href="{{ route('staff-main-warning') }}"><figure class="avatar avatar-sm" data-initial="WS"></figure>Manage Warning Staff</a>
                </div>
                <div class="chip">
                    <a href="{{ route('staff-job-type-alert') }}"><figure class="avatar avatar-sm" data-initial="SJ"></figure>Manage Staff Job Type</a>
                </div>
            </div>

            <div class="paywatch-staff-quick-actions text-center">
                <h5>Perform Quick Actions on Staff</h5>
                <div class="search-staff-box">
                    <input type="search" autocomplete="new-password" id="search-staffs" tabindex="1" placeHolder="Search Staff by name, CID, Branch ID">
                </div>
                <div class="paywatch-staff-quick-actions-btn-groups">
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-job-information',['id' => '']) }}/">Change Job Type</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-payment',['id' => '']) }}/">Update Job Allowances</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-work-schedule',['id' => '']) }}/">Change Weekend</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-payment',['id' => '']) }}/">Update Salary</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-job-information',['id' => '']) }}/">Update Appointment Date</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-job-information',['id' => '']) }}/">Update Temporary/Contract Date</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-job-information',['id' => '']) }}/">Update Permanent Date</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-transfer-create',['staff_central_id' => '']) }}">Transfer Staff</a>
                    <a class="btn btn-sm btn-outline-info" href="{{ route('staff-status-create',['id' => '']) }}/">Change Work Status</a>
                </div>
            </div>

            <div class="paywatch-actions-attendance">

            </div>
        </div>
    </div>
</div>



@section('script')
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js"></script>
    <script>
        const autoCompletejs = new autoComplete({
            data: {
                src: async () => {
                    // Loading placeholder text
                    document
                        .querySelector("#autoComplete")
                        .setAttribute("placeholder", "Loading...");
                    // Fetch External Data Source
                    /*const source = await fetch(
                        "https://tarekraafat.github.io/autoComplete.js/demo/db/generic.json"
                    );*/
                    const source = await fetch('{{ route('apiActions', ['userID' => Auth::user()->id]) }}');
                    const data = await source.json();
                    // Post loading placeholder text
                    // Returns Fetched data
                    return data;
                },
                key: ["title"],
                cache: false
            },
            sort: (a, b) => {
                if (a.match < b.match) return -1;
                if (a.match > b.match) return 1;
                return 0;
            },
            selector: "#autoComplete",
            threshold: 0,
            debounce: 0,
            searchEngine: "strict",
            highlight: true,
            maxResults: 5,
            resultsList: {
                render: true,
                container: source => {
                    source.setAttribute("id", "autoComplete_list");
                },
                destination: document.querySelector("#autoComplete"),
                position: "afterend",
                element: "ul"
            },
            resultItem: {
                content: (data, source) => {
                    source.innerHTML = data.match;
                },
                element: "li"
            },
            noResults: () => {
                const result = document.createElement("li");
                result.setAttribute("class", "no_result");
                result.setAttribute("tabindex", "1");
                result.innerHTML = "No Results";
                document.querySelector("#autoComplete_list").appendChild(result);
            },
            onSelection: feedback => {
                const selection = feedback.selection.value.link;
                console.log(feedback);
                console.log(selection);
                window.location.href = selection;

            }
        });

        $(function () {
            //get staffs
            $('#search-staffs').selectize({
                plugins: ['remove_button'],
                valueField: 'id',
                labelField: 'name_eng',
                searchField: ['name_eng', 'main_id'],
                options: [],
                // preload: true,
                maxItems: 1,
                create: false,
                render: {
                    option: function (item, escape) {
                        return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                            '<div> Branch ID: ' + item.main_id + '</div>' +
                            '<div> Branch: ' + item.branch.office_name + '</div>' +
                            '<div> CID: ' + item.staff_central_id + '</div>' +
                            '</div>';
                    }
                },
                load: function (query, callback) {
                    console.log('test')
                    if (!query.length) return callback();
                    $.ajax({
                        {{--url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query) + '&limit=15'+ '&branch_id=' + branch,--}}
                        url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query) + '&limit=15',
                        type: 'GET',
                        error: function () {
                            callback();
                        },
                        success: function (res) {
                            callback(res.staffs);
                        }
                    });
                }
            });
            $('input#search-staffs-selectized').attr('autocomplete', ($('input#search-staffs-selectized').attr('autocomplete') == 'new-password') ? 'new-password' : 'off')



        });

        //perform action on button click
        $(document).on('click','.paywatch-staff-quick-actions-btn-groups a', function(e){
            e.preventDefault();
            var staff_id = $('#search-staffs').val();
            if(staff_id != '' && staff_id != 0){ //redirect to action link
                const link = $(this).attr('href')  + staff_id;
                window.location.href = link;
            }
            else{
                vex.dialog.alert('Please select a staff first  to perform the action!');
            }
        });

    </script>
@endsection
