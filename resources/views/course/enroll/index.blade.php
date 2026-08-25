@extends('layouts.master')

@section('title', 'Enroll & Register - ' . $course->title . ' - MIFFA')

@section('content')
<!-- Start Breadcrumb -->
<div class="breadcrumb-area text-center bg-gray-gradient-secondary">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>Course Registration</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li><a href="{{ route('courses.show', $course->id) }}">{{ $course->title }}</a></li>
                        <li class="active">Enrollment</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

<!-- Start Enrollment Form Area -->
<div class="enrollment-area py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center g-4">
            <!-- Left Side: Enrollment Form -->
            <div class="col-lg-7 col-md-10">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold">Student Registration</h3>
                                <p class="text-muted small mb-0">Fill in your details to create your account and complete enrollment</p>
                            </div>
                        </div>

                        {{-- Alert Messages --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('enroll.store', $course->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Full Name (Editable) -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter your full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Address (Editable) -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="name@example.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Account Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-key"></i></span>
                                    <input type="password" 
                                           class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Create a password (min. 8 characters)"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-phone"></i></span>
                                    <input type="tel" 
                                           class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="09123456789" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gender & Membership Status -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-venus-mars"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="membership_status" class="form-label fw-semibold">MIFFA Membership <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-id-card"></i></span>
                                        <select class="form-select border-start-0 ps-0 @error('membership_status') is-invalid @enderror" id="membership_status" name="membership_status" required>
                                            <option value="" disabled {{ old('membership_status') ? '' : 'selected' }}>Select Status</option>
                                            <option value="member" {{ old('membership_status') == 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="non-member" {{ old('membership_status') == 'non-member' ? 'selected' : '' }}>Non-Member</option>
                                        </select>
                                        @error('membership_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Formatted Myanmar NRC Input -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">NRC / Identity Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fas fa-address-card"></i></span>
                                    
                                    <!-- State Number -->
                                    <select class="form-select @error('nrc_state') is-invalid @enderror" id="nrc_state" name="nrc_state" style="max-width: 90px;" required>
                                        <option value="" disabled {{ old('nrc_state') ? '' : 'selected' }}>State</option>
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}" {{ old('nrc_state') == $i ? 'selected' : '' }}>{{ $i }}/</option>
                                        @endfor
                                    </select>

                                    <!-- Township / District Code -->
                                    <select class="form-select @error('nrc_district') is-invalid @enderror" id="nrc_district" name="nrc_district" required>
                                        <option value="" disabled selected>District</option>
                                    </select>

                                    <!-- Type -->
                                    <select class="form-select @error('nrc_type') is-invalid @enderror" id="nrc_type" name="nrc_type" style="max-width: 100px;" required>
                                        <option value="(N)" {{ old('nrc_type', '(N)') == '(N)' ? 'selected' : '' }}>(N)</option>
                                        <option value="(P)" {{ old('nrc_type') == '(P)' ? 'selected' : '' }}>(P)</option>
                                        <option value="(E)" {{ old('nrc_type') == '(E)' ? 'selected' : '' }}>(E)</option>
                                        <option value="(NRA)" {{ old('nrc_type') == '(NRA)' ? 'selected' : '' }}>(NRA)</option>
                                    </select>

                                    <!-- 6-Digit Number -->
                                    <input type="text" 
                                           class="form-control @error('nrc_number') is-invalid @enderror" 
                                           id="nrc_number" 
                                           name="nrc_number" 
                                           value="{{ old('nrc_number') }}" 
                                           placeholder="123456" 
                                           maxlength="6"
                                           pattern="[0-9]{6}"
                                           required>
                                </div>
                                @if($errors->has('nrc_state') || $errors->has('nrc_district') || $errors->has('nrc_type') || $errors->has('nrc_number'))
                                    <div class="text-danger small mt-1">
                                        Please provide a valid NRC format (e.g. 12/YAGANA(N)123456).
                                    </div>
                                @endif
                            </div>

                            <!-- Company & Job Title -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="company" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-building"></i></span>
                                        <input type="text" 
                                               class="form-control border-start-0 ps-0 @error('company') is-invalid @enderror" 
                                               id="company" 
                                               name="company" 
                                               value="{{ old('company') }}" 
                                               placeholder="Company Name" 
                                               required>
                                        @error('company')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="job_title" class="form-label fw-semibold">Job Title / Position <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-briefcase"></i></span>
                                        <input type="text" 
                                               class="form-control border-start-0 ps-0 @error('job_title') is-invalid @enderror" 
                                               id="job_title" 
                                               name="job_title" 
                                               value="{{ old('job_title') }}" 
                                               placeholder="e.g. Logistics Manager" 
                                               required>
                                        @error('job_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Passport Photo Upload -->
                            <div class="mb-4">
                                <label for="passport_photo" class="form-label fw-semibold">Passport Photo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-file-image"></i></span>
                                    <input type="file" 
                                           class="form-control border-start-0 ps-0 @error('passport_photo') is-invalid @enderror" 
                                           id="passport_photo" 
                                           name="passport_photo" 
                                           accept="image/*" 
                                           required>
                                    @error('passport_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Upload a clear passport-style photo (JPEG, PNG / max 2MB).</div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-gradient w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-check-circle"></i> Complete Registration & Enrollment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: Course Summary Card -->
            <div class="col-lg-4 col-md-10">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    @if(!empty($course->image_path))
                        <img src="{{ asset($course->image_path) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                    @endif
                    <div class="card-body p-4">
                        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small mb-2">
                            {{ $course->category?->name ?? 'General' }}
                        </span>
                        <h4 class="fw-bold text-dark mb-3">{{ $course->title }}</h4>

                        <div class="bg-light p-3 rounded-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted"><i class="fas fa-barcode me-2"></i>Course Code</span>
                                <span class="fw-bold">{{ $course->code ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted"><i class="fas fa-clock me-2"></i>Duration</span>
                                <span class="fw-bold">{{ $course->hour ?? 0 }} Hours</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <span class="fw-bold text-dark">Total Fee</span>
                                <span class="fs-4 fw-bold text-success">
                                    {{ isset($course->price) && $course->price > 0 ? number_format($course->price) . ' MMK' : 'Free' }}
                                </span>
                            </div>
                        </div>

                        <ul class="list-unstyled text-muted small mb-0">
                            <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i> Instant account activation</li>
                            <li class="mb-2"><i class="fas fa-certificate text-success me-2"></i> MIFFA certification upon completion</li>
                            <li><i class="fas fa-headset text-success me-2"></i> Direct course access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Enrollment Form Area -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stateSelect = document.getElementById('nrc_state');
    const districtSelect = document.getElementById('nrc_district');
    
    const districtOptions = {
        "1": ["ကပတ", "ကမတ", "ခပန", "ခလဖ", "ဆဒန", "ဆပရ", "ဆဘန", "တဆလ", "တနန", "ဒဖယ", "နမန", "ပတအ", "ပနဒ", "ပဝန", "ဖကန", "ဗမန", "မကတ", "မကန", "မခဘ", "မစန", "မညန", "မမန", "မလန", "ရှကန", "ရှဗယ", "လဂျန", "ဟပန", "အဂျယ", "၀မန"],
        "2": ["ဒမဆ", "ဖဆန", "ဖရဆ", "ဘလခ", "မစန", "ရတန", "ရသန", "လကန"],
        "3": ["ကကရ", "ကဆက", "ကဒတ", "ကဒန", "ကမမ", "စကလ", "ပကန", "ဖပန", "ဘဂလ", "ဘသဆ", "ဘအန", "မဝတ", "ရသန", "လဘန", "လသန", "ဝလမ", "သတက", "သတန"],
        "4": ["ကခန", "ကပလ", "ဆမန", "တဇန", "တတန", "ထတလ", "ပလဝ", "ဖလန", "မတန", "မတပ", "ရခဒ", "ရဇန", "ဟခန"],
        "5": ["ကနန", "ကဘလ", "ကမန", "ကလတ", "ကလထ", "ကလန", "ကလဝ", "ကသန", "ခတန", "ခပန", "ခဥတ", "ခဥန", "ငဇန", "စကန", "ဆလက", "တဆန", "တမန", "ထခန", "ဒပယ", "နယန", "ပလန", "ပလဘ", "ဖပန", "ဗမန", "ဘတလ", "မကန", "မမတ", "မမန", "မရန", "မလန", "ယမပ", "ရဘန", "ရဥန", "လရန", "လဟန", "ဝလန", "ဝသန", "ဟမလ", "အတန", "အရတ"],
        "6": ["ကစန", "ကရရ", "ကလအ", "ကသန", "ခမန", "တသရ", "ထဝန", "ပလတ", "ပလန", "ဘပန", "မတန", "မမန", "ရဖြန", "လလန", "သရခ"],
        "7": ["ကကန", "ကတခ", "ကပက", "ကဝန", "ဇကန", "ညလပ", "တငန", "ထတပ", "ဒဥန", "နတလ", "ပခတ", "ပခန", "ပတဆ", "ပတတ", "ပတန", "ပနက", "ပမန", "ဖမန", "မညန", "မလန", "ရကန", "ရတန", "ရတရှ", "လပတ", "ဝမန", "သကန", "သဆန", "သနပ", "သဝတ", "အတန", "အဖန"],
        "8": ["ကထန", "ကမန", "ခမန", "ဂဂန", "ငဖန", "စတရ", "စလန", "ဆပဝ", "ဆဖန", "ဆမန", "တတက", "ထလန", "နမန", "ပခက", "ပဖြန", "ပမန", "မကန", "မတန", "မထန", "မဘန", "မမန", "မလန", "မသန", "ရစက", "ရနခ", "သရန", "အလန"],
        "9": ["ကဆန", "ကပတ", "ခမစ", "ခအစ", "ငဇန", "ငသရ", "စကတ", "စကန", "ဇဗသ", "ဇယသ", "ညဥန", "တကတ", "တကန", "တတဥ", "တသန", "ဒခသ", "နထက", "ပကခ", "ပဗသ", "ပဘန", "ပမန", "ပသက", "ပဥလ", "မကန", "မခန", "မတရ", "မထလ", "မမန", "မလန", "မသန", "မဟမ", "ရမသ", "လဝန", "ဝတန", "သစန", "သပက", "အမစ", "အမရ", "ဥတသ"],
        "10": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
        "11": ["ကတန", "ကတလ", "ကဖန", "ဂမန", "စတန", "တကန", "တပဝ", "ပဏတ", "ပတန", "ဗတထ", "ဘသတ", "မတန", "မပတ", "မပန", "မအတ", "မအန", "မဥန", "ရဗန", "ရသတ", "သတန", "အမန"],
        "12": ["ကကက", "ကခက", "ကတတ", "ကတန", "ကမတ", "ကမန", "ကမရ", "ခရန", "စခန", "ဆကခ", "ဆကန", "တကန", "တတထ", "တတန", "တမန", "ထတပ", "ဒဂဆ", "ဒဂတ", "ဒဂန", "ဒဂမ", "ဒဂရ", "ဒပန", "ဒလန", "ပဇတ", "ပဘတ", "ဗဟန", "မဂတ", "မဂဒ", "မဘန", "မရက", "ရကန", "ရပသ", "လကန", "လမတ", "လမန", "လသန", "လသယ", "သကတ", "သခန", "သဃက", "သလန", "အစန", "အလန", "ဥကတ", "ဥကန", "ဥကမ"],
        "13": ["ကခန", "ကတတ", "ကတန", "ကတလ", "ကမဆ", "ကမန", "ကရန", "ကလတ", "ကလဒ", "ကလန", "ကလဖ", "ကသန", "ကဟန", "ခမန", "ခရဟ", "ခလန", "ဆဆန", "ဆဖန", "ညရန", "တကန", "တခလ", "တမည", "တယန", "တလန", "နကန", "နခတ", "နခန", "နခဝ", "နဆန", "နတန", "နတယ", "နဖန", "နမတ", "နဝန", "ပခန", "ပဆန", "ပတယ", "ပပက", "ပယန", "ပလတ", "ပလန", "ပဝန", "ဖခန", "မကန", "မခန", "မငန", "မဆတ", "မဆန", "မတတ", "မတန", "မနန", "မပန", "မဖန", "မဗတ", "မဘန", "မမဆ", "မမတ", "မမန", "မယန", "မရတ", "မရန", "မလန", "မဟရ", "ယလန", "ရငန", "ရစန", "ရဖန", "လကတ", "လခတ", "လခန", "လရန", "လလန", "လဟန", "သနန", "သပန", "ဟတန", "ဟပတ", "ဟပန", "အခန", "အတန"],
        "14": ["ကကထ", "ကကန", "ကခန", "ကပန", "ကလန", "ငဆန", "ငပတ", "ငရက", "ငသခ", "ငသယ", "ဇလန", "ညတန", "ဒဒရ", "ဒနဖြ", "ပစလ", "ပတန", "ပသန", "ဖပန", "ဘကလ", "မမက", "မမန", "မအန", "မအပ", "ရကန", "ရသယ", "လပတ", "လမန", "ဝခမ", "သပန", "ဟကကျ", "ဟသတ", "အဂပ", "အမတ", "အမန"]
    };

    const oldState = "{{ old('nrc_state') }}";
    const oldDistrict = "{{ old('nrc_district') }}";

    function populateDistricts(selectedState, preselectDistrict = '') {
        districtSelect.innerHTML = '<option value="" disabled selected>District</option>';

        if (selectedState && districtOptions[selectedState]) {
            districtOptions[selectedState].forEach(function (district) {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;

                if (district === preselectDistrict) {
                    option.selected = true;
                }

                districtSelect.appendChild(option);
            });
        }
    }

    stateSelect.addEventListener('change', function () {
        populateDistricts(this.value);
    });

    if (oldState) {
        stateSelect.value = oldState;
        populateDistricts(oldState, oldDistrict);
    }
});
</script>
@endsection