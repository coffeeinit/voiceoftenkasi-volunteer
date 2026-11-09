<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration - Voice of Tenkasi Foundation</title>
    <link rel="stylesheet" href="assets/css/registration.css">

 
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="logo.png" alt="Voice of Tenkasi Logo" class="logo">
            <h1>🤝 Join Our Mission</h1>
            <p>Voice of Tenkasi Foundation - Volunteer Registration</p>
        </div>

        <div class="form-container">
            <div class="success-message" id="successMessage" style="display: none;">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Registration Successful!</h3>
                <p>Thank you for joining Voice of Tenkasi Foundation. Your registration ID is: <strong id="registrationId"></strong></p>
                <p>We will contact you soon with further details.</p>
                <div class="success-actions">
                    <a id="downloadPdfBtn" href="#" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <a href="profile.php" class="btn btn-secondary">
                        <i class="fas fa-user"></i> View Profile
                    </a>
                </div>
            </div>

            <div class="error-message" id="errorMessage" style="display: none;">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h3>Registration Error</h3>
                <p id="errorText"></p>
            </div>

            <form id="volunteerForm" enctype="multipart/form-data">
                <!-- Personal Information Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" id="firstName" name="first_name" class="form-control" required>
                            <div class="error-message">First name is required</div>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="last_name" class="form-control" required>
                            <div class="error-message">Last name is required</div>
                        </div>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="dob">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="dob" name="dob" class="form-control" required placeholder="DD/MM/YYYY" max="<?php echo date('Y-m-d'); ?>" min="1900-01-01">
                            <div class="error-message">Date of Birth is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control">
                            <div class="error-message">Please enter a valid email address</div>
                        </div>
                        <div class="form-group">
                            <label for="contactNo">Contact Number <span class="required">*</span></label>
                            <input type="tel" id="contactNo" name="contact_no" class="form-control" pattern="[0-9]{10}" required>
                            <div class="error-message">Please enter a valid 10-digit contact number</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="whatsappNo">WhatsApp Number <span class="required">*</span></label>
                            <input type="tel" id="whatsappNo" name="whatsapp_no" class="form-control" pattern="[0-9]{10}" required>
                            <div class="error-message">Please enter a valid 10-digit WhatsApp number</div>
                        </div>
                        <div class="form-group">
                            <label for="bloodGroup">Blood Group <span class="required">*</span></label>
                            <select id="bloodGroup" name="blood_group" class="form-control" required>
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                            <div class="error-message">Please select your blood group</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="qualification">Qualification <span class="required">*</span></label>
                            <input type="text" id="qualification" name="qualification" class="form-control" required>
                            <div class="error-message">Qualification is required</div>
                        </div>
                        <div class="form-group">
                            <label for="occupation">Occupation <span class="required">*</span></label>
                            <input type="text" id="occupation" name="occupation" class="form-control" required>
                            <div class="error-message">Occupation is required</div>
                        </div>
                    </div>
                </section>
                <!-- Address Information Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Address Information
                    </h2>
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="address1">Address Line 1 <span class="required">*</span></label>
                            <input type="text" id="address1" name="address1" class="form-control" required>
                            <div class="error-message">Address Line 1 is required</div>
                        </div>
                    </div>
                    <div class="form-row single">
                        <div class="form-group">
                            <label for="address2">Address Line 2</label>
                            <input type="text" id="address2" name="address2" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City <span class="required">*</span></label>
                            <input type="text" id="city" name="city" class="form-control" required>
                            <div class="error-message">City is required</div>
                        </div>
                        <div class="form-group">
                            <label for="state">State <span class="required">*</span></label>
                            <input type="text" id="state" name="state" class="form-control" required>
                            <div class="error-message">State is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="zip">ZIP Code <span class="required">*</span></label>
                            <input type="text" id="zip" name="zip" class="form-control" required>
                            <div class="error-message">ZIP code is required</div>
                        </div>
                    </div>
                </section>
                <!-- Travel and Preferred Locations Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-car"></i>
                        Travel & Preferred Locations
                    </h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="travelKm">How much km you can travel from your current location? <span class="required">*</span></label>
                            <input type="number" id="travelKm" name="travel_km" class="form-control" required min="0" placeholder="Enter distance in km">
                            <div class="error-message">Please enter how far you can travel</div>
                        </div>
                        <div class="form-group">
                            <label for="preferredLocationsInput">Which place(s) are you willing to work? <span class="required">*</span></label>
                            <div id="locationsTagInput" class="tag-input-container" style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;min-height:48px;border:2px solid #ddd;border-radius:10px;padding:8px 12px;background:#f8f9fa;">
                                <input type="text" id="preferredLocationsInput" class="form-control" style="border:none;box-shadow:none;width:auto;min-width:120px;flex:1 0 120px;background:transparent;outline:none;" placeholder="Area, Tenkasi, Vasudevanallur, Puliyangudi, etc">
                    </div>
                                <input type="hidden" name="preferred_locations" id="preferredLocationsHidden" required>
                            <div class="error-message">Please enter at least one preferred location</div>
                        </div>
                    </div>
                </section>
                <!-- Availability Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-calendar"></i>
                        Availability
                    </h2>
                    <div class="form-group">
                        <label>Days Available <span class="required">*</span></label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="monday" name="days[]" value="Monday">
                                <label for="monday">Monday</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="tuesday" name="days[]" value="Tuesday">
                                <label for="tuesday">Tuesday</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="wednesday" name="days[]" value="Wednesday">
                                <label for="wednesday">Wednesday</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="thursday" name="days[]" value="Thursday">
                                <label for="thursday">Thursday</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="friday" name="days[]" value="Friday">
                                <label for="friday">Friday</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="saturday" name="days[]" value="Saturday">
                                <label for="saturday">Saturday</label>
                            </div>
                                <div class="checkbox-item">
                                <input type="checkbox" id="sunday" name="days[]" value="Sunday">
                                <label for="sunday">Sunday</label>
                                </div>
                        </div>
                        <div class="error-message">Please select at least one day</div>
                    </div>
                </section>
                <!-- Interests Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-heart"></i>
                        Areas of Interest
                    </h2>
                    <div class="form-group">
                        <label>Select Your Areas of Interest <span class="required">*</span></label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="education" name="interests[]" value="Education">
                                <label for="education">Education</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="healthcare" name="interests[]" value="Healthcare">
                                <label for="healthcare">Healthcare</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="environment" name="interests[]" value="Environment">
                                <label for="environment">Environment</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="community" name="interests[]" value="Community Development">
                                <label for="community">Community Development</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="elderly" name="interests[]" value="Elderly Care">
                                <label for="elderly">Elderly Care</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="children" name="interests[]" value="Children Welfare">
                                <label for="children">Children Welfare</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="disaster" name="interests[]" value="Disaster Relief">
                                <label for="disaster">Disaster Relief</label>
                            </div>
                                <div class="checkbox-item">
                                <input type="checkbox" id="awareness" name="interests[]" value="Awareness Programs">
                                <label for="awareness">Awareness Programs</label>
                                </div>
                        </div>
                        <div class="error-message">Please select at least one area of interest</div>
                    </div>
                </section>
                <!-- Motivation Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-lightbulb"></i>
                        Motivation
                    </h2>
                    <div class="form-row single">
                    <div class="form-group">
                        <label for="motto">Why do you want to volunteer with us? <span class="required">*</span></label>
                            <textarea id="motto" name="motto" class="form-control" rows="4" required placeholder="Share your motivation and what drives you to volunteer..."></textarea>
                        <div class="error-message">Please share your motivation</div>
                        </div>
                    </div>
                </section>
                <!-- Social Media Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-share-alt"></i>
                        Social Media
                    </h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="instagram">Instagram</label>
                            <input type="text" id="instagram" name="instagram" class="form-control" placeholder="Instagram profile link or username">
                        </div>
                        <div class="form-group">
                            <label for="facebook">Facebook</label>
                            <input type="text" id="facebook" name="facebook" class="form-control" placeholder="Facebook profile link or username">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="twitter">Twitter</label>
                            <input type="text" id="twitter" name="twitter" class="form-control" placeholder="Twitter profile link or username">
                        </div>
                        <div class="form-group">
                            <label for="linkedin">LinkedIn</label>
                            <input type="text" id="linkedin" name="linkedin" class="form-control" placeholder="LinkedIn profile link or username">
                        </div>
                    </div>
                    <div class="form-row single">
                    <div class="form-group">
                            <label>Are you Intrested to work in the Social Medias of Our Foundation ? <span class="required">*</span></label>
                            <div style="display:flex;gap:20px;align-items:center;">
                                <label><input type="radio" name="social_repost" value="Yes" required> Yes</label>
                                <label><input type="radio" name="social_repost" value="Maybe" required> Maybe</label>
                                <label><input type="radio" name="social_repost" value="No" required> No</label>
                                </div>
                            <div class="error-message">Please select an option</div>
                        </div>
                    </div>
                </section>
                <!-- Willing to Donate Blood Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-tint"></i>
                        Willing to Donate Blood?
                    </h2>
                    <div class="form-row single">
                    <div class="form-group">
                        <label for="donate">Willing to Donate Blood? <span class="required">*</span></label>
                            <select id="donate" name="donate" class="form-control" required>
                                <option value="">Select Option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <div class="error-message">Please select your blood donation preference</div>
                        </div>
                    </div>
                </section>
                <!-- Photo Upload Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-camera"></i>
                        Photo Upload
                    </h2>
                    <div class="form-row single">
                    <div class="form-group">
                        <label for="volunteerPhoto">Upload Your Photo <span class="required">*</span></label>
                            <div class="file-upload">
                                <input type="file" id="volunteerPhoto" name="volunteer_photo" accept="image/*" required>
                            <div class="file-upload-label">
                                    <span>📷</span>
                                <span>Click to upload photo or drag and drop</span>
                                </div>
                            </div>
                            <img id="photoPreview" class="photo-preview" alt="Preview">
                            <div class="error-message">Please upload a valid image file (JPEG, PNG, GIF)</div>
                        </div>
                    </div>
                </section>
                <!-- Consent Section -->
                <section class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-check-circle"></i>
                        Consent & Terms
                    </h2>
                    <div class="consent-section">
                        <div class="consent-item">
                            <input type="checkbox" id="consent" name="consent" required>
                            <label for="consent">I consent to the collection and use of my personal information as described in the privacy policy. I understand that my information will be used to facilitate my volunteer activities with Voice of Tenkasi Foundation.</label>
                        </div>
                        <div class="consent-item">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to the terms and conditions of volunteering with Voice of Tenkasi Foundation. I understand my responsibilities and commit to fulfilling them to the best of my ability.</label>
                        </div>
                    </div>
                </section>
                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        Register as Volunteer
                    </button>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                        Processing...
                    </div>
                </div>
            </form>
        </div>
    </div>

    <footer  class="form-container" style="margin-top:40px; background:#222; color:#fff; text-align:center; border-radius:0 0 20px 20px; font-size:15px;">
    <div style="margin-bottom:10px;">
        <!-- Instagram -->
        <a href="https://www.instagram.com/voice_of_tamilnadu/#" target="_blank" style="margin:0 10px; color:#fff; text-decoration:none; display:inline-block;">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 448 512" style="vertical-align:middle;"><path d="M224,202.66A53.34,53.34,0,1,0,277.34,256,53.38,53.38,0,0,0,224,202.66Zm124.71-41a54.49,54.49,0,0,0-30.83-30.83C293.39,120,224,120,224,120s-69.39,0-93.88,10.84a54.49,54.49,0,0,0-30.83,30.83C88.44,181.22,88,224,88,224s.44,42.78,11.29,66.33a54.49,54.49,0,0,0,30.83,30.83C154.61,336,224,336,224,336s69.39,0,93.88-10.84a54.49,54.49,0,0,0,30.83-30.83C359.56,266.78,360,224,360,224S359.56,181.22,348.71,161.71ZM224,306a82,82,0,1,1,82-82A82.09,82.09,0,0,1,224,306Zm85.33-148a19.2,19.2,0,1,1,19.2-19.2A19.22,19.22,0,0,1,309.33,158Z"/></svg>
            <span style="font-size:0;">Instagram</span>
        </a>

        <!-- Facebook -->
        <a href="https://www.facebook.com/voiceoftenkasi/" target="_blank" style="margin:0 10px; color:#fff; text-decoration:none; display:inline-block;">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 320 512" style="vertical-align:middle;"><path d="M279.14 288l14.22-92.66h-88.91V127.37c0-25.35 12.42-50.06 52.24-50.06H293V6.26S259.77 0 225.36 0c-73.51 0-121.29 44.38-121.29 124.72v70.62H22.89V288h81.18v224h100.17V288z"/></svg>
            <span style="font-size:0;">Facebook</span>
        </a>

        <!-- YouTube -->
        <a href="https://www.youtube.com/channel/UCDgbbUwotuzOUJY4y3oN18w" target="_blank" style="margin:0 10px; color:#fff; text-decoration:none; display:inline-block;">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 576 512" style="vertical-align:middle;"><path d="M549.65 124.08c-6.28-23.64-24.79-42.15-48.43-48.43C456.77 64 288 64 288 64S119.23 64 74.78 75.65c-23.64 6.28-42.15 24.79-48.43 48.43C16 168.52 16 256 16 256s0 87.48 10.35 131.92c6.28 23.64 24.79 42.15 48.43 48.43C119.23 448 288 448 288 448s168.77 0 213.22-11.65c23.64-6.28 42.15-24.79 48.43-48.43C560 343.48 560 256 560 256s0-87.48-10.35-131.92zM232 336V176l142 80-142 80z"/></svg>
            <span style="font-size:0;">YouTube</span>
        </a>
    </div>
    <div style="margin-bottom:6px;">© voiceoftenkasi.org 2025</div>
    <div>For any queries contact <a href="tel:+918778200402" style="color:#4dd0e1; text-decoration:none;">+91 87782 00402</a> or <a style="color:#4dd0e1; text-decoration:none;" href="https://wa.me/+918925495641">WhatsApp</a> your issues.</div>
    </footer>

    <button id="fabTop" title="Scroll to Top" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>
    <div id="toast"></div>

    <script>
        // Form elements
        const form = document.getElementById('volunteerForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');
        const photoInput = document.getElementById('volunteerPhoto');
        const photoPreview = document.getElementById('photoPreview');

        // Photo preview functionality
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                    photoInput.parentElement.classList.add('has-file');
                };
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        function validateForm() {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Clear previous errors
            document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
            
            requiredFields.forEach(field => {
                if (field.type === 'checkbox') {
                    if (field.name === 'days[]') {
                        const dayCheckboxes = form.querySelectorAll('input[name="days[]"]:checked');
                        if (dayCheckboxes.length === 0) {
                            field.closest('.form-group').querySelector('.error-message').style.display = 'block';
                            isValid = false;
                        }
                    } else if (field.name === 'interests[]') {
                        const interestCheckboxes = form.querySelectorAll('input[name="interests[]"]:checked');
                        if (interestCheckboxes.length === 0) {
                            field.closest('.form-group').querySelector('.error-message').style.display = 'block';
                            isValid = false;
                        }
                    } else if (!field.checked) {
                        field.closest('.form-group').querySelector('.error-message').style.display = 'block';
                        isValid = false;
                    }
                } else if (!field.value.trim()) {
                    field.classList.add('error');
                    field.closest('.form-group').querySelector('.error-message').style.display = 'block';
                    isValid = false;
                }
            });
            
            // Validate phone numbers
            const contactNo = document.getElementById('contactNo');
            const whatsappNo = document.getElementById('whatsappNo');
            const phoneRegex = /^[0-9]{10}$/;
            
            if (contactNo.value && !phoneRegex.test(contactNo.value)) {
                contactNo.classList.add('error');
                contactNo.closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            
            if (whatsappNo.value && !phoneRegex.test(whatsappNo.value)) {
                whatsappNo.classList.add('error');
                whatsappNo.closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            
            // Validate email
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value && !emailRegex.test(email.value)) {
                email.classList.add('error');
                email.closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            
            // In validateForm, add check for social_repost
            const repostRadios = form.querySelectorAll('input[name="social_repost"]');
            if (![...repostRadios].some(r => r.checked)) {
                repostRadios[0].closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            
            // In validateForm, add checks for travel_km and preferred_locations
            const travelKm = document.getElementById('travelKm');
            if (!travelKm.value || isNaN(travelKm.value) || Number(travelKm.value) < 0) {
                travelKm.classList.add('error');
                travelKm.closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            const preferredLocationsHidden = document.getElementById('preferredLocationsHidden');
            if (!preferredLocationsHidden.value || preferredLocationsHidden.value.split(',').filter(Boolean).length === 0) {
                const tagContainer = document.getElementById('locationsTagInput');
                tagContainer.style.borderColor = '#e74c3c';
                tagContainer.closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            
            // In validateForm, add DOB validation
            const dob = document.getElementById('dob');
            // Accept both yyyy-mm-dd (from input type=date) and dd/mm/yyyy (legacy)
            const dobVal = dob.value.trim();
            const isDateInput = dob.type === 'date';
            let dobValid = false;
            if (isDateInput) {
                // Accept yyyy-mm-dd (from calendar picker)
                dobValid = /^\d{4}-\d{2}-\d{2}$/.test(dobVal);
            } else {
                // Accept dd/mm/yyyy (legacy text input)
                dobValid = /^\d{2}\/\d{2}\/\d{4}$/.test(dobVal);
            }
            if (!dobVal || !dobValid) {
                dob.classList.add('error');
                dob.closest('.form-group').querySelector('.error-message').textContent = isDateInput
                    ? 'Please select your Date of Birth'
                    : 'Please enter DOB in DD/MM/YYYY format';
                dob.closest('.form-group').querySelector('.error-message').style.display = 'block';
                isValid = false;
            }
            
            return isValid;
        }

        // Show loading state
        function showLoading() {
            submitBtn.style.display = 'none';
            loadingSpinner.style.display = 'block';
        }

        // Hide loading state
        function hideLoading() {
            submitBtn.style.display = 'block';
            loadingSpinner.style.display = 'none';
        }

        // --- Local save/restore logic for new registrations ---
        function saveFormToLocal() {
            const formData = {};
            form.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.type === 'checkbox') {
                    if (!formData[el.name]) formData[el.name] = [];
                    if (el.checked) formData[el.name].push(el.value);
                } else if (el.type === 'radio') {
                    if (el.checked) formData[el.name] = el.value;
                } else {
                    formData[el.name] = el.value;
                }
            });
            localStorage.setItem('vot_volunteer_form', JSON.stringify(formData));
        }
        function restoreFormFromLocal() {
            const saved = localStorage.getItem('vot_volunteer_form');
            if (!saved) return;
            try {
                const data = JSON.parse(saved);
                Object.keys(data).forEach(name => {
                    const value = data[name];
                    const els = form.querySelectorAll(`[name='${name}']`);
                    els.forEach(el => {
                        if (el.type === 'checkbox') {
                            el.checked = Array.isArray(value) && value.includes(el.value);
                        } else if (el.type === 'radio') {
                            el.checked = el.value === value;
                        } else {
                            el.value = value;
                        }
                    });
                });
                // Custom multi-select update
                updateSelectedLocations();
            } catch {}
        }
        // Save on every input change
        form.addEventListener('input', saveFormToLocal);
        // Restore on load (if not edit mode)
        restoreFormFromLocal();
        // Clear localStorage on success or edit
        function clearLocalForm() {
            localStorage.removeItem('vot_volunteer_form');
        }
        // Show success message
        function showSuccess(registrationId) {
            clearLocalForm();
            document.getElementById('registrationId').textContent = registrationId;
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('errorMessage').style.display = 'none';
            document.getElementById('volunteerForm').style.display = 'none';
            // Set the download PDF link
            document.getElementById('downloadPdfBtn').href = 'form-handler.php?action=download_pdf_volunteer&ref=' + encodeURIComponent(registrationId);
            // Scroll to success message
            document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
            showToast('Registration successful!');
        }

        // Show error message
        function showError(message) {
            document.getElementById('errorText').textContent = message;
            document.getElementById('errorMessage').style.display = 'block';
            document.getElementById('successMessage').style.display = 'none';
            
            // Scroll to error message
            document.getElementById('errorMessage').scrollIntoView({ behavior: 'smooth' });
            showToast(message, false);
        }

        // --- Prefill logic for edit mode ---
        document.addEventListener('DOMContentLoaded', function() {
            const editId = getQueryParam('id');
            if (editId) {
                fetch('form-handler.php?action=get&id=' + encodeURIComponent(editId))
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.id) {
                            // Prefill all fields
                            document.getElementById('firstName').value = data.first_name || '';
                            document.getElementById('lastName').value = data.last_name || '';
                            document.getElementById('email').value = data.email || '';
                            document.getElementById('contactNo').value = data.contact_no || '';
                            document.getElementById('whatsappNo').value = data.whatsapp_no || '';
                            document.getElementById('bloodGroup').value = data.blood_group || '';
                            document.getElementById('qualification').value = data.qualification || '';
                            document.getElementById('occupation').value = data.occupation || '';
                            document.getElementById('address1').value = data.address1 || '';
                            document.getElementById('address2').value = data.address2 || '';
                            document.getElementById('city').value = data.city || '';
                            document.getElementById('state').value = data.state || '';
                            document.getElementById('zip').value = data.zip || '';
                            document.getElementById('travelKm').value = data.travel_km || '';
                            // Preferred locations (custom multi-select)
                            if (data.preferred_locations) {
                                const locs = data.preferred_locations.split(',').map(s => s.trim());
                                document.querySelectorAll('#locationsList input[type="checkbox"]').forEach(cb => {
                                    cb.checked = locs.includes(cb.value);
                                });
                                updateSelectedLocations();
                            }
                            // Days
                            if (data.days) {
                                const daysArr = data.days.split(',').map(s => s.trim());
                                document.querySelectorAll('input[name="days[]"]').forEach(cb => {
                                    cb.checked = daysArr.includes(cb.value);
                                });
                            }
                            // Interests
                            if (data.interests) {
                                const intArr = data.interests.split(',').map(s => s.trim());
                                document.querySelectorAll('input[name="interests[]"]').forEach(cb => {
                                    cb.checked = intArr.includes(cb.value);
                                });
                            }
                            document.getElementById('motto').value = data.motto || '';
                            document.getElementById('instagram').value = data.instagram || '';
                            document.getElementById('facebook').value = data.facebook || '';
                            document.getElementById('twitter').value = data.twitter || '';
                            document.getElementById('linkedin').value = data.linkedin || '';
                            // Social repost
                            if (data.social_repost) {
                                document.querySelectorAll('input[name="social_repost"]').forEach(r => {
                                    r.checked = (r.value === data.social_repost);
                                });
                            }
                            document.getElementById('donate').value = data.donate || '';
                            // Show photo preview if photo exists
                            if (data.photo) {
                                const photoPreview = document.getElementById('photoPreview');
                                photoPreview.src = 'volunteer_photos/' + data.photo;
                                photoPreview.style.display = 'block';
                                photoInput.parentElement.classList.add('has-file');
                            }
                            // Ensure form is visible
                            form.style.display = '';
                            successMessage.style.display = 'none';
                            errorMessage.style.display = 'none';
                        }
                    });
            }
        });

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!validateForm()) {
                showError('Please fill in all required fields correctly.');
                return;
            }
            showLoading();
            try {
                const formData = new FormData(form);
                const response = await fetch('form-handler.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    showSuccess(result.registration_id);
                } else {
                    showError(result.message || 'Registration failed. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Network error. Please check your connection and try again.');
            } finally {
                hideLoading();
            }
        });

        // Real-time validation
        form.addEventListener('input', function(e) {
            const field = e.target;
            if (field.classList.contains('error')) {
                field.classList.remove('error');
                field.closest('.form-group').querySelector('.error-message').style.display = 'none';
            }
        });

        // Auto-fill WhatsApp number with contact number
        document.getElementById('contactNo').addEventListener('input', function(e) {
            const whatsappField = document.getElementById('whatsappNo');
            if (!whatsappField.value) {
                whatsappField.value = e.target.value;
            }
        });

        // Preferred Locations custom multiselect logic
        function toggleLocationsList() {
            const list = document.getElementById('locationsList');
            list.style.display = list.style.display === 'block' ? 'none' : 'block';
            document.getElementById('selectedLocations').style.borderColor = list.style.display === 'block' ? '#3498db' : '#ddd';
        }
        function highlightDropdown(focus) {
            document.getElementById('selectedLocations').style.borderColor = focus ? '#3498db' : '#ddd';
        }
        function updateSelectedLocations() {
            const checkboxes = document.querySelectorAll('#locationsList input[type="checkbox"]');
            const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
            const selectedDiv = document.getElementById('selectedLocations');
            const input = document.getElementById('preferredLocationsInput');
            selectedDiv.innerHTML = '';
            if (selected.length === 0) {
                selectedDiv.innerHTML = '<span id="placeholderText" style="color:#888;">Select preferred locations...</span>';
            } else {
                selected.forEach(loc => {
                    const tag = document.createElement('span');
                    tag.textContent = loc;
                    tag.style.background = '#3498db';
                    tag.style.color = '#fff';
                    tag.style.padding = '4px 12px';
                    tag.style.borderRadius = '14px';
                    tag.style.marginRight = '4px';
                    tag.style.fontSize = '15px';
                    tag.style.display = 'inline-block';
                    tag.style.boxShadow = '0 1px 4px rgba(52,152,219,0.08)';
                    selectedDiv.appendChild(tag);
                });
            }
            input.value = selected.join(', ');
            // Show checkmarks for selected
            document.querySelectorAll('#locationsList label').forEach(label => {
                const cb = label.querySelector('input[type="checkbox"]');
                const checkmark = label.querySelector('.checkmark');
                if (cb.checked) {
                    checkmark.style.display = 'inline';
                } else {
                    checkmark.style.display = 'none';
                }
            });
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('preferredLocationsDropdown');
            if (!dropdown.contains(e.target)) {
                document.getElementById('locationsList').style.display = 'none';
                document.getElementById('selectedLocations').style.borderColor = '#ddd';
            }
        });

        // Toast notification
        function showToast(msg, success = true) {
            const toast = document.getElementById('toast');
            toast.textContent = msg;
            toast.style.background = success ? '#27ae60' : '#e74c3c';
            toast.className = 'show';
            setTimeout(() => { toast.className = toast.className.replace('show', ''); }, 3200);
        }
        // Show FAB when scrolled down
        window.addEventListener('scroll', function() {
            document.getElementById('fabTop').style.display = window.scrollY > 200 ? 'flex' : 'none';
        });
        // Add fade-in to form sections/messages
        document.querySelectorAll('.form-section, .success-message, .error-alert').forEach(el => {
            el.classList.add('fade-in');
        });

        // Replace emoji section headers with SVG icons
        document.querySelectorAll('.section-title').forEach(function(title) {
            const text = title.textContent.trim();
            let icon = '';
            if (text.includes('Personal Information')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg>';
            if (text.includes('Address Information')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>';
            if (text.includes('Travel')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M18.92 5.01C18.72 4.42 18.15 4 17.5 4h-11c-.65 0-1.22.42-1.42 1.01L2 9v2c0 .55.45 1 1 1h1v7c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-7h1c.55 0 1-.45 1-1V9l-2.08-3.99zM6.85 6h10.29l1.04 3H5.81l1.04-3zM19 19c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1v-7h14v7z"/></svg>';
            if (text.includes('Availability')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13zm0-15H5V5h14v1z"/></svg>';
            if (text.includes('Areas of Interest')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';
            if (text.includes('Motivation')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2" stroke="#fff" stroke-width="2" fill="none"/></svg>';
            if (text.includes('Social Media')) icon = '<svg width="22" height="22" fill="#3498db" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04A4.28 4.28 0 0 0 16.11 4c-2.37 0-4.29 1.92-4.29 4.29 0 .34.04.67.11.99C7.69 8.99 4.07 7.13 1.64 4.16c-.37.64-.58 1.38-.58 2.17 0 1.5.76 2.82 1.92 3.6-.71-.02-1.38-.22-1.97-.54v.05c0 2.1 1.5 3.85 3.5 4.25-.36.1-.74.16-1.13.16-.28 0-.54-.03-.8-.08.54 1.7 2.1 2.94 3.95 2.97A8.6 8.6 0 0 1 2 19.54c-.29 0-.57-.02-.85-.06A12.13 12.13 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.54A8.18 8.18 0 0 0 22.46 6z"/></svg>';
            if (text.includes('Willing to Donate Blood')) icon = '<svg width="22" height="22" fill="#e74c3c" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';
            if (text.includes('Photo Upload')) icon = '<svg width="22" height="22" fill="#764ba2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM5 5h14v14H5V5zm7 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';
            if (text.includes('Consent')) icon = '<svg width="22" height="22" fill="#27ae60" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>';
            if (icon) title.innerHTML = icon + text.replace(/^[^a-zA-Z]+/, '');
        });

        // Preferred Locations tag input logic
        const tagInput = document.getElementById('preferredLocationsInput');
        const tagContainer = document.getElementById('locationsTagInput');
        const hiddenInput = document.getElementById('preferredLocationsHidden');
        let tags = [];
        function renderTags() {
            tagContainer.querySelectorAll('.tag-chip').forEach(e => e.remove());
            tags.forEach((tag, idx) => {
                const chip = document.createElement('span');
                chip.className = 'tag-chip';
                chip.textContent = tag;
                const remove = document.createElement('span');
                remove.className = 'remove-tag';
                remove.textContent = '×';
                remove.onclick = () => {
                    tags.splice(idx, 1);
                    renderTags();
                };
                chip.appendChild(remove);
                tagContainer.insertBefore(chip, tagInput);
            });
            hiddenInput.value = tags.join(', ');
        }
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                let value = tagInput.value.trim().replace(/,$/, '');
                if (value && !tags.includes(value)) {
                    tags.push(value);
                    renderTags();
                }
                tagInput.value = '';
                return false;
            }
        });
        tagInput.addEventListener('blur', function() {
            let value = tagInput.value.trim().replace(/,$/, '');
            if (value && !tags.includes(value)) {
                tags.push(value);
                renderTags();
            }
            tagInput.value = '';
        });
        // On input, hide error
        hiddenInput.addEventListener('input', function() {
            tagContainer.style.borderColor = '#ddd';
            tagContainer.closest('.form-group').querySelector('.error-message').style.display = 'none';
        });
        // Always render tags on page load (even if empty)
        renderTags();
        // If in edit mode and data.preferred_locations exists, prefill tags
        if (typeof data !== 'undefined' && data.preferred_locations) {
            tags = data.preferred_locations.split(',').map(s => s.trim()).filter(Boolean);
            renderTags();
        }
    </script>
</body>
</html>