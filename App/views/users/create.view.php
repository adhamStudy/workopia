<?php loadPartial('head'); ?>

<!-- Nav -->
<?php loadPartial('navbar'); ?>

<!-- Registration Form Box -->
<div class="flex justify-center items-center mt-20">
    <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-500 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Register</h2>

        <!-- Display validation errors -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/auth/register">
            <div class="mb-4">
                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $old['name'] ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $old['email'] ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input
                    type="text"
                    name="city"
                    placeholder="City"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $old['city'] ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input
                    type="text"
                    name="state"
                    placeholder="State"
                    class="w-full px-4 py-2 border rounded focus:outline-none"
                    value="<?= $old['state'] ?? '' ?>" />
            </div>
            <div class="mb-4">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <div class="mb-4">
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    class="w-full px-4 py-2 border rounded focus:outline-none" />
            </div>
            <button
                type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none">
                Register
            </button>

            <p class="mt-4 text-gray-500">
                Already have an account?
                <a class="text-blue-900" href="/auth/login">Login</a>
            </p>
        </form>
    </div>
</div>

<?php loadPartial('footer'); ?>