<?php
session_start();
$error = '';
$conn = new mysqli("localhost", "root", "", "macj_pest_control");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate passwords
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Validate password strength
        if (strlen($password) < 10 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password)) {
            $error = "Password must be at least 10 characters long and include uppercase, lowercase letters, and a number.";
        } else {
            // Check if email exists
            $check = $conn->prepare("SELECT email FROM clients WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Email already registered!";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new client
                $stmt = $conn->prepare("INSERT INTO clients (first_name, last_name, email, contact_number, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $firstName, $lastName, $email, $contact, $hashedPassword);

                if ($stmt->execute()) {
                    $_SESSION['client_id'] = $stmt->insert_id;
                    header("Location: SignIn.php");
                    exit();
                } else {
                    $error = "Registration failed: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MacJ Pest Control</title>
    <link href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
<div class="bg-light py-3 py-md-5">
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
        <div class="bg-white p-4 p-md-5 rounded shadow-sm">
          <div class="row">
            <div class="col-12">
              <div class="text-center mb-5">
                <a href="landing.php">
                  <img src="MACJLOGO.png" alt="MacJ Pest Control" width="180" height="57">
                </a>
              </div>
            </div>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <form action="SignUp.php" method="POST">
            <div class="row gy-3 gy-md-4 overflow-hidden">
              <!-- First Name -->
              <div class="col-12">
                <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard" viewBox="0 0 16 16">
                      <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
                      <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12z"/>
                    </svg>
                  </span>
                  <input type="text" class="form-control" name="firstName" id="firstName" required>
                </div>
              </div>

              <!-- Last Name -->
              <div class="col-12">
                <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                      <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                      <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                    </svg>
                  </span>
                  <input type="text" class="form-control" name="lastName" id="lastName" required>
                </div>
              </div>

              <!-- Email -->
              <div class="col-12">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                      <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                    </svg>
                  </span>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>
              </div>

              <!-- Contact Number -->
              <div class="col-12">
                <label for="contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                      <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58zM1.884.511a1.75 1.75 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                    </svg>
                  </span>
                  <input type="tel" class="form-control" name="contact" id="contact" required>
                </div>
              </div>

              <!-- Password -->
              <div class="col-12">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                      <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                      <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                  </span>
                  <input type="password" class="form-control" name="password" id="password" 
                         pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}" 
                         title="Must contain at least 10 characters, including uppercase, lowercase letters, and a number" 
                         required>
                  <span class="input-group-text" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye"></i>
                  </span>
                </div>
              </div>

              <!-- Confirm Password -->
              <div class="col-12">
                <label for="confirmPassword" class="form-label">Re-Type Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                      <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                      <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                  </span>
                  <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" 
                         pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{10,}" 
                         required>
                  <span class="input-group-text" onclick="togglePassword('confirmPassword', this)">
                    <i class="bi bi-eye"></i>
                  </span>
                </div>
              </div>

              <!-- Terms Checkbox -->
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="iAgree" id="iAgree" required>
                  <label class="form-check-label text-secondary" for="iAgree">
                    I agree to the <a href="#" class="link-primary text-decoration-none">terms and conditions</a>
                  </label>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="col-12">
                <div class="d-grid">
                  <button class="btn btn-primary btn-lg" type="submit" href="landing.php">Sign Up</button>
                </div>
              </div>
            </div>
          </form>

          <!-- Sign In Link -->
          <div class="row">
            <div class="col-12">
              <hr class="mt-5 mb-4 border-secondary-subtle">
              <p class="m-0 text-secondary text-center">Already have an account? <a href="SignIn.php" class="link-primary text-decoration-none">Sign in</a></p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
function togglePassword(fieldId, element) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = element.querySelector('i');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('bi-eye');
        eyeIcon.classList.add('bi-eye-slash');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('bi-eye-slash');
        eyeIcon.classList.add('bi-eye');
    }
}
</script>
</body>
</html>