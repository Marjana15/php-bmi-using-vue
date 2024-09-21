<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <title>Register</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div id="registerApp" class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-indigo-600">Create an Account</h2>
        <form @submit.prevent="registerUser" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input v-model="username" id="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
                </div>
                <div class="mt-4">
                    <label for="password" class="sr-only">Password</label>
                    <input v-model="password" id="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
                <div class="mt-4">
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input v-model="password_confirmation" id="password_confirmation" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"> Register </button>
            </div>
        </form>
        <p class="mt-2 text-center text-sm text-gray-600">
            Already have an account?
            <a href="../index.php" class="font-medium text-indigo-600 hover:text-indigo-500"> Sign in </a>
        </p>
    </div>

    <!-- Vue.js script -->
    <script>
        new Vue({
            el: '#registerApp',
            data: {
                username: '',
                password: '',
                password_confirmation: ''
            },
            methods: {
                registerUser() {
                    // Check password confirmation
                    if (this.password !== this.password_confirmation) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Passwords do not match!',
                            text: 'Please make sure both passwords are the same.',
                            confirmButtonText: 'Try Again',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('username', this.username);
                    formData.append('password', this.password);
                    formData.append('password_confirmation', this.password_confirmation);

                    fetch('../php/register.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registered Successfully!',
                                text: 'Your account has been created.',
                                confirmButtonText: 'Login Now',
                                confirmButtonColor: '#3085d6'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../index.php';
                                }
                            });
                        } else if (data.message === 'duplicate') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: 'Username already exists, please choose another one.',
                                confirmButtonText: 'Try Again',
                                confirmButtonColor: '#d33'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong. Please try again.',
                                confirmButtonText: 'Try Again',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            confirmButtonText: 'Try Again',
                            confirmButtonColor: '#d33'
                        });
                    });
                }
            }
        });
    </script>

</body>
</html>
