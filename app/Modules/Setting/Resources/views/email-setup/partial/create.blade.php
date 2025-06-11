@inject('emailSetupModel', '\App\Modules\Setting\Entities\EmailSetup')

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Module</th>
                <th>Enable Email ?</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($moduleList as $moduleId => $moduleName)
                @php
                    $emailSetup = $emailSetupModel->where('module_id', $moduleId)->first();
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        {{ $moduleName }}
                    </td>
                    {!! Form::hidden("setups[$moduleId][module_id]", $moduleId, []) !!}
                    <td>
                        {!! Form::select("setups[$moduleId][status]", $statusList, isset($emailSetup) ? $emailSetup->status : null, [
                            'class' => 'form-control select-search',
                            'placeholder' => 'Select Option',
                        ]) !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-2 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>
