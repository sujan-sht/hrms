@section('css')
    <style>
        .form-container {
            margin: 0 auto;
        }

        .form-title {
            text-align: center;
            margin-bottom: 30px;
            text-decoration: underline;
        }

        .checkbox-group {
            margin-bottom: 20px;
        }

        .checkbox-group .form-check {
            margin-bottom: 10px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .declaration {
            font-size: 0.9rem;
            margin: 20px 0;
        }

        .signature-section {
            text-align: right;
            margin-top: 30px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
            margin-bottom: 5px;
        }
    </style>
@endsection
<div class="card-body staff-home-form" style="display: none;">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button"
                role="tab" aria-controls="nav-home" aria-selected="true">Basic Detail</button>
            <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button"
                role="tab" aria-controls="nav-profile" aria-selected="false">Document Attachment</button>
            <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact" type="button"
                role="tab" aria-controls="nav-contact" aria-selected="false">Nominee Detail</button>
            <button class="nav-link" id="property-detail-tab" data-toggle="tab" data-target="#property-detail"
                type="button" role="tab" aria-controls="property-detail" aria-selected="false">Property
                Detail</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row">
                <div class="col-lg-12 mb-3 p-4 form-container">
                    <div class="row">
                        <h5>Dear Sir/Madam,</h5>
                        <p>
                            With reference to the policy of the bank, I wish
                            to apply for Staff Home Loan/Additional Home
                            Loan of NPR
                            <input type="text" name="staff_home_loan" class="" />, equivalent to my
                            <input type="text" name="staff_home_loan_years" class="" /> years gross
                            salary required under the Staff Home Loan
                            facility of the Bank.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>1. Name of Staff:</label>
                            <input type="text" class="form-control" name="staff_name" placeholder="Enter Name" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>2. Designation:</label>
                            <input type="text" class="form-control" name="staff_designation"
                                placeholder="Enter Designation" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>3. Branch/Dept.:</label>
                            <input type="text" class="form-control" name="staff_branch"
                                placeholder="Enter Branch/Dept." />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>4. Initial Appointment Date:</label>
                            <input type="date" class="form-control" name="staff_appointment_date"
                                placeholder="Enter Initial Appointment Date" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>5. Date of Confirmation:</label>
                            <input type="date" class="form-control" name="staff_confirmation_date" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>6. Service Period:</label>
                            <input type="text" class="form-control" placeholder="Enter Service Period"
                                name="staff_service_period" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>7. Present Gross Salary:</label>
                            <input type="number" class="form-control" placeholder="Enter Present Gross Salary"
                                name="staff_present_gross_salary" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>8. Application Date:</label>
                            <input type="date" class="form-control" name="staff_application_date" />
                        </div>
                    </div>
                    <div class="purpose-section">
                        <label>Purpose of Loan:</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" name="staff_home_loan_purpose" type="checkbox"
                                    id="purpose1" value="" />
                                <label class="form-check-label" for="purpose1">a. To purchase a land</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="staff_home_loan_purpose" type="checkbox"
                                    id="purpose2" />
                                <label class="form-check-label" for="purpose2">b. To purchase land and construct
                                    house
                                    thereon</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="staff_home_loan_purpose" type="checkbox"
                                    id="purpose3" />
                                <label class="form-check-label" for="purpose3">c. To construct house in existing
                                    land</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="staff_home_loan_purpose" type="checkbox"
                                    id="purpose4" />
                                <label class="form-check-label" for="purpose4">d. To purchase already constructed
                                    house/apartment</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="staff_home_loan_purpose" type="checkbox"
                                    id="purpose5" />
                                <label class="form-check-label" for="purpose5">e. To maintain/additional
                                    construction
                                    on
                                    the property mortgaged under SHL </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="staff_home_loan_purpose" type="checkbox"
                                    id="purpose6" />
                                <label class="form-check-label" for="purpose6">f. To repay the loan taken from the
                                    financial institution or private
                                    source</label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="purpose7">g. Other: <input type="text"
                                        name="staff_home_loan_purpose_other"></label>
                            </div>
                        </div>
                        <p>
                            I solemnly declare that the given information
                            are true and accept all the prescribed rules and
                            regulations stated in the Staff Home Loan
                            Facility.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="row">
                <div class="col-lg-12 mb-3 p-r form-container">
                    <div class="documents-section mt-2">
                        <label><strong>Documents attached:</strong> (Please Tick (√) for Available
                            Document)</label>
                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc1" />
                                <label class="form-check-label" for="doc1">1. Photo copy of Lalpurja</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc2" />
                                <label class="form-check-label" for="doc2">2. Blue print map of land</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc3" />
                                <label class="form-check-label" for="doc3">3. Purchase deed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc4" />
                                <label class="form-check-label" for="doc4">4. Photo copy of Land Revenue
                                    receipt</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc5" />
                                <label class="form-check-label" for="doc5">5. Design/drawing of the proposed
                                    house
                                    prepared by an Architect/Engineer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc6" />
                                <label class="form-check-label" for="doc6">6. Approval of municipality or
                                    local
                                    development committee</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc7" />
                                <label class="form-check-label" for="doc7">7. House Tax</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc8" />
                                <label class="form-check-label" for="doc8">8. Valuation / estimation report
                                    from
                                    the
                                    authorized valuator of the Bank</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="doc9" />
                                <label class="form-check-label" for="doc9">9. Char Killa</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div class="row">
                <div class="col-lg-12 mb-3 p-r form-container">
                    <h5>Nominee:</h5>
                    <p class="mb-4">I hereby give the following details of the nominee(s) in the event of my death:
                    </p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Name:</label>
                                <input type="text" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Son/Wife/Daughter of:</label>
                                <input type="text" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Birth:</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Age:</label>
                                <input type="number" class="form-control" placeholder="Enter Age">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Relationship:</label>
                                <input type="text" class="form-control" placeholder="Enter Relationship">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Permanent Address:</label>
                                <input type="text" class="form-control" placeholder="Enter Permanent Address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact Address:</label>
                                <input type="text" class="form-control" placeholder="Enter Contact Address">
                            </div>
                        </div>
                    </div>
                    <!-- Alternate Nominee Section -->
                    <h5 class="mt-4">In the event of the death of above nominee(s)</h5>
                    <p>I appoint the following alternate nominee(s)</p>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Name:</label>
                                <input type="text" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Son/Wife/Daughter of:</label>
                                <input type="text" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date of Birth:</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Age:</label>
                                <input type="number" class="form-control" placeholder="Enter Age">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Relationship:</label>
                                <input type="text" class="form-control" placeholder="Enter Relationship">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Permanent Address:</label>
                                <input type="text" class="form-control" placeholder="Enter Permanent Address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact Address:</label>
                                <input type="text" class="form-control" placeholder="Enter Contact Address">
                            </div>
                        </div>
                    </div>

                    <!-- Declaration -->
                    <div class="declaration">
                        <p>I hereby declare that all information provided herein is true and I shall be liable for any
                            false
                            information and statement.</p>
                    </div>
                    <!-- Signature Section -->
                    {{-- <div class="signature-section">
                        <div class="signature-line"></div>
                        <div>Applicant's Signature</div>
                        <div class="form-group mt-3">
                            <label>Date:</label>
                            <input type="date" class="form-control" style="width: 200px; float: right;">
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="property-detail" role="tabpanel" aria-labelledby="property-detail-tab">
            <div class="row">
                <div class="col-lg-12 mb-3 p-r form-container">
                    <!-- Property Details Section -->
                    <h5 class="section-title">Property Detail</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Plot No:</label>
                                <input type="text" class="form-control" placeholder="Enter Plot No">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Area:</label>
                                <input type="text" class="form-control" placeholder="Enter Area">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Location:</label>
                                <input type="text" class="form-control" placeholder="Enter Location">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Owner:</label>
                                <input type="text" class="form-control" placeholder="Enter Owner">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Property Value:</label>
                                <input type="text" class="form-control" placeholder="Enter Property Value">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Property Valuation Date:</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Property Valuated By:</label>
                                <input type="text" class="form-control" placeholder="Enter Property Valuated By">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation Buttons -->
        <div class="navigation-buttons mt-3 text-right">
            <button class="btn btn-secondary prev-tab"> <i class="icon-backward2"></i> Previous</button>
            <button class="btn btn-success next-tab"><i class="icon-forward3"></i>Next</button>
        </div>
    </div>
</div>
