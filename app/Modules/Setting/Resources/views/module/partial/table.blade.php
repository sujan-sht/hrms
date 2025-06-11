<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="text-white">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($modules as $key => $module)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $module->name }}</td>
                    <td>

                        <div class="custom-control custom-control-{{ (in_array($module->name, $mandatory_modules)?'teal':'success')}} custom-checkbox mb-2">
                            @if (in_array($module->name, $mandatory_modules))
                                <input name="modules[{{ $module->name }}]" type="hidden" value="1" />
                            @endif
                            <input name="modules[{{ $module->name }}]" type="checkbox" class="custom-control-input"
                                id="{{ $module->name }}" value="1"
                                {{ $module->status == 1 ? 'checked' : '' }}
                                {{ in_array($module->name, $mandatory_modules) ? 'disabled' : '' }}>
                            <label class="custom-control-label" for="{{ $module->name }}"></label>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
