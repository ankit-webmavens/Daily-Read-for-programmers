<?php
// 2026-10-17 06:03:44

/* PHP
Topic: PHP Generators (Yield)

Explanation:
Generators allow you to create iterators without building an entire array in memory.  
When a function contains the keyword `yield`, PHP returns a Generator object that produces values one at a time.  
This is especially useful for processing large data sets, streaming files, or any situation where you want lazy evaluation.  
Each call to `next()` resumes execution right after the previous `yield`, preserving local variable state.  
Generators reduce memory consumption and can improve performance compared to returning large arrays.

Code example (with inline comments):

<?php
// A generator that yields the first n Fibonacci numbers
function fibonacciGenerator(int $n): Generator
{
    $a = 0;               // First number in the sequence
    $b = 1;               // Second number in the sequence
    for ($i = 0; $i < $n; $i++) {
        yield $a;         // Return current number, pause function state
        $temp = $a + $b;  // Compute next number
        $a = $b;          // Shift values forward
        $b = $temp;
    }
}

// Use the generator
foreach (fibonacciGenerator(10) as $index => $value) {
    echo "Fibonacci #{$index}: {$value}\n";
}
?>
*/

/* Laravel
Topic: Laravel Queues with Redis

Explanation: Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or making API calls to a background worker. By using Redis as the queue driver, you get fast in‑memory storage and reliable message delivery. Jobs are defined as classes that implement the ShouldQueue interface, and they can be dispatched from anywhere in your application. The queue worker continuously pulls jobs from Redis, runs them, and automatically handles retries and failures. This setup improves response time for end users and scales horizontally by adding more workers.

Code example (Job class and dispatch):
<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // The user instance to email

    // Job constructor receives the data needed for processing
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the worker
    public function handle()
    {
        // Use Laravel's Mail facade to send the email
        Mail::to($this->user->email)->send(new \App\Mail\WelcomeMail($this->user));
    }

    // Optional: define how many times the job should be retried
    public function retryUntil()
    {
        return now()->addMinutes(10);
    }
}

// Dispatching the job somewhere in a controller or service
use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    $user = User::create($request->all());

    // Push the job onto the Redis queue; it will be processed asynchronously
    SendWelcomeEmail::dispatch($user);

    return response()->json(['message' => 'User registered, welcome email queued.']);
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and improves readability by breaking complex queries into logical building blocks. Recursive CTEs allow you to perform hierarchical or graph‑traversal queries, such as retrieving an employee’s management chain or generating a series of dates. The recursion consists of an anchor member (the base case) and a recursive member that references the CTE itself. Recursive CTEs stop when the recursive member returns no rows, preventing infinite loops.

Code example with comments:
-- Create a sample table to hold an organizational hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),      -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to list the management chain for a given employee (e.g., employee 5)
WITH RECURSIVE management_chain AS (
    -- Anchor member: start with the selected employee
    SELECT emp_id, emp_name, manager_id, 0 AS level
    FROM employees
    WHERE emp_id = 5
    UNION ALL
    -- Recursive member: join back to the employees table to climb up the hierarchy
    SELECT e.emp_id, e.emp_name, e.manager_id, mc.level + 1
    FROM employees e
    INNER JOIN management_chain mc ON e.emp_id = mc.manager_id
)
SELECT emp_id, emp_name, manager_id, level
FROM management_chain
ORDER BY level DESC;

-- The result shows Eve (level 0), her manager Bob (level 1), and the top manager Alice (level 2)  
*/

/* JavaScript
Topic: Async/Await with Error Handling

Explanation:
Async/Await simplifies working with promises by allowing asynchronous code to be written in a synchronous style. It makes error handling more straightforward, because try/catch blocks can be used around awaited calls. When an awaited promise rejects, the control flow jumps to the nearest catch block. This pattern eliminates the need for nested .then() and .catch() chains. It also integrates well with Promise.all for parallel operations while still preserving readable error handling.

Code Example:
async function fetchData(url) {
    try {
        const response = await fetch(url); // wait for the HTTP request
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json(); // parse JSON body
        return data; // resolved value
    } catch (error) {
        console.error('Failed to fetch data:', error); // handle any error
        // Re‑throw if you want the caller to also handle it
        throw error;
    }
}

// Usage
(async () => {
    try {
        const result = await fetchData('https://api.example.com/items');
        console.log('Data received:', result);
    } catch (e) {
        console.log('Outer catch: could not retrieve items.');
    }
})();
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs within the same request, guiding it toward the desired behavior without fine‑tuning. By carefully crafting the examples and using system messages to set context, you can achieve reliable, task‑specific responses. This technique works well for classification, data extraction, or transformation tasks where a full training pipeline would be overkill. It leverages the model’s in‑context learning abilities and can be adjusted on the fly by adding or modifying examples. The approach is inexpensive, fast to iterate, and integrates directly with any OpenAI‑compatible client library.

Code example (Python, using the openai package):

import os
import openai

# Load your API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define the system prompt to set overall behavior
system_message = {
    "role": "system",
    "content": "You are a helpful assistant that extracts the due date from meeting notes."
}

# Few‑shot examples: each user‑assistant pair demonstrates the desired extraction
few_shot = [
    {
        "role": "user",
        "content": "Meeting notes: Discuss project timeline. Action: finalize design by March 15."
    },
    {
        "role": "assistant",
        "content": "Due date: March 15"
    },
    {
        "role": "user",
        "content": "Notes: Budget review scheduled. Follow‑up needed on April 2."
    },
    {
        "role": "assistant",
        "content": "Due date: April 2"
    }
]

# New query we want the model to answer
new_query = {
    "role": "user",
    "content": "Notes: Team agreed to submit the test plan next Friday."
}

# Assemble the full message list
messages = [system_message] + few_shot + [new_query]

# Call the Chat Completion endpoint
response = openai.ChatCompletion.create(
    model="gpt-4o-mini",          # Choose a suitable model
    messages=messages,
    temperature=0.0               # Deterministic output for extraction tasks
)

# Print the extracted due date
print("Model response:", response.choices[0].message.content.strip())
*/

