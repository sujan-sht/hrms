
<h5>Competency Title: <span class="text-info">{{$competency->name}}</span></h5>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Question</th>
            </tr>
        </thead>
        <tbody>
            @if($competency->questions->count() != 0)
                @foreach($competency->questions as $key => $question)
                    <tr>
                        <td width="5%">#{{++$key}}</td>
                        <td>{{ $question->question }}</td>
                    </tr>
                @endforeach
            @else
            <tr>
                <td colspan="7">No Questions Found !!!</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
