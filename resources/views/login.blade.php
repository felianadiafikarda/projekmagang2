<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Auth Slide</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .viewport {
      width: 100vw;
      height: 100vh;
      overflow: hidden;
      position: relative;
    }

    .slider {
      width: 200%;
      height: 100%;
      display: flex;
      transition: transform .7s ease-in-out;
    }

    /* DEFAULT: LOGIN */
    #login:checked~.viewport .slider {
      transform: translateX(0%);
    }

    /* REGISTER */
    #register:checked~.viewport .slider {
      transform: translateX(-50%);
    }
  </style>
</head>

<body class="bg-gray-200">

  <!-- STATE -->
  <input type="radio" name="auth" id="login" checked hidden>
  <input type="radio" name="auth" id="register" hidden>

  <!-- TOP BUTTON (STATIC) -->
  <div class="fixed top-6 left-0 right-0 z-50 px-8 flex text-xl justify-between font-bold text-gray-200">
    <label for="register" class="cursor-pointer">Register</label>
    <label for="login" class="cursor-pointer">Login</label>
  </div>

  <!-- VIEWPORT -->
  <div class="viewport">

    <div class="slider">

      <!-- LOGIN STATE -->
      <div class="w-1/2 h-full flex">

        <!-- BRAND -->
        <div class="w-1/2 bg-indigo-600 text-white flex items-center justify-center p-12">
          <div class="max-w-md">
            <h1 class="text-4xl font-bold mb-4">Journal Management System</h1>
            <p class="text-indigo-100">
              A professional platform to submit, review, and manage academic manuscripts efficiently.
            </p>
          </div>
        </div>

        <!-- LOGIN FORM -->
        <div class="w-1/2 flex items-center justify-center">
          <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
              @csrf
              <div>
                <label for="email" class="block mb-2">Email *</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Email"
                  class="w-full border rounded-md p-3">
              </div>

              <div>
                <label for="password" class="block mb-2">Password *</label>
                <input type="password" name="password" placeholder="Password"
                  class="w-full border rounded-md p-3">
              </div>

              <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-md font-semibold">
                Login
              </button>

              <!-- Forgot Password -->
              <div class="text-right mt-1">
                <a href="/forgot-password" class="text-sm text-indigo-500 hover:underline">Forgot Password?</a>
              </div>

              <!-- Don't have an account -->
              <div class="mt-4 text-sm">
                <p>Don't have an account?
                  <label for="register" class="text-indigo-500 hover:underline font-medium">Register</label>
                </p>
              </div>
            </form>
          </div>
        </div>

      </div>

      <!-- REGISTER STATE -->
      <div class="w-1/2 h-full flex">

        <!-- REGISTER FORM -->
        <div class="w-1/2 flex items-center justify-center">
          <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

            <form method="POST" action="{{ route('registration.post') }}" class="space-y-4">
              @csrf

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="first_name" class="block mb-2 text-sm font-medium">First Name</label>
                  <input type="text" name="first_name" placeholder="First Name"
                    class="w-full border rounded-md p-3">
                </div>

                <div>
                  <label for="last_name" class="block mb-2 text-sm font-medium">Last Name</label>
                  <input type="text" name="last_name" placeholder="Last Name" 
                    class="w-full border rounded-md p-3">
                </div>
              </div>


              <div>
                <label for="email" class="block mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email"
                  class="w-full border rounded-md p-3">
              </div>

              <div>
                <label for="username" class="block mb-2">Username</label>
                <input name="username" placeholder="Username"
                  class="w-full border rounded-md p-3">
              </div>

              <div>
                <label for="password" class="block mb-2">Password</label>
                <input type="password" name="password" placeholder="Password"
                  class="w-full border rounded-md p-3">
              </div>

              <div>
                <label for="password" class="block mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                  class="w-full border rounded-md p-3">
              </div>

              <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-md font-semibold">
                Register
              </button>

              <!-- Already have an account -->
              <div class="mt-4 text-sm">
                <p>Already have an account?
                  <label for="login" class="text-indigo-500 hover:underline font-medium">Login</label>
                </p>
              </div>
            </form>
          </div>
        </div>

        <!-- BRAND -->
        <div class="w-1/2 bg-indigo-600 text-white flex items-center justify-center p-12">
          <div class="max-w-md">
            <h1 class="text-4xl font-bold mb-4">Join Our Journal</h1>
            <p class="text-indigo-100">
              Create an account to submit and manage your academic manuscripts.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>