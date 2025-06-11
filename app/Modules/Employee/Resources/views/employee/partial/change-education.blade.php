@php
    $fields = [
        'course_name' => 'Course Name',
        'level' => 'Level',
        'score' => 'Score',
        'division' => 'Division',
        'faculty' => 'Faculty',
        'specialization' => 'Specialization',
        'university_name' => 'university Name',
        'major_subject' => 'Major Subject',
        'type_of_institution' => 'Type of Institute',
        'institution_name' => 'Institution Name',
        'affiliated_to' => 'Affiliated To',
        'attended_from' => 'Attended From',
        'attended_to' => 'Attended To',
        'passed_year' => 'Passed Year',
        'note' => 'Note',
        'is_foreign_board' => 'Is Foreign Board',
        'is_foreign_board_file' => 'Foreign Board File',
        'equivalent_certificates' => 'Equivalent Certificate',
        'degree_certificates' => 'Degree Certificate',
    ];
@endphp
@foreach ($fields as $key => $label)
    <tr>
        <td>{{ $label }}</td>
        <td>{{ @$oldEntity->$key }}</td>
        <td>{{ @$newEntity->$key }}</td>
    </tr>
@endforeach
