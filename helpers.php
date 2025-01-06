<?php

/**
 * Get the base path
 * 
 * @param string $path
 * @return string
 */
function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view 
 * 
 * @param string $name
 * @return void
 */
function loadView($name, $data = [])
{
    $viewPath = basePath("views/{$name}.view.php");
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View file not found: $viewPath";
    }
}

/**
 * Load a partial 
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name)
{
    $partialPath = basePath("views/partials/{$name}.php");
    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "Partial file not found: $partialPath";
    }
}

/**
 * Inspect a value(s)
 * @param mixed $value
 * @return void
 * 
 */

function inspect($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a value(s) and die
 * @param mixed $value
 * @return void
 * 
 */

function inspectAndDie($value)
{
    echo '<pre>';

    die(var_dump($value));
    echo '</pre>';
}


/**
 * 
 * Format Salary
 * @param string $salary
 * @return string $formatedSalary
 */
function formatSalary($salary)
{
    return '$' . number_format(floatval($salary));
}
