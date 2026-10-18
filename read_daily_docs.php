<?php
// 2026-10-18 06:31:06

/* PHP
Topic: Secure Database Queries with PDO and Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a uniform interface for accessing many different databases, making code portable and easier to maintain. Using prepared statements with named or positional placeholders separates SQL logic from data, which prevents SQL injection attacks. The driver sends the statement template to the database first, then binds the actual values, allowing the database to treat them strictly as data. PDO also supports error handling via exceptions, giving developers fine‑grained control over failure cases. This approach is recommended for any application that interacts with a relational database, especially when handling user‑supplied input.  

Code example with comments:  
<?php  
// Database connection parameters  
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';  
$username = 'dbuser';  
$password = 'dbpass';  

try {  
    // Create a new PDO instance  
    $pdo = new PDO($dsn, $username, $password);  
    // Configure PDO to throw exceptions on error  
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

    // Prepare an INSERT statement with named placeholders  
    $stmt = $pdo->prepare('INSERT INTO users (email, password_hash) VALUES (:email, :hash)');  

    // Values to be inserted – could come from user input  
    $email = 'user@example.com';  
    $hash = password_hash('secret123', PASSWORD_DEFAULT);  

    // Bind the PHP variables to the statement placeholders  
    $stmt->bindParam(':email', $email);  
    $stmt->bindParam(':hash', $hash);  

    // Execute the prepared statement  
    $stmt->execute();  

    echo 'User added successfully';  
} catch (PDOException $e) {  
    // Output or log the error message  
    echo 'Database error: ' . $e->getMessage();  
}  
?>
*/

/* Laravel
Topic: Laravel Queues with Redis

Explanation:  
Laravel queues allow time‑consuming tasks (such as sending emails or processing images) to run in the background, keeping web requests fast.  
Redis is a fast, in‑memory data store that Laravel can use as a queue driver, providing high throughput and low latency.  
To use Redis queues you configure the driver, create a job class, and dispatch the job; a queue worker then processes jobs asynchronously.  
Laravel’s queue system handles retries, failures, and job timeouts automatically, making it reliable for production workloads.  
Using Redis also enables you to monitor queue lengths and worker status via Redis CLI tools.

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

    protected $user;

    // Job receives the user instance when dispatched
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Example email sending logic
        Mail::to($this->user->email)->send(new \App\Mail\WelcomeMail($this->user));
    }
}

// Dispatching the job from a controller or service
// The job will be pushed onto the Redis queue named 'default'
$user = \App\Models\User::find(1);
SendWelcomeEmail::dispatch($user);

--- Configuration (config/queue.php) ---
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],

--- Starting a worker ---
php artisan queue:work redis --queue=default --sleep=3 --tries=3

The worker will continuously pull jobs from the Redis list, execute the handle() method, and remove the job when completed.
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. They are defined using the WITH clause and can be recursive, enabling hierarchical data processing such as traversing parent‑child relationships. Recursive CTEs consist of an anchor member (the base case) and a recursive member that references the CTE itself, iterating until no new rows are produced. This feature is especially useful for organizational charts, bill‑of‑materials, or any tree‑structured data.

Code example (recursive CTE to list an employee hierarchy):

WITH RECURSIVE emp_hierarchy AS (  
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE employee_id = 1  

    UNION ALL  

    -- Recursive member: find direct reports of the previous level  
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1  
    FROM employees e  
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM emp_hierarchy  
ORDER BY level, employee_id;  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to variables from its lexical scope even after that outer function has finished executing.  
Closures enable data privacy, allowing you to create private variables that cannot be accessed directly from outside.  
They are created automatically whenever an inner function references a variable from an outer function.  
Common use‑cases include function factories, memoization, and managing state in event handlers.  
Understanding closures is essential for mastering asynchronous patterns and functional programming in JavaScript.  

Code example:  
function makeCounter() {  
    let count = 0;                     // private variable, not accessible outside  
    return function() {                // inner function forms a closure  
        count++;                       // can read and modify 'count' from outer scope  
        console.log(count);            // each call logs an incremented value  
    };                                 // the returned function retains access to 'count'  
}  

const counterA = makeCounter(); // creates a new closure with its own 'count'  
counterA(); // 1  
counterA(); // 2  

const counterB = makeCounter(); // separate closure, independent 'count'  
counterB(); // 1   (counterB's count starts from 0)  
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI Completion API  

Explanation:  
Few‑shot prompting lets a language model learn a new task by seeing a handful of input‑output examples embedded in the prompt. The model infers the pattern and applies it to new inputs without any parameter updates. Selecting clear, concise examples and a consistent format improves reliability. Including an explicit instruction line helps the model understand the expected behavior. This technique works well for classification, transformation, or extraction tasks where fine‑tuning is not feasible.  

Code example (Python, using openai library):  

import openai  

# Set your API key (ensure it is kept secure)  
openai.api_key = "YOUR_API_KEY"  

# Define a few‑shot prompt for sentiment classification  
examples = """\
Review: I loved the movie, it was fantastic!  
Sentiment: Positive  

Review: The plot was boring and predictable.  
Sentiment: Negative  

Review: It was okay, not great but not terrible.  
Sentiment: Neutral  

"""  

# New user input we want classified  
new_review = "The acting was superb, but the story fell flat."  

# Assemble the final prompt by appending the new instance without the label  
prompt = examples + f"Review: {new_review}\nSentiment:"  

# Call the OpenAI completion endpoint  
response = openai.Completion.create(  
    model="text-davinci-003",  
    prompt=prompt,  
    max_tokens=5,  
    temperature=0.0,   # deterministic output for classification  
    stop=["\\n"]       # stop at end of label line  
)  

# Extract and print the predicted sentiment  
predicted_sentiment = response.choices[0].text.strip()  
print(f"Predicted sentiment: {predicted_sentiment}")  
*/

