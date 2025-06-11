@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@forelse($employeeDemotionModels as $key => $employeeDemotionModel)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ optional($employeeDemotionModel->organization)->name }}</td>
        <td>{{ optional($employeeDemotionModel->branch)->name }}</td>
        <td>{{ optional($employeeDemotionModel->department)->title }}</td>
        <td>{{ optional($employeeDemotionModel->level)->title }}</td>
        <td>{{ optional($employeeDemotionModel->designation)->title }}</td>
        <td>{{ $employeeDemotionModel->job_title }}</td>
        <td>{{ getStandardDateFormat($employeeDemotionModel->date) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6">No employee demotion details found !!!</td>
    </tr>
@endforelse