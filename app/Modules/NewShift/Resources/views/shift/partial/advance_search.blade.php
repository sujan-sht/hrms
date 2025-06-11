<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Title</label>
                        @php
                            if(isset($_GET['title'])) {
                                $titleValue = $_GET['title'];
                            } else {
                                $titleValue = null;
                            }
                        @endphp
                        {!! Form::text('title', $value=$titleValue, ['placeholder'=>'Title','class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-1" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>

                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
            </div>

        </form>

    </div>
</div>
