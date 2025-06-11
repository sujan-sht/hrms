<script src="{{ asset('admin/validation/familyDetail.js') }}"></script>
<script src="{{ asset('admin/validation/editFamilyDetail.js') }}"></script>

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Nominee Details
                        </legend>
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>Contact No</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($nominee_details->isNotEmpty())
                                @foreach ($nominee_details as $key => $nominee)
                                    @php
                                        if (isset($nominee->employeeAddress) && !is_null($nominee->employeeAddress)) {
                                            $address = [
                                                'province' => optional(
                                                    optional($nominee->employeeAddress)->permanentProvinceModel,
                                                )->province_name,
                                                'district' => optional(
                                                    optional($nominee->employeeAddress)->permanentDistrictModel,
                                                )->district_name,
                                                'vdc' => optional($nominee->employeeAddress)->permanentmunicipality_vdc,
                                                'ward' => optional($nominee->employeeAddress)->permanentward,
                                                'address' => optional($nominee->employeeAddress)->permanentaddress,
                                            ];
                                        } else {
                                            $address = [
                                                'province' => optional($nominee->province)->province_name,
                                                'district' => optional($nominee->district)->district_name,
                                                'vdc' => $nominee->municipality ?? null,
                                                'ward' => $nominee->ward_no ?? null,
                                                'address' => $nominee->family_address ?? null,
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
                                        <td>{{ $nominee->name }}</td>
                                        <td>{{ $nominee->relation_type_title }}</td>
                                        <td>{{ $nominee->contact }}</td>
                                        <td>
                                            {{ $fullAddress }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Data Found!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

    })
</script>
