<div class="card">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-12 py-3">
                <h3 class="mb-0">Gender</h3>
                <span class="text-muted">Employee based on gender</span>
            </div>
            <div class="col-md-6 text-center py-2">
                <div style="background: linear-gradient(to top,#562FF5 0%,#8C32DE {{ $malePercentage }}%,#dedede {{ $malePercentage }}%,#dedede 100%);">
                    <img src="{{ asset('admin/male_chart.png') }}" width="100%">
                </div>
                <div class="pt-3">
                    <h1 class="mb-0" style="color: #562FF5;">{{ $malePercentage }} %</h1>
                    <span class="text-muted">MALE <span class="badge" style="color: #562FF5;">({{ $maleCount }})</span></span>

                </div>
            </div>
            <div class="col-md-6 text-center py-2">
                <div style="background: linear-gradient(to top,#FF279C 0%,#FF789E {{ $femalePercentage }}%,#dedede {{ $femalePercentage }}%,#dedede 100%);">
                    <img src="{{ asset('admin/female_chart.png') }}" width="100%">
                </div>
                <div class="pt-3">
                    <h1 class="mb-0" style="color: #FF279C;">{{ $femalePercentage }} %</h1>
                    <span class="text-muted">FEMALE <span class="badge" style="color: #FF279C;">({{ $femaleCount }})</span></span>
                </div>
            </div>
        </div>
    </div>
</div>
