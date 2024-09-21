<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div id="loginApp" class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-indigo-600">Login</h2>
        <form @submit.prevent="loginUser" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input v-model="username" id="username" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
                </div>
                <div class="mt-4">
                    <label for="password" class="sr-only">Password</label>
                    <input v-model="password" id="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input v-model="remember_me" id="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900"> Remember me </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> Forgot your password? </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"> Sign in </button>
            </div>
        </form>
        <p class="mt-2 text-center text-sm text-gray-600">
            <a href="html/register_form.php" class="font-medium text-indigo-600 hover:text-indigo-500">Create a new account</a>
        </p>
    </div>

    <script>
        new Vue({
            el: '#loginApp',
            data: {
                username: '',
                password: '',
                remember_me: false
            },
            methods: {
                loginUser() {
                    const formData = new FormData();
                    formData.append('username', this.username);
                    formData.append('password', this.password);
                    formData.append('remember_me', this.remember_me ? '1' : '0');

                    fetch('php/login.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful!',
                                text: 'You are now logged in.',
                                confirmButtonText: 'Continue',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                window.location.href = './index.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: data.message,
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
