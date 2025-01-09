<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p class=" message bg-red-100 my-3"> <?= $error ?> </p>
    <?php endforeach; ?>
    </div>

<?php endif; ?>