
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice of Tenkasi Foundation - Volunteer Portal</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(59,91,219,0.13);
            padding: 36px 28px 32px 28px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #3b5bdb;
            margin-bottom: 18px;
            font-size: 2em;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: none;
            border-radius: 8px;
            background: #3498db;
            color: #fff;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .login-section {
            margin-top: 24px;
            background: #f8fafd;
            border-radius: 10px;
            padding: 18px 14px 10px 14px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #bbb;
            border-radius: 8px;
            font-size: 1em;
            margin-bottom: 12px;
        }
        .profile-card {
            background: #f4f8ff;
            border-radius: 12px;
            margin-top: 18px;
            padding: 18px 10px;
            box-shadow: 0 2px 8px rgba(59,91,219,0.07);
            text-align: left;
        }
        .profile-card h2 {
            color: #3b5bdb;
            margin: 0 0 10px 0;
            font-size: 1.2em;
        }
        .profile-row { margin-bottom: 7px; }
        .error-msg {
            color: #e74c3c;
            background: #fbeaea;
            border-radius: 8px;
            padding: 8px 0;
            margin-bottom: 10px;
            font-size: 1em;
        }
        .success-msg {
            color: #27ae60;
            background: #eafaf1;
            border-radius: 8px;
            padding: 8px 0;
            margin-bottom: 10px;
            font-size: 1em;
        }
    </style>
</head>
<body>
  <div class="container">
  <img src="logo.png" alt="Voice of Tenkasi Logo" style="max-width:160px;width:100%;margin-bottom:18px;" />
  <h1>Volunteer Portal</h1>
  <a href="registration.php" class="btn">Register as Volunteer</a>

  <div class="login-section" id="loginSection">
    <h2 style="margin-bottom:10px;font-size:1.1em;color:#222;">View Your Profile</h2>

    <form id="mobileLoginForm" autocomplete="off" onsubmit="return false;">
      <input type="text" id="loginMobile" placeholder="Enter your mobile number" maxlength="10" pattern="[0-9]{10}" required />
      <button class="btn" onclick="loginByMobile()">Login</button>
    </form>

    <div id="loginError" class="error-msg" style="display:none;"></div>
    <div id="loginSuccess" class="success-msg" style="display:none;"></div>
  </div>
</div>

<script>
  function showError(msg) {
    const errEl = document.getElementById('loginError');
    errEl.innerText = msg;
    errEl.style.display = 'block';
    document.getElementById('loginSuccess').style.display = 'none';
  }

  function showSuccess(msg) {
    const successEl = document.getElementById('loginSuccess');
    successEl.innerHTML = msg;
    successEl.style.display = 'block';
    document.getElementById('loginError').style.display = 'none';
  }

  function loginByMobile() {
    const phone = document.getElementById('loginMobile').value.trim();
    if (!phone.match(/^\d{10}$/)) {
      showError('Please enter a valid 10-digit mobile number.');
      return;
    }

    showSuccess('Checking mobile number...');

    fetch(`form-handler.php?action=login&mobile=${encodeURIComponent(phone)}`)
      .then(res => {
        if (!res.ok) throw new Error('Mobile number not found.');
        return res.json();
      })
      .then(data => {
        if (data.registration_id) {
          showSuccess('Login successful! Redirecting...');
          setTimeout(() => {
            // ✅ Redirect using profile=registration_id format
            window.location.href = `https://volunteer.voiceoftenkasi.org/form-handler.php?profile=${encodeURIComponent(data.registration_id)}`;
          }, 1200);
        } else {
          showError('Mobile number not registered.');
        }
      })
      .catch(err => {
        showError(err.message || 'Login failed. Try again.');
      });
  }
</script>

</body>
</html>
