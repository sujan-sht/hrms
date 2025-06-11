<script src="{{ asset('admin/validation/createEducationDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editEducationDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Education Details
                        </legend>
                    </div>
                    @if ($menuRoles->assignedRoles('educationDetail.save'))
                        @if ($employeeModel->status == 1)
                            <div class="col-1 text-center">
                                <a class="btn btn-sm btn-success rounded-pill createmode" data-name="Education">Create</a>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Type of Institution</th>
                                <th>Institution Name</th>
                                <th>Passed Year</th>
                                <th>Level</th>
                                {{-- <th>Is foreign board</th>
                                <th>Course Name</th>
                                <th>Score/Division</th>
                                <th>Faculty/Specialization</th>
                                <th>University Name / Major Subject</th>
                                <th>Equivalent Certificates</th>
                                <th>Degree Certificates</th> --}}
                                @if ($employeeModel->status == 1)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="educationTable">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="card createEducationDetail">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Create Education Details</legend>
                {{-- create --}}
                <form class="submitEducationDetail validateEducationDetail" enctype="multipart/form-data">
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="level">Level</label>
                            <div class="input-group">
                                {!! Form::text('level', null, [
                                    'placeholder' => 'Enter Level',
                                    'class' => 'form-control',
                                    'id' => 'level',
                                ]) !!}
                            </div>
                            @if ($errors->has('level'))
                                <div class="error text-danger">{{ $errors->first('level') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="course_name">Course Name</label>
                            <div class="input-group">
                                {!! Form::text('course_name', null, [
                                    'placeholder' => 'Enter Course Name',
                                    'class' => 'form-control',
                                    'id' => 'course_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('course_name'))
                                <div class="error text-danger">{{ $errors->first('course_name') }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="type_of_institution">Type Institution</label>
                            <div class="input-group">
                                {!! Form::text('type_of_institution', null, [
                                    'placeholder' => 'Enter Type of Institution',
                                    'class' => 'form-control',
                                    'id' => 'type_of_institution',
                                ]) !!}
                            </div>
                            @if ($errors->has('type_of_institution'))
                                <div class="error text-danger">{{ $errors->first('type_of_institution') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="institution_name">Institution Name</label>
                            <div class="input-group">
                                {!! Form::text('institution_name', null, [
                                    'placeholder' => 'Enter Institution Name',
                                    'class' => 'form-control',
                                    'id' => 'institution_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('institution_name'))
                                <div class="error text-danger">{{ $errors->first('institution_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="division">Division</label>
                            <div class="input-group">
                                {!! Form::text('division', null, [
                                    'placeholder' => 'Enter Division',
                                    'class' => 'form-control',
                                    'id' => 'division',
                                ]) !!}
                            </div>
                            @if ($errors->has('division'))
                                <div class="error text-danger">{{ $errors->first('division') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="score">Score</label>
                            <div class="input-group">
                                {!! Form::text('score', null, [
                                    'placeholder' => 'Enter Score',
                                    'class' => 'form-control',
                                    'id' => 'score',
                                ]) !!}
                            </div>
                            @if ($errors->has('score'))
                                <div class="error text-danger">{{ $errors->first('score') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="passed_year">Passed Year</label>
                            <div class="input-group">
                                {!! Form::text('passed_year', null, [
                                    'placeholder' => 'Enter Passed Year',
                                    'class' => 'form-control',
                                    'id' => 'passed_year',
                                ]) !!}
                            </div>
                            @if ($errors->has('passed_year'))
                                <div class="error text-danger">{{ $errors->first('passed_year') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="faculty">Faculty</label>
                            <div class="input-group">
                                {!! Form::text('faculty', null, [
                                    'placeholder' => 'Enter Faculty Name',
                                    'class' => 'form-control',
                                    'id' => 'faculty',
                                ]) !!}
                            </div>
                            @if ($errors->has('faculty'))
                                <div class="error text-danger">{{ $errors->first('faculty') }}</div>
                            @endif
                        </div>
                    </div>




                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="university_name">University Name</label>
                            <div class="input-group">
                                {!! Form::text('university_name', null, [
                                    'placeholder' => 'Enter University Name',
                                    'class' => 'form-control',
                                    'id' => 'university_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('university_name'))
                                <div class="error text-danger">{{ $errors->first('university_name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="specialization">Specialization</label>
                            <div class="input-group">
                                {!! Form::text('specialization', null, [
                                    'placeholder' => 'Enter Specialization',
                                    'class' => 'form-control',
                                    'id' => 'specialization',
                                ]) !!}
                            </div>
                            @if ($errors->has('specialization'))
                                <div class="error text-danger">{{ $errors->first('specialization') }}</div>
                            @endif
                        </div>


                    </div>


                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="attended_from">Attended From</label>
                            <div class="input-group">
                                {!! Form::text('attended_from', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'attended_from',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('attended_from'))
                                <div class="error text-danger">{{ $errors->first('attended_from') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="attended_to">Attended To</label>
                            <div class="input-group">
                                {!! Form::text('attended_to', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'attended_to',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('attended_to'))
                                <div class="error text-danger">{{ $errors->first('attended_to') }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="affiliated_to">Affiliated To</label>
                            <div class="input-group">
                                {!! Form::text('affiliated_to', null, [
                                    'placeholder' => 'Enter Affiliated To Name',
                                    'class' => 'form-control',
                                    'id' => 'affiliated_to',
                                ]) !!}
                            </div>
                            @if ($errors->has('affiliated_to'))
                                <div class="error text-danger">{{ $errors->first('affiliated_to') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="note">Note</label>
                            <div class="input-group">
                                {!! Form::text('note', null, [
                                    'placeholder' => 'Enter Note',
                                    'class' => 'form-control',
                                    'id' => 'note',
                                ]) !!}
                            </div>
                            @if ($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label for="major_subject"> Affiliated Board</label>
                            <div class="input-group">
                                <input type="radio" name="is_foreign_board" value="1" id="is_foreign_board">
                                <span class="p-2">Foreign</span>
                                <input type="radio" name="is_foreign_board" value="0" id="is_foreign_board">
                                <span class="p-2">Nepal</span>
                            </div>
                            <div class="" id="foreignDiv" style="display: none;">
                                <div class="row mb-2">
                                    <label class="col-form-label" style="margin-left: 1rem;">Foreign Board
                                        File</label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <input type="file" name="is_foreign_board_file" class="form-control"
                                            id="is_foreign_board_file">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-form-label" style="margin-left: 1rem;">Equivalent
                                        Certificates</label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <input class="form-control" type="file" name="equivalent_certificates"
                                            id="equivalent_certificates" accept="image/*" multiple>

                                        @if ($errors->has('equivalent_certificates'))
                                            <div class="error text-danger">
                                                {{ $errors->first('equivalent_certificates') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label for="major_subject">Major Subject</label>
                            <div class="input-group">
                                {!! Form::text('major_subject', null, [
                                    'placeholder' => 'Enter Major Subject Name',
                                    'class' => 'form-control',
                                    'id' => 'major_subject',
                                ]) !!}
                            </div>
                            @if ($errors->has('major_subject'))
                                <div class="error text-danger">{{ $errors->first('major_subject') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-form-label" style="margin-left: 1rem;">Degree Certificates</label>
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <input class="form-control" type="file" name="degree_certificates"
                                id="degree_certificates" accept="image/*" multiple>

                            @if ($errors->has('degree_certificates'))
                                <div class="error text-danger">{{ $errors->first('degree_certificates') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit"
                            class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                    class="icon-database-insert"></i></b>Save
                        </button>
                        <a type="submit" href="javascript:void(0)"
                            class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                    class="icon-cancel-circle2"></i></b>Discard</a>
                    </div>
                </form>
            </div>
        </div>
        {{-- edit --}}
        <div class="card editEducationDetail" style="display: none">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Edit Education Details</legend>
                <form class="updateEducationDetail validateUpdateEducationDetail" enctype="multipart/form-data">
                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="type_of_institution">Type of Institution
                            </label>
                            <div class="input-group">
                                {!! Form::text('type_of_institution', null, [
                                    'placeholder' => 'Enter Type of Institution',
                                    'class' => 'form-control',
                                    'id' => 'edit_type_of_institution',
                                ]) !!}
                            </div>
                            @if ($errors->has('type_of_institution'))
                                <div class="error text-danger">{{ $errors->first('type_of_institution') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="institution_name">Institution Name</label>
                            <div class="input-group">
                                {!! Form::text('institution_name', null, [
                                    'placeholder' => 'Enter Institution Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_institution_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('institution_name'))
                                <div class="error text-danger">{{ $errors->first('institution_name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="affiliated_to">Affiliated To</label>
                            <div class="input-group">
                                {!! Form::text('affiliated_to', null, [
                                    'placeholder' => 'Enter Affiliated To Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_affiliated_to',
                                ]) !!}
                            </div>
                            @if ($errors->has('affiliated_to'))
                                <div class="error text-danger">{{ $errors->first('affiliated_to') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="attended_from">Attended From</label>
                            <div class="input-group">
                                {!! Form::text('attended_from', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'edit_attended_from',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('attended_from'))
                                <div class="error text-danger">{{ $errors->first('attended_from') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="attended_to">Course Name</label>
                            <div class="input-group">
                                {!! Form::text('course_name', null, [
                                    'placeholder' => 'Enter Course Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_course_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('course_name'))
                                <div class="error text-danger">{{ $errors->first('course_name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="score">Score</label>
                            <div class="input-group">
                                {!! Form::text('score', null, [
                                    'placeholder' => 'Enter Score',
                                    'class' => 'form-control',
                                    'id' => 'edit_score',
                                ]) !!}
                            </div>
                            @if ($errors->has('score'))
                                <div class="error text-danger">{{ $errors->first('score') }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="division">Division</label>
                            <div class="input-group">
                                {!! Form::text('division', null, [
                                    'placeholder' => 'Enter Division',
                                    'class' => 'form-control',
                                    'id' => 'edit_division',
                                ]) !!}
                            </div>
                            @if ($errors->has('division'))
                                <div class="error text-danger">{{ $errors->first('division') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="faculty">Faculty</label>
                            <div class="input-group">
                                {!! Form::text('faculty', null, [
                                    'placeholder' => 'Enter Faculty Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_faculty',
                                ]) !!}
                            </div>
                            @if ($errors->has('faculty'))
                                <div class="error text-danger">{{ $errors->first('faculty') }}</div>
                            @endif
                        </div>
                    </div>



                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="university_name">University Name</label>
                            <div class="input-group">
                                {!! Form::text('university_name', null, [
                                    'placeholder' => 'Enter University Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_university_name',
                                ]) !!}
                            </div>
                            @if ($errors->has('university_name'))
                                <div class="error text-danger">{{ $errors->first('university_name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="major_subject">Major Subject</label>
                            <div class="input-group">
                                {!! Form::text('major_subject', null, [
                                    'placeholder' => 'Enter Major Subject Name',
                                    'class' => 'form-control',
                                    'id' => 'edit_major_subject',
                                ]) !!}
                            </div>
                            @if ($errors->has('major_subject'))
                                <div class="error text-danger">{{ $errors->first('major_subject') }}</div>
                            @endif
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="attended_to">Attended To</label>
                            <div class="input-group">
                                {!! Form::text('attended_to', null, [
                                    'placeholder' => 'Choose Date',
                                    'class' => 'form-control daterange-single',
                                    'id' => 'edit_attended_to',
                                    'readonly',
                                ]) !!}
                            </div>
                            @if ($errors->has('attended_to'))
                                <div class="error text-danger">{{ $errors->first('attended_to') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="passed_year">Passed Year</label>
                            <div class="input-group">
                                {!! Form::text('passed_year', null, [
                                    'placeholder' => 'Enter Passed Year',
                                    'class' => 'form-control',
                                    'id' => 'edit_passed_year',
                                ]) !!}
                            </div>
                            @if ($errors->has('passed_year'))
                                <div class="error text-danger">{{ $errors->first('passed_year') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="level">Level</label>
                            <div class="input-group">
                                {!! Form::text('level', null, [
                                    'placeholder' => 'Enter Level',
                                    'class' => 'form-control',
                                    'id' => 'edit_level',
                                ]) !!}
                            </div>
                            @if ($errors->has('level'))
                                <div class="error text-danger">{{ $errors->first('level') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                            <label for="note">Note</label>
                            <div class="input-group">
                                {!! Form::text('note', null, [
                                    'placeholder' => 'Enter Note',
                                    'class' => 'form-control',
                                    'id' => 'edit_note',
                                ]) !!}
                            </div>
                            @if ($errors->has('note'))
                                <div class="error text-danger">{{ $errors->first('note') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label for="specialization">Specialization</label>
                            <div class="input-group">
                                {!! Form::text('specialization', null, [
                                    'placeholder' => 'Enter Specialization',
                                    'class' => 'form-control',
                                    'id' => 'edit_specialization',
                                ]) !!}
                            </div>
                            @if ($errors->has('specialization'))
                                <div class="error text-danger">{{ $errors->first('specialization') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <label for="major_subject"> Affiliated Board</label>
                            <div class="input-group">
                                <input type="radio" name="edit_is_foreign_board" value="1"
                                    id="edit_is_foreign_board">
                                <span class="p-2">Foreign</span>
                                <input type="radio" name="edit_is_foreign_board" value="0"
                                    id="edit_is_foreign_board">
                                <span class="p-2">Nepal</span>
                            </div>
                            <div class="" id="editforeignDiv" style="display: none;">
                                <div class="row mb-2">
                                    <label class="col-form-label" style="margin-left: 1rem;">Foreign Board
                                        File</label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <input type="file" name="is_foreign_board_file" class="form-control"
                                            id="edit_is_foreign_board_file">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label class="col-form-label" style="margin-left: 1rem;">Equivalent
                                        Certificates</label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <input class="form-control" type="file" name="equivalent_certificates"
                                            id="edit_equivalent_certificates" accept="image/*" multiple>

                                        @if ($errors->has('equivalent_certificates'))
                                            <div class="error text-danger">
                                                {{ $errors->first('equivalent_certificates') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="row mb-2">
                        <label class="col-form-label" style="margin-left: 1rem;">Degree Certificates</label>
                        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                            <input class="form-control" type="file" name="degree_certificates"
                                id="edit_degree_certificates" accept="image/*" multiple>

                            @if ($errors->has('degree_certificates'))
                                <div class="error text-danger">{{ $errors->first('degree_certificates') }}</div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="educationDetailId" class="educationDetailId">
                    <div class="text-center">
                        <button type="submit"
                            class="ml-2 mt-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                                    class="icon-database-insert"></i></b>Save
                        </button>
                        <a type="submit" href="javascript:void(0)"
                            class="ml-2 mt-2 btn btn-secondary btn-labeled btn-labeled-left float-right go-back"><b><i
                                    class="icon-cancel-circle2"></i></b>Discard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('employee::employee.partial.employeeEdu')
</div>
@include('employee::employee.js.educationDetailJsFunction')

<script>
    $(document).ready(function() {
        // Initially hide the file input
        $('input[name="is_foreign_board_file"]').hide();

        // Listen for changes on the radio buttons
        $('input[name="is_foreign_board"]').change(function() {
            if ($('#is_foreign_board').is(':checked')) {
                $('input[name="is_foreign_board_file"]').show(); // Show file input
                $('#foreignDiv').show(); // Show file input
            } else {
                $('input[name="is_foreign_board_file"]').hide(); // Hide file input
                $('#foreignDiv').hide(); // Hide file input
            }
        });

        $('input[name="edit_is_foreign_board"]').change(function() {
            if ($('#edit_is_foreign_board').is(':checked')) {
                $('input[name="is_foreign_board_file"]').show(); // Show file input
                $('#editforeignDiv').show(); // Show file input
            } else {
                $('input[name="is_foreign_board_file"]').hide(); // Hide file input
                $('#editforeignDiv').hide(); // Hide file input
            }
        });
    });
</script>
