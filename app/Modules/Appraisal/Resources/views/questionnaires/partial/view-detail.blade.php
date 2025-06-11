
<h5>Questionnaire Title: <span class="text-info">{{$questionnaire->title}}</span></h5>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Competency</th>
            </tr>
        </thead>
        <tbody>
            @if($competencies->count() != 0)
                @foreach($competencies as $key => $competency)
                    <tr>
                        <td width="5%">#{{++$key}}</td>
                        <td>{{ $competency->name }}</td>
                    </tr>
                @endforeach
            @else
            <tr>
                <td colspan="7">No Competencies Found !!!</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

