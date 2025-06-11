  <div class="row">
      {{-- @if ($employees->total() != 0) --}}
      @foreach ($employees as $key => $value)
          @php
              if ($value->profile_pic != '') {
                  $imagePath = asset($value->file_full_path) . '/profile_pic/' . $value->profile_pic;
              } else {
                  $imagePath = asset('admin/default.png');
              }
          @endphp

          <div class="col-xl-3 col-sm-6">
              <div class="card bg-secondary text-white"
                  style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                  <div class="card-body text-center">
                      <div class="card-img-actions d-inline-block mb-3" style="width:150px; height:150px;">
                          <img class="img-fluid rounded-circle" src="{{ $imagePath }}" alt=""
                              style="width: 100%; height: 100%; object-fit: cover;">
                          <div class="card-img-actions-overlay card-img rounded-circle">
                              @php
                                  $UserInfo = App\Modules\Employee\Entities\Employee::getUserInfoByEmp($value->id);
                              @endphp

                              <input type="hidden" value="{{ $UserInfo->parent_id ?? '' }}"
                                  id="user_parent_id_{{ $value->id }}" />

                              @if ($value->is_user_access == '1')
                                  <a data-toggle="modal" data-target="#"
                                      class="btn btn-outline-white border-2 btn-icon rounded-pill remove_user_access"
                                      link="" data-popup="tooltip" data-placement="bottom"
                                      data-original-title="User access granted"><i class="icon-user-check"></i></a>
                              @else
                                  <a data-toggle="modal" data-target="#modal_theme_success"
                                      class="ml-1 btn btn-outline-white border-2 btn-icon rounded-pill employer_user_access"
                                      emp_id="{{ $value->id }}"
                                      email="{{ $value->official_email ?? $value->personal_email }}"
                                      employee_id="{{ $value->employee_id }}" data-popup="tooltip"
                                      data-placement="bottom" data-original-title="User Access"><i
                                          class="icon-user-plus"></i></a>
                              @endif
                              @if ($value->is_parent_link == '1')
                                  <a data-toggle="modal" data-target="#modal_parent_link"
                                      class="ml-1 btn btn-outline-white border-2 btn-icon rounded-pill user_parent_link"
                                      empId="{{ $value->id }}" data-placement="bottom" data-popup="tooltip"
                                      data-placement="top" data-original-title="Link With Parent"><i
                                          class="icon-user-check"></i></a>
                              @else
                                  <a data-toggle="modal" data-target="#modal_parent_link"
                                      class="ml-1 btn btn-outline-white border-2 btn-icon rounded-pill user_parent_link"
                                      empId="{{ $value->id }}" data-placement="bottom" data-popup="tooltip"
                                      data-placement="top" data-original-title="Link With Parent"><i
                                          class="icon-user-plus"></i></a>
                              @endif

                          </div>
                      </div>
                      <span class="d-block opacity-100">{{ $value->employee_code }}</span>
                      <h6 class="font-weight-semibold mb-0">
                          {{ $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name }}</h6>
                      <span class="d-block opacity-75">{{ optional($value->designation)->title }}</span>
                      <span class="d-block opacity-75">{{ optional($value->organizationModel)->name }}</span>
                      <ul class="list-inline list-inline-condensed mb-0 mt-2">
                          <li class="list-inline-item"><a href="{{ route('employee.view', $value->id) }}"
                                  class="btn btn-outline-primary btn-icon text-light border-1" data-popup="tooltip"
                                  data-placement="bottom" data-original-title="View employee">
                                  <i class="icon-eye"></i></a>
                          </li>
                          @if ($menuRoles->assignedRoles('employee.edit'))
                              <li class="list-inline-item"><a href="{{ route('employee.edit', $value->id) }}"
                                      class="btn btn-outline-success btn-icon text-light border-1" data-popup="tooltip"
                                      data-placement="bottom" data-original-title="Edit employee">
                                      <i class="icon-pencil"></i></a>
                              </li>
                          @endif
                          <li class="list-inline-item">
                              @if ($value->status == '1')
                                  <a data-toggle="modal" data-target="#modal_theme_warning_status"
                                      class="btn btn-outline-warning text-light btn-icon border-1 status_employee"
                                      employment_id="{{ $value->id }}" data-popup="tooltip" data-placement="bottom"
                                      data-original-title="Offboard"><i class="icon-basket"></i></a>
                              @else
                                  <a data-toggle="modal" data-target="#modal_theme_warning_status"
                                      class="btn btn-outline-warning text-light btn-icon border-1 status_employee"
                                      employee_id="{{ $value->id }}" data-popup="tooltip" data-placement="bottom"
                                      data-original-title="In-Active Employer"><i class="icon-basket"></i></a>
                              @endif
                          </li>
                      </ul>
                  </div>
                  @php
                      if ($value->status == '1') {
                          $status = 'Active';
                          $color = 'bg-success';
                      } else {
                          $status = 'InActive';
                          $color = 'bg-danger';
                      }
                  @endphp
                  @if (isset($value->getUser))
                      <div class="ribbon-container">
                          <div class="ribbon {{ $color }}">
                              <a class="text-light" href="" data-popup="tooltip"
                                  data-original-title="Employee Status" data-placement="bottom">{{ $status }}</a>
                          </div>
                      </div>
                  @endif
              </div>
          </div>
      @endforeach
      {{-- @endif --}}
  </div>

  <div class="row">
      <div class="col-12">
          <ul class="pagination pagination-rounded justify-content-end mb-3">
              @if ($employees->total() != 0)
                  {{ $employees->appends(request()->all() + ['switch_view' => 'grid-view'])->links() }}
              @endif
          </ul>
      </div>
  </div>
