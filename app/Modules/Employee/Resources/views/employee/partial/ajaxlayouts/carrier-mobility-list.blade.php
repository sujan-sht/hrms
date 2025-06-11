@inject('mobility', '\App\Modules\Employee\Entities\EmployeeCarrierMobility')

@forelse($carrierMobilityModels as $key => $carrierMobilityModel)
    <tr>
        <td width="5%">#{{ ++$key }}</td>
        <td>{{ getStandardDateFormat($carrierMobilityModel->date) }}</td>
        <td>{{ $carrierMobilityModel->getTypeList() }}</td>
        <td>{{ $mobility->getTypewiseName($carrierMobilityModel) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6">No employee career mobility details found !!!</td>
    </tr>
@endforelse