@extends('layouts.app')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Create New Exam</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.exams.index') }}">Exams</a></li>
                <li class="breadcrumb-item active">Create Exam</li>
            </ol>
        </nav>
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body" id="card-body">
                            <form class="row g-3 mt-3" name="create_exam_form" method="post"
                                action="{{ route('admin.exams.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label for="name" class="form-label">Exam Name</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
                                <div class="col-12" id="button-area">
                                    <button type="button" id="add-section-btn" class="btn btn-primary float-end"
                                        title="Add Section"><i class="bi bi-plus"></i></button>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form><!-- Vertical Form -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"
        integrity="sha512-6S5LYNn3ZJCIm0f9L6BCerqFlQ4f5MwNKq+EthDXabtaJvg3TuFLhpno9pcm+5Ynm6jdA9xfpQoMz2fcjVMk9g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        //   new TomSelect(".tom-select",{
        // 	create: false,
        // 	sortField: {
        // 		field: "text",
        // 		direction: "asc"
        // 	}
        // });
        var section_row = 1;
        $(function() {

            $("form[name='create_exam_form']").validate({
                // Specify validation rules
                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                        minlength: 3
                    },
                    description: {
                        required: true,
                        minlength: 10,
                        maxlength: 500
                    },
                    // "sections[]":{
                    //   required:function(){
                    //     return $("#time_limit").val()!="" || $("#questions").val()!="" || $("#files").val()!="";

                    //   },
                    //   maxlength:100,
                    //   minlength:3
                    // },
                    // "questions[]": {
                    //   required:function(){
                    //     return $("#sections").val()!="" || $("#time_limit").val()!="" || $("#files").val()!="";

                    //   },
                    //   range: [1, 100]
                    // },
                    // "time_limit[]": {
                    //   required:function(){
                    //     return $("#sections").val()!="" || $("#questions").val()!="" || $("#files").val()!="";

                    //   },
                    //   range: [1, 320]
                    // },
                    // "files[]": {
                    //   required:function(){
                    //     return $("#sections").val()!="" || $("#questions").val()!="" || $("#time_limit").val()!="";

                    //   },
                    //   extension: "pdf"

                    // },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    }
                },
                // Specify validation error messages
                messages: {
                    email: {
                        remote: "Email already exists!"
                    },
                    // "files[]": {
                    //   extension: "Please select pdf files only",
                    // }
                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function(form) {
                    form.submit();
                }
            });
            $(document).on('click', '#add-section-btn', showSection);
            $("#add-section-btn").trigger("click");
        });

        function showSection() {
            const html = `
             <div class="col-12">
                <label for="section-${section_row}" class="form-label">Section Name</label>
                <input type="text" class="form-control" name="sections[${section_row}]" id="section-${section_row}">
              </div>
             <div class="col-12">
                <label for="time_limit-${section_row}" class="form-label">Time Limit (In Minutes)</label>
                <input type="number" class="form-control" name="time_limit[${section_row}]" id="time_limit-${section_row}" >
              </div>
              <div class="col-12">
                <label for="questions-${section_row}" class="form-label">Number of Questions</label>
                <input type="number" class="form-control" name="questions[${section_row}]" id="questions-${section_row}" >
              </div>


              <div class="col-12">
                <label for="free-textbox-${section_row}" class="form-label">Number of Free TextBox Questions
                    <i class="bi bi-info-circle"   data-toggle="tooltip" data-html="true" title="Number of Free free-textbox Questions out of Total questions "></i>

                    </label>
                <input type="number" class="form-control" name="free_textbox[${section_row}]" id="free-textbox-${section_row}" >
              </div>


                <div class="col-12">
                    <label for="question-types-${section_row}" class="form-label" tittle>
                        Question Types <i class="bi bi-info-circle"   data-toggle="tooltip" data-html="true" title="Information"></i>
                    </label>
                    <select class="form-control" name="question_types[${section_row}]" value="" id="question-types-${section_row}" required>
                        <option value="">Choose</option>
                        <option>Same Pattern</option>
                        <option>Alertanative Pattern</option>
                    </select>
                </div>


                <div class="col-12">
                    <label for="option_types-${section_row}" class="form-label" tittle>
                        Question Option <i class="bi bi-info-circle"   data-toggle="tooltip" data-html="true" title="Information"></i>
                    </label>
                    <select class="form-control" name="option_types[${section_row}]" value="" id="option_types-${section_row}" required multiple>
                        <option value="">Select Option</option>
                        <option value="ABCD">ABCD</option>
                        <option value="EFGH">EFGH</option>
                        <option value="ABCDE">ABCDE</option>
                        <option value="FGHJ">FGHJ </option>
                        <option value="FGHJK">FGHJK</option>
                    </select>
                </div>




              <div class="col-12">
                <label for="breaks-${section_row}" class="form-label">Break Duration (In Minutes)</label>
                <input type="number" class="form-control" name="breaks[${section_row}]" id="breaks-${section_row}" >
              </div>
              <div class="col-12">
                <label for="files-${section_row}" class="form-label">Upload PDF File</label>
                <input type="file" class="form-control" name="files[${section_row}]" id="files-${section_row}" accept="application/pdf"  />
              </div>
              <hr>
     `;
            $(html).insertBefore($("#button-area"));

            selector="#option_types-"+section_row;
            $(document).ready(function() {
                $(selector).select2({
                    maximumSelectionLength: 2
                });
            });
            // add rules
            $('input[name="sections[' + section_row + ']"]').rules("add", { // <- apply rule to new field
                required: true,
                maxlength: 100,
                minlength: 3
            });
            $('input[name="time_limit[' + section_row + ']"]').rules("add", { // <- apply rule to new field
                required: true,
                range: [1, 320]
            });
            $('input[name="questions[' + section_row + ']"]').rules("add", { // <- apply rule to new field
                required: true,
                range: [1, 100]
            });
            $('input[name="breaks[' + section_row + ']"]').rules("add", { // <- apply rule to new field
                required: true,
                range: [1, 100]
            });
            $('input[name="files[' + section_row + ']"]').rules("add", { // <- apply rule to new field
                required: true,
                extension: "pdf"
            });

            section_row++;
        }
        $(document).ready(function() {
            $(document).on('change', '[id^="question-types-"]', function(e) {

                let e_id = $(this).attr('id');
                section_row = e_id.replace("question-types-", "");
                selector="#option_types-"+section_row;
                len=2;

                if( $(this).val() == "Same Pattern"){
                    len=1;
                }
                $(document).ready(function() {
                    $(selector).select2({
                        maximumSelectionLength: len
                    });
                    $(selector).val([]).trigger("change");
                });

            });

            $(document).on('change', '[id^="free-textbox-"]', function(e) {
                let e_id = $(this).attr('id');
                section_row = e_id.replace("free-textbox-", "");

                selector="#questions-"+section_row;
                console.log(selector );
                if( $(this).val() > $(selector).val()){
                    $(this).val($(selector).val());
                }

            });

            $(document).on('change', '[id^="questions-"]', function(e) {
                let e_id = $(this).attr('id');
                section_row = e_id.replace("questions-", "");

                selector="#free-textbox-"+section_row;
                console.log(selector );
                if( $(this).val() < $(selector).val()){
                    $(selector).val($(this).val());
                }

            });


        });
    </script>
@endsection
