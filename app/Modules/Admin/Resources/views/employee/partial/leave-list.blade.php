<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <a href="" class="btn rounded-pill btn-icon btn-sm">
                    <img src="{{ optional($model->employeeModel)->getImage() }}"
                        class="rounded-circle" width="40" height="40"
                        alt="">
                </a>
            </div>
            <div>
                <a href=""
                    class="text-body font-weight-semibold letter-icon-title">{{ optional($model->employeeModel)->getFullName() }}</a>
                <div class="text-muted font-size-sm">
                    {{ optional(optional($model->employeeModel)->department)->title }}
                </div>

            </div>
        </div>
    </td>
    <td>
        <h6 class="font-weight-semibold mb-0 text-right">
            {{ $model->getLeaveKind() }}

        </h6>
    </td>
</tr>
