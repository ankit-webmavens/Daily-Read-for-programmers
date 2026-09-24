<?php
// 2026-09-24 06:41:12

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements separate SQL code from data values, preventing SQL injection attacks. PDO provides a consistent interface for various databases, allowing you to prepare a query once and execute it multiple times with different parameters. When a statement is prepared, the database parses and compiles the SQL, then you bind values to placeholders before execution. This approach also improves performance for repeated queries and makes code easier to read and maintain. Using PDO’s error mode set to exceptions helps catch issues early in development.

Code example (with comments):

<?php
// Create a new PDO connection (replace DSN, username, password with real values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$user = 'dbuser';
$pass = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $user, $pass, $options);

// SQL with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Data to insert
$data = [
    ['username' => 'alice', 'email' => 'alice@example.com'],
    ['username' => 'bob',   'email' => 'bob@example.org'],
    ['username' => 'carol', 'email' => 'carol@example.net'],
];

// Execute the prepared statement for each row
foreach ($data as $row) {
    // Bind values and execute; execute() can accept an array directly
    $stmt->execute([
        ':username' => $row['username'],
        ':email'    => $row['email'],
    ]);
    // Optional: get the ID of the inserted row
    $lastId = $pdo->lastInsertId();
    echo "Inserted user {$row['username']} with ID $lastId\n";
}
?>
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Injection  

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. It resolves class instances, injecting their required dependencies without manual instantiation. By binding abstractions to concrete implementations, you can swap implementations easily, facilitating testing and adherence to the SOLID principles. Controllers, jobs, listeners, and other classes can type‑hint dependencies in their constructors and Laravel will resolve them from the container. This mechanism simplifies code, improves readability, and centralises configuration of services.

Code example:

// app/Services/ReportGenerator.php
<?php

namespace App\Services;

class ReportGenerator
{
    protected $format;

    public function __construct(string $format = 'pdf')
    {
        $this->format = $format;
    }

    public function generate(array $data)
    {
        // generate a report in the specified format
        return "Report generated in {$this->format} format.";
    }
}

// app/Providers/AppServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ReportGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the ReportGenerator to the container with a custom format
        $this->app->bind(ReportGenerator::class, function ($app) {
            return new ReportGenerator('excel'); // change format as needed
        });
    }

    public function boot()
    {
        //
    }
}

// app/Http/Controllers/ReportController.php
<?php

namespace App\Http\Controllers;

use App\Services\ReportGenerator;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportGenerator;

    // Laravel automatically injects the bound ReportGenerator instance
    public function __construct(ReportGenerator $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
    }

    public function show(Request $request)
    {
        $data = $request->all(); // pretend this is the data for the report
        $result = $this->reportGenerator->generate($data);

        return response($result);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries in MySQL

Explanation:
A Common Table Expression (CTE) is a temporary named result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and improves query readability, especially for complex subqueries. MySQL 8.0 introduced support for both non‑recursive and recursive CTEs. Recursive CTEs allow you to perform hierarchical or tree‑like traversals by repeatedly applying a query to its own output until a termination condition is met. This feature is useful for organizational charts, bill‑of‑materials, or any data that has parent‑child relationships.

Code Example (calculating a simple employee hierarchy):
-- Define a recursive CTE named employee_path that starts with top‑level managers (manager_id IS NULL)
WITH RECURSIVE employee_path AS (
    SELECT 
        employee_id,
        employee_name,
        manager_id,
        CAST(employee_name AS CHAR(255)) AS path,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL
    
    UNION ALL
    
    SELECT 
        e.employee_id,
        e.employee_name,
        e.manager_id,
        CONCAT(ep.path, ' > ', e.employee_name) AS path,
        ep.level + 1 AS level
    FROM employees e
    INNER JOIN employee_path ep ON e.manager_id = ep.employee_id
)
SELECT 
    employee_id,
    employee_name,
    manager_id,
    path,
    level
FROM employee_path
ORDER BY level, employee_name;
*/

/* JavaScript
Topic Name: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when it is executed outside the original context where it was defined.  
It allows an inner function to remember variables from the outer function after the outer function has finished running.  
Closures are created each time a function is defined, capturing the environment (variables, parameters) at that moment.  
They are commonly used for data privacy, partial application, and implementing module‑like patterns.  
Understanding closures helps avoid pitfalls such as unintended sharing of loop variables or memory leaks.  

Code Example (with comments):  

function createCounter(initialValue) {                     // outer function that receives a starting value  
    let count = initialValue;                             // variable that will be captured by the closure  

    return function() {                                   // inner function forms a closure over 'count'  
        count += 1;                                       // modify the captured variable  
        return count;                                     // expose the updated value  
    };                                                    // end of inner function  
}                                                         // end of outer function  

const counterA = createCounter(0);  // counterA has its own independent 'count' variable  
const counterB = createCounter(10); // counterB has a separate 'count' variable  

console.log(counterA()); // 1  
console.log(counterA()); // 2  
console.log(counterB()); // 11  
console.log(counterB()); // 12   // each counter maintains its own private state via a closure.
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Classification with the OpenAI GPT‑4 API  

Explanation:  
Few‑shot prompting lets you teach a language model new tasks by providing a handful of labeled examples directly in the prompt. By carefully formatting the examples and instructions, the model can generalize to unseen inputs without any gradient updates. This approach is useful for rapid prototyping, handling niche categories, or when labeled data is scarce. The key is to keep the prompt concise, maintain consistent formatting, and include a clear task description. You can automate the construction of such prompts to feed them to the OpenAI API and parse the model’s structured response.

Code example (Python, uses the openai package):

import os
import json
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def build_few_shot_prompt(examples, user_input):
    """
    Create a prompt that contains a short task description,
    several input‑label pairs (the few‑shot examples),
    and the new input for which we want a prediction.
    """
    prompt = "You are a text classifier. Assign one of the following labels: Positive, Negative, Neutral.\n"
    prompt += "Examples:\n"
    for inp, label in examples:
        prompt += f"Text: \"{inp}\" -> Label: {label}\n"
    prompt += f"Text: \"{user_input}\" -> Label:"
    return prompt

# Define a few labeled examples (input text, label)
few_shot_examples = [
    ("I love this product, it works great!", "Positive"),
    ("The service was terrible and slow.", "Negative"),
    ("It's okay, not the best but not the worst.", "Neutral")
]

# New text we want to classify
new_text = "The movie was a complete waste of time."

# Build the prompt
prompt_text = build_few_shot_prompt(few_shot_examples, new_text)

# Call the OpenAI chat completion endpoint with the constructed prompt
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",
    messages=[{"role": "user", "content": prompt_text}],
    temperature=0.0,               # deterministic output
    max_tokens=5                   # we only need the label word
)

# Extract and clean the label from the model’s reply
predicted_label = response.choices[0].message.content.strip()
print(f"Predicted label: {predicted_label}")   # Expected output: Negative (or Neutral depending on model)
*/

