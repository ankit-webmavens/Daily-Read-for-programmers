<?php
// 2026-08-23 02:48:03

/* PHP
Topic: PHP PDO Prepared Statements

Explanation:  
Prepared statements separate SQL code from data, protecting against SQL injection attacks.  
PDO (PHP Data Objects) provides a consistent interface for working with many database systems.  
You prepare the query once, bind parameters, and then execute it multiple times with different values.  
This approach also improves performance when the same statement is executed repeatedly.  
Error handling with exceptions allows you to catch and manage database errors cleanly.

Code example with comments:  

<?php
// Create a new PDO instance with DSN, username, and password
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    // Enable exceptions for error handling
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');

    // Bind values to the placeholders and execute
    $stmt->execute([
        ':username' => 'alice',
        ':email'    => 'alice@example.com'
    ]);

    // You can reuse the same prepared statement with different data
    $stmt->execute([
        ':username' => 'bob',
        ':email'    => 'bob@example.com'
    ]);

    echo "Records inserted successfully.";
} catch (PDOException $e) {
    // Handle any errors that occur during the database operations
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or interacting with external APIs to a background worker. This improves response time for end users because the request can finish while the job runs asynchronously. Queues are configured with drivers like database, Redis, or Amazon SQS, and each job class defines a handle method that contains the work to be performed. You can dispatch jobs directly from controllers, events, or any other part of the application. Laravel also provides built-in retry, timeout, and failure handling mechanisms to make background processing reliable.

Code example (Job class and dispatching it):

<?php
namespace App\Jobs;

use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;   // The user instance that will receive the email

    // Constructor receives data needed for the job
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }

    // Optional: define how many times the job may be attempted
    public $tries = 3;

    // Optional: define the number of seconds the job can run before it times out
    public $timeout = 120;
}

// Dispatching the job from a controller method
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        // Validate and create the user...
        $user = User::create($request->only(['name', 'email', 'password']));

        // Dispatch the email job to the default queue
        SendWelcomeEmail::dispatch($user);

        return response()->json(['message' => 'Registration successful.']);
    }
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation: 
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. 
MySQL supports both non‑recursive and recursive CTEs, enabling you to write clearer and more maintainable queries. 
Recursive CTEs are especially useful for traversing hierarchical data such as organizational charts or category trees. 
The CTE is defined using the WITH clause, and the recursion terminates when the anchor query no longer produces new rows. 
You can reference the CTE multiple times in the outer query, treating it like a regular table or view.

Code example with comments:
-- Create a sample table to hold an employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL   -- NULL for top‑level manager
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),      -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'Dave', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to list each employee with their management chain depth
WITH RECURSIVE emp_hierarchy AS (
    -- Anchor member: start with top‑level managers (no manager_id)
    SELECT 
        emp_id,
        emp_name,
        manager_id,
        1 AS level   -- root level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join employees to their direct reports
    SELECT 
        e.emp_id,
        e.emp_name,
        e.manager_id,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id
)
SELECT 
    emp_id,
    emp_name,
    manager_id,
    level
FROM emp_hierarchy
ORDER BY level, emp_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to the variables from its lexical scope even after that outer function has finished executing. This allows the inner function to remember the environment in which it was created, enabling data encapsulation and private state. Closures are created every time a function is defined, and they capture the variables they reference. They are frequently used for module patterns, currying, and event handlers. Understanding closures helps avoid common pitfalls such as unintended variable sharing across iterations.

Code example with comments:  
function makeCounter(initialValue) {  
    let count = initialValue; // private variable, not accessible from outside  

    // The inner function forms a closure over `count`  
    return function() {  
        count += 1;          // modifies the captured variable  
        return count;        // returns the updated value  
    };  
}  

// Create two independent counters  
const counterA = makeCounter(0);  
const counterB = makeCounter(10);  

console.log(counterA()); // 1  
console.log(counterA()); // 2  
console.log(counterB()); // 11  
console.log(counterB()); // 12   // each counter maintains its own private `count` variable  
*/

/* AI
Topic: Few-Shot Prompt Engineering with the OpenAI GPT‑4 API

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs before the actual query, guiding it toward the desired behavior without any fine‑tuning. By carefully crafting the examples, you can shape the style, format, or domain of the response. This technique works well for tasks like translation, code generation, or data extraction, where the model can infer the pattern from the demos. The prompt is passed as a single string to the ChatCompletion endpoint, and the model completes the next instance. Adjusting temperature and max_tokens helps control creativity and length of the output.

Code example (Python, using the OpenAI API):
import os
import openai

# Load your API key from an environment variable for security
openai.api_key = os.getenv("OPENAI_API_KEY")

def get_completion(prompt: str) -> str:
    # Call the ChatCompletion endpoint with the constructed prompt
    response = openai.ChatCompletion.create(
        model="gpt-4",                       # Choose the appropriate model
        messages=[{"role": "system", "content": "You are a helpful assistant."},
                  {"role": "user",   "content": prompt}],
        temperature=0.7,                     # Balance between deterministic and creative output
        max_tokens=200                       # Limit the length of the response
    )
    # Extract and return the text part of the assistant's reply
    return response["choices"][0]["message"]["content"].strip()

# Few‑shot prompt template for English‑to‑French translation
few_shot_template = """
Q: Translate the following English sentence to French.
A: The cat sits on the mat. -> Le chat s'assoit sur le tapis.

Q: Translate the following English sentence to French.
A: The sky is blue. -> Le ciel est bleu.

Q: Translate the following English sentence to French.
A: {sentence}
"""

# Sentence we want translated
sentence = "Artificial intelligence is transforming the world."

# Insert the target sentence into the prompt
filled_prompt = few_shot_template.format(sentence=sentence)

# Get and print the model's translation
print(get_completion(filled_prompt))
*/

