<?php
// 2026-10-19 06:43:21

/* PHP
Topic: Using Prepared Statements with PDO for Secure Database Queries

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks by sending the query structure to the database first and then binding user‑supplied values. PDO (PHP Data Objects) provides a consistent interface for many database systems, making it easy to switch drivers without changing application code. When you prepare a statement, the database can optimize the query plan once and reuse it for multiple executions, which can improve performance. Binding parameters also handles proper escaping and type conversion automatically. This approach is considered best practice for any application that accepts external input for database operations.

Code example with comments:
<?php
// Create a new PDO instance (replace DSN, username, and password with real values)
$dsn = 'mysql:host=localhost;dbname=example_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch results as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders (automatic type handling)
$stmt->bindValue(':username', $_POST['username']);
$stmt->bindValue(':email', $_POST['email']);

// Execute the statement; returns true on success
if ($stmt->execute()) {
    echo "User added successfully.";
} else {
    echo "Failed to add user.";
}

// Example of fetching data with a prepared SELECT statement
$selectSql = "SELECT id, username, email FROM users WHERE email = :email";
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->bindValue(':email', $_POST['email']);
$selectStmt->execute();
$user = $selectStmt->fetch();

if ($user) {
    echo "User ID: " . $user['id'];
} else {
    echo "No user found with that email.";
}
?>
*/

/* Laravel
Topic: Laravel Custom Validation Rules

Explanation:  
A custom validation rule allows you to encapsulate complex validation logic that isn’t covered by Laravel’s built‑in rules.  
You create a rule class with the Artisan command `php artisan make:rule RuleName`, then implement the `passes` method to define the check and the `message` method for the error message.  
The rule can be used directly in form request classes or controller validation arrays just like any native rule.  
It keeps validation logic reusable, testable, and separated from controller code.  
Custom rules are especially useful for checks that involve database queries, external APIs, or intricate business rules.

Code example (UserRequest.php):
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidReferralCode; // custom rule

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // authorize all users for this example
    }

    public function rules()
    {
        return [
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'referral' => ['nullable', new ValidReferralCode()], // apply custom rule
        ];
    }
}
?>

Code example (ValidReferralCode.php):
<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Referral; // model that stores valid codes

class ValidReferralCode implements Rule
{
    // The passes method receives the attribute name and its value
    public function passes($attribute, $value)
    {
        // Check that the referral code exists and is still active
        return Referral::where('code', $value)
                       ->where('is_active', true)
                       ->exists();
    }

    // Return the validation error message
    public function message()
    {
        return 'The :attribute is invalid or has expired.';
    }
}
?>
*/

/* MySQL
Topic: Stored Procedures with IN, OUT, and INOUT Parameters

Explanation:  
A stored procedure is a pre‑compiled set of SQL statements that can be executed repeatedly.  
MySQL allows you to define parameters that are passed into the procedure (IN), returned to the caller (OUT), or both (INOUT).  
Using parameters makes procedures flexible, letting you reuse the same logic with different input values.  
OUT and INOUT parameters are useful for returning computed results without needing a result set.  
Proper declaration of parameter types (e.g., INT, VARCHAR) ensures type safety and clear contract between the caller and the procedure.

Code Example:
CREATE PROCEDURE GetEmployeeStats(
    IN  p_department_id INT,
    OUT p_employee_count INT,
    INOUT p_average_salary DECIMAL(10,2)
)
BEGIN
    -- Count employees in the given department
    SELECT COUNT(*) INTO p_employee_count
    FROM employees
    WHERE department_id = p_department_id;

    -- Calculate average salary; if p_average_salary is NULL, set it to the computed value
    IF p_average_salary IS NULL THEN
        SELECT AVG(salary) INTO p_average_salary
        FROM employees
        WHERE department_id = p_department_id;
    ELSE
        -- If caller supplied a starting value, add it to the computed average
        SELECT AVG(salary) + p_average_salary INTO p_average_salary
        FROM employees
        WHERE department_id = p_department_id;
    END IF;
END;

-- Example call:
-- Declare variables to receive OUT/INOUT values
SET @dept_id = 3;
SET @emp_cnt = 0;
SET @avg_sal = NULL;   -- will be calculated inside the procedure
CALL GetEmployeeStats(@dept_id, @emp_cnt, @avg_sal);
SELECT @emp_cnt AS employee_count, @avg_sal AS average_salary;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
- A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing.  
- The inner function retains a reference to the outer scope’s variables, preserving their state across calls.  
- Closures enable data encapsulation, allowing private variables that cannot be accessed directly from the outside.  
- They are fundamental for patterns like module creation, function factories, and maintaining state in callbacks.  
- Understanding closures helps avoid common pitfalls such as unexpected variable sharing in loops or memory leaks.

Code Example with comments:
// Define an outer function that creates a private counter
function createCounter() {
    // This variable is local to createCounter and will be captured by the inner function
    var count = 0;

    // The inner function forms a closure over 'count'
    return function increment() {
        // Each call updates the captured 'count' variable
        count++;
        console.log('Current count:', count);
    };
}

// Obtain a new counter instance; 'counter' holds the inner function with its own closure
var counter = createCounter();

counter(); // Output: Current count: 1
counter(); // Output: Current count: 2

// Create a second independent counter to demonstrate separate closures
var anotherCounter = createCounter();
anotherCounter(); // Output: Current count: 1 (independent from the first counter)
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models (LLMs)

Explanation:
Few‑shot prompting lets you guide an LLM’s behavior by providing a handful of example input‑output pairs directly in the prompt. This approach is model‑agnostic and works without any fine‑tuning, making it ideal for rapid prototyping. By carefully selecting diverse examples that illustrate the desired pattern, you can improve consistency and reduce hallucinations. The technique also allows you to inject domain‑specific knowledge or style constraints on the fly. When combined with temperature control and token limits, few‑shot prompts become a powerful tool for building reliable AI assistants.

Code example (Python, using OpenAI’s ChatCompletion API):

import os
import json
import openai

# Load your API key from an environment variable or a config file
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt that teaches the model how to rewrite sentences in a formal tone
system_message = {"role": "system", "content": "You are a helpful assistant that rewrites informal sentences into formal, business‑appropriate language."}

example_user_1 = {"role": "user", "content": "Hey, can you send me the report ASAP?"}
example_assistant_1 = {"role": "assistant", "content": "Could you please send me the report at your earliest convenience?"}

example_user_2 = {"role": "user", "content": "I need that data now."}
example_assistant_2 = {"role": "assistant", "content": "I would appreciate receiving the data as soon as possible."}

# The new user query we want to rewrite
new_user = {"role": "user", "content": "Give me the numbers for last quarter."}

# Assemble the full message list in the order they should be processed
messages = [
    system_message,
    example_user_1,
    example_assistant_1,
    example_user_2,
    example_assistant_2,
    new_user
]

# Call the ChatCompletion endpoint with a low temperature for deterministic output
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # or any model you have access to
    messages=messages,
    temperature=0.2,              # lower temperature = more predictable results
    max_tokens=150
)

# Extract and print the rewritten sentence
rewritten = response["choices"][0]["message"]["content"]
print("Formal rewrite:", rewritten)
*/

