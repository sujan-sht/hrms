  <div class="card card-body mt-3">
      <div class="media align-items-center align-items-md-start flex-column flex-md-row">
          <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
              <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
          </a>
          <div class="media-body text-center text-md-left">
              <h6 class="media-title font-weight-semibold">My History</h6>
              All the Employee History will be listed below. You can view the data.
          </div>
      </div>
  </div>
  <div class="card card-body">
      <div class="table-responsive">
          <table class="table table-hover">
              <thead>
                  <tr class="text-light btn-slate">
                      <th>S.N</th>
                      <th>Employee Code</th>
                      <th>National ID</th>
                      <th>Full Name</th>
                      <th>Event</th>
                      <th>Event Date (AD)</th>
                      <th>Event Date (BS)</th>
                      <th>Effective Date (BS)</th>
                      <th>Unit</th>
                      <th>Sub-Function</th>
                      <th>Designation</th>
                      <th>Tenure (NOD)</th>
                  </tr>
              </thead>
              <tbody>

                  @if ($timelineModels->isNotEmpty())
                      @foreach ($timelineModels as $key => $value)
                          @php
                              $current_join__date = '';
                              if (
                                  optional($employeeModel->getUser)->user_type != 'super_admin' &&
                                  optional($employeeModel->getUser)->user_type != 'admin'
                              ) {
                                  $current_join__date = App\Helpers\DateTimeHelper::DateDiffInYearMonthDay(
                                      $value['date'],
                                      date('Y-m-d'),
                                  );
                              }
                          @endphp
                          <tr>
                              <td>{{ ++$key }}</td>
                              <td>{{ $employeeModel->employee_code }}</td>
                              <td>{{ $employeeModel->national_id }}</td>
                              <td>{{ $employeeModel->full_name }}</td>
                              <td>
                                  @php
                                      $rawDescription = $value['description'] ?? null;
                                      $descriptions = [];

                                      if ($rawDescription) {
                                          // Try to decode as JSON
                                          $decoded = json_decode($rawDescription, true);
                                          if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                              $descriptions = $decoded;
                                          } else {
                                              $descriptions = [$rawDescription]; // fallback to plain string
                                          }
                                      }
                                  @endphp
                                  @if (!empty($descriptions))
                                      <ul class="mb-0 pl-4 list-disc">
                                          @foreach ($descriptions as $desc)
                                              <li>{{ $desc }}</li>
                                          @endforeach
                                      </ul>
                                  @endif

                                  {{-- @if (!empty($descriptions) && is_array($descriptions))
                                      <ul class="mb-0 pl-4 list-disc">
                                          @foreach ($descriptions as $description)
                                              <li>{{ $description }}</li>
                                          @endforeach
                                      </ul>
                                  @endif --}}
                              </td>

                              <td>{{ getStandardDateFormat($value['date']) }}</td>
                              <td>{{ date_converter()->eng_to_nep_convert($value['date']) }}</td>
                              <td>{{ getStandardDateFormat(@$value->careerMobility->event->effective_date) }}</td>
                              <td>
                                  @if (!empty($value['branch']))
                                      @if ($value['branch']['old']['branch_id'] != null)
                                          @if (App\Modules\Unit\Entities\Unit::find($value['branch']['old']['branch_id']) != null)
                                              <p> {{ 'From ' . optional(App\Modules\Unit\Entities\Unit::find($value['branch']['old']['branch_id']))->name }}
                                              </p>
                                          @endif
                                      @endif
                                      @if ($value['branch']['new']['branch_id'] != null)
                                          @if (App\Modules\Unit\Entities\Unit::find($value['branch']['new']['branch_id']) != null)
                                              <p> {{ 'To ' . optional(App\Modules\Unit\Entities\Unit::find($value['branch']['new']['branch_id']))->name }}
                                              </p>
                                          @endif
                                      @endif
                                  @else
                                      -
                                  @endif
                              </td>
                              <td>
                                  @if (!empty($value['department']))
                                      @if ($value['department']['old']['department_id'] != null)
                                          @if (App\Modules\Setting\Entities\Department::find($value['department']['old']['department_id']) != null)
                                              <p> {{ 'From ' . optional(App\Modules\Setting\Entities\Department::find($value['department']['old']['department_id']))->title }}
                                              </p>
                                          @endif
                                      @endif
                                      @if ($value['department']['new']['department_id'] != null)
                                          @if (App\Modules\Setting\Entities\Department::find($value['department']['new']['department_id']) != null)
                                              <p> {{ 'To ' . optional(App\Modules\Setting\Entities\Department::find($value['department']['new']['department_id']))->title }}
                                              </p>
                                          @endif
                                      @endif
                                  @else
                                      -
                                  @endif
                              </td>
                              <td>
                                  @if (!empty($value['designation']))
                                      @if ($value['designation']['old']['designation_id'] != null)
                                          @if (App\Modules\Setting\Entities\Designation::find($value['designation']['old']['designation_id']) != null)
                                              <p> {{ 'From ' . optional(App\Modules\Setting\Entities\Designation::find($value['designation']['old']['designation_id']))->title }}
                                              </p>
                                          @endif
                                      @endif
                                      @if ($value['designation']['new']['designation_id'] != null)
                                          @if (App\Modules\Setting\Entities\Designation::find($value['designation']['new']['designation_id']) != null)
                                              <p> {{ 'To ' . optional(App\Modules\Setting\Entities\Designation::find($value['designation']['new']['designation_id']))->title }}
                                              </p>
                                          @endif
                                      @endif
                                  @else
                                      -
                                  @endif
                              </td>
                              <td>
                                  {{ $current_join__date }}
                              </td>

                          </tr>
                      @endforeach
                  @else
                  @endif


              </tbody>
          </table>
      </div>

      <div class="col-12">
          <span class="float-right pagination align-self-end mt-3">
          </span>
      </div>
  </div>
