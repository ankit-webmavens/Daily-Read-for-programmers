<?php
// 2026-11-01 08:02:49

/* PHP
Topic: PDO Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a uniform interface for accessing many different databases. Using prepared statements with PDO separates SQL code from data, which prevents SQL injection attacks. The statement is parsed by the database server once, then can be executed multiple times with different parameters, improving performance for repeated queries. Placeholders in the SQL are bound to PHP variables, allowing automatic data type handling and proper escaping. Errors can be caught with exceptions, making debugging easier and keeping the application stable.  

Code example:  

<?php
// Enable exceptions for PDO errors
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash) VALUES (:email, :pwd_hash)');

    // Data to be inserted
    $email = 'alice@example.com';
    $pwdHash = password_hash('secret123', PASSWORD_BCRYPT);

    // Bind values to the placeholders and execute
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pwd_hash', $pwdHash);
    $stmt->execute();

    echo "User inserted successfully.";
} catch (PDOException $e) {
    // Handle any errors
    echo 'Database error: ' . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs  

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or interacting with external APIs, keeping the user experience fast.  
A job class encapsulates the logic for a single unit of work and can be dispatched to any configured queue driver (database, Redis, SQS, etc.).  
When a job is dispatched, Laravel serializes the class and stores it in the queue, where a worker process later retrieves and executes it.  
Failed jobs are automatically logged and can be retried, providing robust error handling for background processing.  
Using queues also enables horizontal scaling by adding more worker processes without changing the application code.  

Code example (Job class and dispatching it):  

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WelcomeMail;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // The user instance to receive the email

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user; // Store user data for later processing
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

// Somewhere in a controller or service after a user registers:
use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    $user = User::create($request->only(['name', 'email', 'password']));
    
    // Dispatch the job to the default queue
    SendWelcomeEmail::dispatch($user);
    
    return response()->json(['message' => 'Registration successful, email will be sent shortly.']);
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
- A CTE is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.
- It improves readability by allowing you to break complex queries into logical building blocks.
- CTEs are defined using the WITH clause and can be either non‑recursive or recursive.
- Recursive CTEs are useful for hierarchical data such as organizational charts or tree structures.
- In MySQL 8.0+ you can use CTEs to replace subqueries and simplify joins, especially when dealing with multi‑level relationships.

Code Example (with comments):
WITH RECURSIVE org_chart AS (
    -- Anchor member: start with the top‑level manager (no manager_id)
    SELECT employee_id, name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: find employees reporting to the previous level
    SELECT e.employee_id, e.name, e.manager_id, oc.level + 1
    FROM employees e
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id
)
SELECT employee_id, name, manager_id, level
FROM org_chart
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Closures and Lexical Scoping in JavaScript

Explanation:
- A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
- JavaScript creates closures automatically whenever a function is defined inside another function.  
- The inner function forms a closure over the variables of the outer function’s scope, preserving their values across multiple calls.  
- Closures enable powerful patterns such as data encapsulation, private state, and function factories.  
- Understanding closures is essential for writing modular, memory‑efficient, and maintainable code.

Code example (with comments):

function makeCounter(start) {               // outer function, creates a private variable
    let count = start;                     // this variable is captured by the inner function
    return function increment(step) {      // inner function forms a closure over count
        count += step;                     // can modify the captured variable
        console.log('Current count:', count);
        return count;                      // returns the updated value
    };
}

// Create a new counter starting at 10
const counterA = makeCounter(10);
counterA(2);   // Output: Current count: 12
counterA(3);   // Output: Current count: 15

// Create another independent counter starting at 0
const counterB = makeCounter(0);
counterB(5);   // Output: Current count: 5
counterB(1);   // Output: Current count: 6

// Each counter maintains its own private 'count' variable because of closures.
*/

/* AI
Topic: Chain‑of‑Thought Prompt Engineering for Complex Reasoning

Explanation:
Chain‑of‑thought (CoT) prompting guides large language models to generate intermediate reasoning steps before arriving at a final answer, improving performance on tasks that require multi‑step logic. By structuring the prompt with explicit “Let's think step by step” cues, the model is encouraged to break down the problem, making its internal chain of deductions visible. This technique works well for math word problems, logical puzzles, and code explanation tasks. The prompt can be combined with few‑shot examples that demonstrate the desired reasoning pattern, allowing the model to generalize the style to new inputs. Properly crafted CoT prompts reduce hallucination and increase answer correctness without changing model parameters.

Code example (Python, using OpenAI’s ChatCompletion API):

import os
import json
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def chain_of_thought(prompt: str) -> str:
    """
    Sends a prompt to the GPT model with a chain‑of‑thought cue.
    Returns the model's full response, including intermediate steps.
    """
    # Build a few‑shot example that demonstrates the reasoning style
    few_shot = [
        {"role": "system", "content": "You are a helpful assistant that solves problems by reasoning step by step."},
        {"role": "user", "content": "A train travels 60 km/h for 2 hours and then 80 km/h for 3 hours. What is the total distance?"},
        {"role": "assistant", "content": "Let's think step by step.\n"
                                         "1. Distance in first segment = 60 km/h * 2 h = 120 km.\n"
                                         "2. Distance in second segment = 80 km/h * 3 h = 240 km.\n"
                                         "3. Total distance = 120 km + 240 km = 360 km.\n"
                                         "Answer: 360 km."},
        {"role": "user", "content": prompt}
    ]

    # Call the ChatCompletion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # choose an appropriate model
        messages=few_shot,
        temperature=0.2,               # low temperature for deterministic reasoning
        max_tokens=300
    )

    # Extract the assistant’s reply
    answer = response["choices"][0]["message"]["content"]
    return answer.strip()

# Example usage
question = "A rectangle has a length that is three times its width. If the perimeter is 64 cm, what is the area?"
print(chain_of_thought(question))

# The output will include a step‑by‑step breakdown followed by the final area.
*/

