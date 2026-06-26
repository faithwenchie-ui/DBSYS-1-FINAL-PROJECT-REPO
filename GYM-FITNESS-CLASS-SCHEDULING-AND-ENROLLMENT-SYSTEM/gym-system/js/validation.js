// Validate the Add/Edit member form before submitting it to the server.
function validateMemberForm() {

    let fullName =
        document.forms["memberForm"]["full_name"].value.trim();

    let email =
        document.forms["memberForm"]["email"].value.trim();

    let phone =
        document.forms["memberForm"]["phone"].value.trim();

    if (fullName === "") {

        showToast("Full Name is required.");
        return false;

    }

    if (fullName.length < 8) {

        showToast("Full Name must be at least 8 characters.");
        return false;

    }

    if (email === "") {

        showToast("Email is required.");
        return false;

    }

    if (phone === "") {

        showToast("Phone number is required.");
        return false;

    }

    let emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {

        showToast("Please enter a valid email address.");
        return false;

    }

    let phonePattern =
        /^[0-9]{11}$/;

    if (!phonePattern.test(phone)) {

        showToast("Phone number must contain exactly 11 digits.");

        return false;

    }

    return true;

}

// Validate the login form fields before allowing submission.
function validateLoginForm() {

    let username =
        document.forms["loginForm"]["username"].value.trim();

    let password =
        document.forms["loginForm"]["password"].value.trim();

    if (username === "") {

        showToast("Username is required.");
        return false;

    }

    if (password === "") {

        showToast("Password is required.");
        return false;

    }

    return true;

}

// Validate the signup form fields before allowing submission.
function validateSignupForm() {
    let fullName = document.forms["signupForm"]["full_name"].value.trim();
    let email = document.forms["signupForm"]["email"].value.trim();
    let phone = document.forms["signupForm"]["phone"].value.trim();

    if (fullName === "") {
        showToast("Full Name is required.");
        return false;
    }

    if (fullName.length < 8) {
        showToast("Full Name must be at least 8 characters.");
        return false;
    }

    if (email === "") {
        showToast("Email address is required.");
        return false;
    }

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showToast("Please enter a valid email address.");
        return false;
    }

    if (phone === "") {
        showToast("Phone number is required.");
        return false;
    }

    let phonePattern = /^[0-9]{11}$/;
    if (!phonePattern.test(phone)) {
        showToast("Phone number must contain exactly 11 digits.");
        return false;
    }

    let password = document.forms["signupForm"]["password"].value.trim();
    let confirmPassword = document.forms["signupForm"]["confirm_password"].value.trim();

    if (password === "") {
        showToast("Password is required.");
        return false;
    }

    if (password.length < 6) {
        showToast("Password must be at least 6 characters.");
        return false;
    }

    if (password !== confirmPassword) {
        showToast("Passwords do not match.");
        return false;
    }

    return true;
}

// Validate the change password form before submission.
function validateChangePasswordForm() {
    let currentPassword = document.forms["changePasswordForm"]["current_password"].value.trim();
    let newPassword = document.forms["changePasswordForm"]["new_password"].value.trim();
    let confirmPassword = document.forms["changePasswordForm"]["confirm_new_password"].value.trim();

    if (currentPassword === "") {
        showToast("Current password is required.");
        return false;
    }

    if (newPassword === "") {
        showToast("New password is required.");
        return false;
    }

    if (newPassword.length < 6) {
        showToast("New password must be at least 6 characters.");
        return false;
    }

    if (newPassword !== confirmPassword) {
        showToast("New passwords do not match.");
        return false;
    }

    return true;
}

// Validate the enrollment form to ensure both member and class are selected.
function validateEnrollmentForm() {

    let member =
        document.forms["enrollmentForm"]["member_id"].value;

    let gymClass =
        document.forms["enrollmentForm"]["class_id"].value;

    if (member === "") {

        showToast("Please select a member.");
        return false;

    }

    if (gymClass === "") {

        showToast("Please select a class.");
        return false;

    }

    let selectedClassOption = document.forms["enrollmentForm"]["class_id"].selectedOptions[0];
    if (selectedClassOption && selectedClassOption.dataset.availableSlots !== undefined) {
        let availableSlots = parseInt(selectedClassOption.dataset.availableSlots, 10);
        if (!isNaN(availableSlots) && availableSlots <= 0) {
            showToast("Cannot enroll: selected class is full.");
            return false;
        }
    }

    return true;

}

// Validate the class edit form fields before updating the schedule and capacity.
function validateClassForm() {

    let trainerId =
        document.forms["classForm"]["trainer_id"].value.trim();

    let schedule =
        document.forms["classForm"]["schedule_at"].value.trim();

    let maxCapacity =
        document.forms["classForm"]["max_capacity"].value.trim();

    if (trainerId === "") {

        showToast("Trainer is required.");
        return false;

    }

    if (schedule === "") {

        showToast("Schedule is required.");
        return false;

    }

    if (maxCapacity === "") {

        showToast("Maximum capacity is required.");
        return false;

    }

    let capacityNum = parseInt(maxCapacity, 10);

    if (isNaN(capacityNum) || capacityNum <= 0) {

        showToast("Maximum capacity must be a positive integer.");
        return false;

    }

    return true;

}

// Validate the attendance form before submission.
function validateAttendanceForm() {
    let enrollment = document.forms["attendanceForm"]["enrollment_id"].value;
    let status = document.forms["attendanceForm"]["status"].value;

    if (enrollment === "") {
        showToast("Please select an enrollment.");
        return false;
    }

    if (status === "") {
        showToast("Please select an attendance status.");
        return false;
    }

    return true;
}

// Validate the member profile form before submission.
function validateEditProfileForm() {
    let fullName = document.forms["profileForm"]["full_name"].value.trim();
    let email = document.forms["profileForm"]["email"].value.trim();
    let phone = document.forms["profileForm"]["phone"].value.trim();

    if (fullName === "") {
        showToast("Full Name is required.");
        return false;
    }

    if (fullName.length < 8) {
        showToast("Full Name must be at least 8 characters.");
        return false;
    }

    if (email === "") {
        showToast("Email is required.");
        return false;
    }

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showToast("Please enter a valid email address.");
        return false;
    }

    if (phone === "") {
        showToast("Phone number is required.");
        return false;
    }

    let phonePattern = /^[0-9]{11}$/;
    if (!phonePattern.test(phone)) {
        showToast("Phone number must contain exactly 11 digits.");
        return false;
    }

    return true;
}