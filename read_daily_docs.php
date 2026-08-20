<?php
// 2026-08-20 02:41:38

/* PHP
Prepared Statements with PDO

PDO (PHP Data Objects) provides a uniform interface for accessing many different databases.  
Prepared statements separate SQL logic from data, which protects against SQL injection attacks.  
They also improve performance when the same query is executed repeatedly with different values.  
You prepare the statement once, then bind values and execute it as many times as needed.  
Using PDO exceptions gives you a clean way to handle database errors.

<?php
// Create PDO connection (replace placeholders with actual credentials)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    // Enable exceptions for error handling
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');

    // Sample data to insert
    $email = 'alice@example.com';
    // Password should be hashed before storing
    $hashedPassword = password_hash('secret123', PASSWORD_DEFAULT);

    // Bind parameters and execute the statement
    $stmt->execute([
        ':email' => $email,
        ':password' => $hashedPassword
    ]);

    echo 'User inserted successfully.';
} catch (PDOException $e) {
    // Handle any errors
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Laravel Topic: Queues and Jobs  

Explanation:  
Laravel queues allow time‑consuming tasks to be processed in the background, keeping web requests fast.  
A job class represents a single unit of work and can be dispatched to any configured queue driver.  
The queue system automatically handles retries, failures, and can be monitored via the built‑in dashboard.  
Using queues you can offload email sending, image processing, API calls, and other heavy operations.  
Laravel provides simple artisan commands to generate jobs and to run workers that process the queued jobs.

Code Example (app/Jobs/SendWelcomeEmail.php):
<?php
namespace App\Jobs;

use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Indicates the job should be queued
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // The user instance to receive the email

    // Constructor receives the data needed for the job
    public function __construct($user)
    {
        $this->user = $user;
    }

    // The logic that will be executed by the queue worker
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}
?>

Dispatching the job (e.g., in a controller after registration):
<?php
use App\Jobs\SendWelcomeEmail;

// After creating the user...
$user = User::create($request->all());

// Dispatch the job to the default queue
SendWelcomeEmail::dispatch($user);
?>
When you run the worker (e.g., php artisan queue:work), Laravel will pick up the job from the queue, execute the handle method, and send the welcome email asynchronously.
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and improves readability by allowing you to break complex queries into logical building blocks. MySQL supports both non‑recursive and recursive CTEs starting from version 8.0. Recursive CTEs enable hierarchical data processing such as traversing tree structures. CTEs are scoped to the statement they belong to, so they do not persist beyond the execution of that statement. Using CTEs can also help the optimizer generate more efficient execution plans compared with deeply nested subqueries.

Code example with comments:

WITH RECURSIVE org_chart AS (                                   -- define a recursive CTE named org_chart
    SELECT employee_id, manager_id, employee_name, 0 AS level   -- anchor member: top‑level employees
    FROM employees
    WHERE manager_id IS NULL                                    -- root nodes (no manager)
    
    UNION ALL                                                    -- combine anchor with recursive part
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1
    FROM employees e                                            -- recursive member: fetch direct reports
    JOIN org_chart oc ON e.manager_id = oc.employee_id
)
SELECT employee_id, manager_id, employee_name, level
FROM org_chart
ORDER BY level, manager_id;                                      -- final query reads the hierarchy in order.
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing. This allows the inner function to retain a reference to the outer scope’s variables, enabling data encapsulation and private state. Closures are fundamental for creating function factories, maintaining state across calls, and implementing patterns like the module pattern. They work because JavaScript functions form lexical environments that preserve the scope chain. Understanding closures helps avoid common pitfalls such as unintended memory retention or variable capture in loops.

Code Example:
// Function that returns a counter function (closure)
function createCounter(start) {
    // 'count' is a variable in the outer function's scope
    let count = start;

    // The inner function forms a closure over 'count'
    return function() {
        // Each call increments and returns the current count
        count += 1;
        return count;
    };
}

// Create two independent counters
const counterA = createCounter(0);
const counterB = createCounter(10);

// Use the counters
console.log(counterA()); // 1
console.log(counterA()); // 2
console.log(counterB()); // 11
console.log(counterB()); // 12
console.log(counterA()); // 3   // counterA maintains its own private 'count' variable.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI GPT‑4 API  

Explanation:  
Few‑shot prompting supplies a small set of example input‑output pairs inside the prompt, guiding the model toward the desired behavior without fine‑tuning.  
By framing the task as a pattern that the model can recognize, you can achieve higher accuracy on classification, transformation, or generation tasks.  
The technique works well for zero‑to‑few‑shot scenarios, especially when labeled data is scarce.  
Key elements include a clear instruction, representative examples, and a delimiter that separates the examples from the new query.  
When combined with temperature control, you can balance consistency and creativity for robust results.  

Code example (Python, using the OpenAI library):  

import os  
import openai  

# Set your OpenAI API key; you can also configure it via the OPENAI_API_KEY environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Construct a few‑shot prompt with two labeled examples and a placeholder for the new input  
    prompt = (  
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"  
        "Sentence: I love the new design of the app.\n"  
        "Sentiment: Positive\n\n"  
        "Sentence: The update broke the login feature.\n"  
        "Sentiment: Negative\n\n"  
        f"Sentence: {text}\n"  
        "Sentiment:"  
    )  

    response = openai.ChatCompletion.create(  
        model="gpt-4",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,          # Low temperature for deterministic output  
        max_tokens=10,            # Only need a short label  
        top_p=1,  
        n=1,  
        stop=["\n"]               # Stop at the end of the label line  
    )  

    # Extract the model's answer and strip whitespace  
    sentiment = response.choices[0].message["content"].strip()  
    return sentiment  

# Example usage  
sample = "The tutorial was okay, not great but helpful enough."  
print(f"Input: {sample}")  
print("Predicted sentiment:", classify_sentiment(sample))
*/

