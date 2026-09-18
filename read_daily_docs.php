<?php
// 2026-09-18 06:32:16

/* PHP
Topic: PHP PDO Prepared Statements  

Explanation:  
Prepared statements in PDO separate the SQL query from its data values, which helps prevent SQL injection attacks.  
You first prepare the SQL with placeholders, then bind the actual values before execution.  
PDO supports both named (e.g., :name) and positional (?) placeholders.  
Using prepared statements also allows the database to reuse the execution plan, improving performance for repeated queries.  
Errors are handled via exceptions, making debugging easier and code more robust.  

Code example:  
<?php  
// Create a new PDO instance (replace DSN, username, and password with your own values)  
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'dbuser', 'dbpass');  
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

// Define an INSERT statement with named placeholders  
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)";  

// Prepare the statement once  
$stmt = $pdo->prepare($sql);  

// Bind values to the placeholders and execute the statement  
$stmt->execute([  
    ':username'   => 'johndoe',                // User's username  
    ':email'      => 'john@example.com',      // User's email address  
    ':created_at' => date('Y-m-d H:i:s')      // Current timestamp  
]);  

// Fetch the ID of the newly inserted row  
$newUserId = $pdo->lastInsertId();  
echo "New user inserted with ID: " . $newUserId;  
?>
*/

/* Laravel
Topic Name: Laravel Queues with Redis

Explanation:  
Laravel queues provide a way to defer lengthy or resource‑intensive tasks such as sending emails, processing images, or running API calls. By pushing jobs onto a queue, the main request can return quickly while the work is processed in the background. Redis is a fast, in‑memory data store that Laravel can use as a queue driver, offering low latency and simple setup. Each queued job is serialized, stored in a Redis list, and a worker process pulls jobs off the list to execute them. Configuring queues with Redis improves application responsiveness and scalability, especially under heavy load.

Code Example (Job class and dispatch):

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;   // The ID of the user to email

    // Constructor receives data needed for the job
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    // The handle method contains the logic that will run in the background
    public function handle()
    {
        $user = \App\Models\User::find($this->userId);
        if ($user) {
            // Use Laravel's Mail facade to send the email
            \Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
        }
    }
}

// Dispatching the job somewhere in a controller or service
use App\Jobs\SendWelcomeEmail;

// After creating a new user, push the email job onto the Redis queue
$user = \App\Models\User::create($request->all());
SendWelcomeEmail::dispatch($user->id)->onQueue('emails');

// To start processing jobs, run the queue worker (run this in a terminal)
// php artisan queue:work redis --queue=emails --sleep=3 --tries=3
?>
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTEs) in MySQL

Explanation:
A recursive CTE allows you to perform hierarchical queries such as traversing parent‑child relationships or generating series of numbers. It consists of two parts: an anchor query that provides the initial rows, and a recursive query that references the CTE itself to produce subsequent rows. MySQL evaluates the recursive part repeatedly until it returns no new rows, or a MAX_RECURSION_DEPTH limit is reached. Recursive CTEs are useful for organizational charts, bill‑of‑materials, or date calendars. They were introduced in MySQL 8.0, replacing the need for stored procedures for many hierarchy tasks.

Code example (generate an employee hierarchy and list each employee with its level):

-- Create a sample employees table
CREATE TABLE employees (
    emp_id   INT PRIMARY KEY,
    name     VARCHAR(50),
    manager_id INT NULL   -- NULL means top‑level manager
);

-- Insert sample data
INSERT INTO employees (emp_id, name, manager_id) VALUES
(1, 'Alice', NULL),   -- CEO
(2, 'Bob',   1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve',   2),
(6, 'Frank', 3);

-- Recursive CTE to walk the hierarchy
WITH RECURSIVE emp_hierarchy AS (
    -- Anchor: start with top‑level managers (no manager_id)
    SELECT 
        emp_id,
        name,
        manager_id,
        1 AS level          -- root level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive step: join children to their parents
    SELECT 
        e.emp_id,
        e.name,
        e.manager_id,
        h.level + 1 AS level
    FROM employees e
    JOIN emp_hierarchy h ON e.manager_id = h.emp_id
)
SELECT 
    emp_id,
    name,
    manager_id,
    level
FROM emp_hierarchy
ORDER BY level, manager_id, emp_id;

-- The result shows each employee, their manager, and the depth (level) in the hierarchy.  
*/

/* JavaScript
Topic Name: Async/Await for Managing Asynchronous Code  

Explanation:  
Async/Await is syntactic sugar built on top of JavaScript Promises, allowing asynchronous operations to be written in a synchronous style. Declaring a function with the async keyword makes it return a Promise automatically, and the await keyword pauses execution until the awaited Promise resolves or rejects. This improves readability by eliminating deeply nested .then() chains and makes error handling straightforward with try/catch blocks. It works in modern browsers and Node.js environments, but the underlying Promise behavior remains unchanged. Use async/await when you need to perform sequential asynchronous tasks or when you want clearer flow control in asynchronous code.

Code Example:
// Simulate a network request that resolves after a delay
function fetchData(url) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            // For demonstration, resolve with a simple object
            resolve({ data: `Response from ${url}` });
        }, 1000);
    });
}

// Async function that uses await to get the data
async function loadData() {
    try {
        console.log('Fetching data...');
        const result = await fetchData('https://api.example.com/items');
        // Execution pauses above until the Promise resolves
        console.log('Data received:', result.data);
    } catch (error) {
        // Any rejection from fetchData is caught here
        console.error('Error fetching data:', error);
    }
}

// Invoke the async function
loadData();   // Output appears after ~1 second delay.
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Classification with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets a language model learn a new task from only a handful of labeled examples provided in the prompt. By carefully formatting the examples and instructions, you can guide the model to produce accurate classifications without any fine‑tuning. This approach is especially useful when you have limited data or need rapid prototyping. The prompt typically includes a clear task description, several example pairs (input → label), and then the new input whose label the model should infer. Adjusting delimiters, temperature, and max tokens can further improve consistency and reduce hallucinations.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from environment  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_text(text):  
    # Build a few‑shot prompt with three examples  
    prompt = """You are a helpful assistant that classifies customer messages into categories.  
Categories: Complaint, Praise, Question.  

Example 1:  
Message: "I received the wrong item in my order."  
Label: Complaint  

Example 2:  
Message: "Your support team resolved my issue quickly, thank you!"  
Label: Praise  

Example 3:  
Message: "When will the new product be released?"  
Label: Question  

Now classify the following message:  
Message: """ + f'"{text}"' + """  
Label:"""  

    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[{"role": "user", "content": prompt}],  
        temperature=0.0,          # deterministic output  
        max_tokens=10,            # only need the label  
        top_p=1,  
        n=1,  
    )  

    # The model returns the label as the first choice's message content  
    label = response.choices[0].message.content.strip()  
    return label  

# Example usage  
if __name__ == "__main__":  
    new_message = "The website keeps crashing when I try to checkout."  
    print("Predicted label:", classify_text(new_message))  
*/

