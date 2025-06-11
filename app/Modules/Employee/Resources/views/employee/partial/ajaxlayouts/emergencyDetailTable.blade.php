@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@forelse($emergency_details as $key => $item)
    @php
        if (isset($item->employeeAddress) && !is_null($item->employeeAddress)) {
            $address = [
                'province' => optional(optional($item->employeeAddress)->permanentProvinceModel)->province_name,
                'district' => optional(optional($item->employeeAddress)->permanentDistrictModel)->district_name,
                'vdc' => optional($item->employeeAddress)->permanentmunicipality_vdc,
                'ward' => optional($item->employeeAddress)->permanentward,
                'address' => optional($item->employeeAddress)->permanentaddress,
            ];
        } else {
            $address = [
                'province' => optional($item->province)->province_name,
                'district' => optional($item->district)->district_name,
                'vdc' => $item->municipality ?? null,
                'ward' => $item->ward_no ?? null,
                'address' => $item->family_address ?? null,
            ];
        }
        $address = json_decode(json_encode($address), true);
        $fullAddress = collect([
            $address['province'] ?? null,
            $address['district'] ?? null,
            $address['vdc'] ?? null,
            $address['ward'] ?? null,
            $address['address'] ?? null,
        ])
            ->filter()
            ->implode(', ');

    @endphp
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->relation_type_title }}</td>
        <td>{{ $item->contact }}</td>
        <td>
            {{ $fullAddress }}
        </td>
    @empty
    <tr>
        <td colspan="5">No Emergency Details Found !!!</td>
    </tr>
@endforelse


<script>
    $('.confirmDelete').on('click', function() {
        let id = $(this).data('id')
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteEmergencyDetail(id)
            }
        });

    });

    $('.editEmergency').on('click', function() {
        let all = $(this).data('all')
        editEmergency(all);
        var that = $(this);
        editModal(that);
    })
</script>
