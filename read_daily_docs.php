<?php
// 2026-09-22 06:44:23

/* PHP
PHP Topic: Using PDO for Secure Database Access

Explanation:
The PHP Data Objects (PDO) extension provides a uniform interface for accessing many different databases. By using prepared statements with bound parameters, PDO helps prevent SQL injection attacks. It supports transactions, enabling you to commit or roll back a group of operations atomically. Error handling can be set to throw exceptions, making debugging easier. PDO also allows you to fetch results as associative arrays, objects, or custom classes for flexible data manipulation.

Code Example (MySQL connection, insert, and fetch):

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace placeholders with real credentials)
$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8mb4', 'db_user', 'db_password', $options);

// Begin a transaction
$pdo->beginTransaction();

try {
    // Prepare an INSERT statement with named placeholders
    $stmt = $pdo->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');

    // Bind values to the placeholders and execute
    $stmt->execute([
        ':username' => 'alice',
        ':email'    => 'alice@example.com',
    ]);

    // Commit the transaction
    $pdo->commit();
} catch (Exception $e) {
    // Something went wrong; roll back changes
    $pdo->rollBack();
    echo 'Error: ' . $e->getMessage();
}

// Prepare a SELECT statement to fetch all users
$stmt = $pdo->prepare('SELECT id, username, email FROM users ORDER BY id ASC');
$stmt->execute();

// Fetch results as an associative array
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Queues with Redis  

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or interacting with external APIs. By default Laravel supports many drivers; Redis is a fast, in‑memory store that works well for high‑throughput applications. Jobs are pushed onto a Redis list and workers pull them off, executing the logic in the background. This improves response time for web requests and makes your application more scalable. You can configure the connection, create a job class, and run workers that listen for new jobs indefinitely.

Code example with comments:

// app/Jobs/SendWelcomeEmail.php  
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

    protected $user;  

    // Job receives the user object when dispatched  
    public function __construct($user)  
    {  
        $this->user = $user;  
    }  

    // The code that runs when the job is processed  
    public function handle()  
    {  
        // Send the welcome email using Laravel's Mailable  
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));  
    }  
}  

// Dispatch the job from a controller or service  
use App\Jobs\SendWelcomeEmail;  

public function register(Request $request)  
{  
    $user = User::create($request->all());  

    // Push the job onto the Redis queue named "default"  
    SendWelcomeEmail::dispatch($user)->onQueue('default');  

    return response()->json(['message' => 'User created, email will be sent shortly']);  
}  

// config/queue.php – configure Redis connection (excerpt)  
'connections' => [  
    'redis' => [  
        'driver' => 'redis',  
        'connection' => 'default',  
        'queue' => env('REDIS_QUEUE', 'default'),  
        'retry_after' => 90,  
        'block_for' => null,  
    ],  
],  

// .env – set Redis queue name if desired  
REDIS_QUEUE=default  

// Run a worker that listens to the Redis queue  
php artisan queue:work redis --queue=default --sleep=3 --tries=3  

// The worker will keep running, pulling jobs from Redis and executing the handle method above.  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical building blocks.  
They can be recursive, enabling hierarchical data processing such as organizational charts or bill‑of‑materials.  
MySQL supports CTEs starting with version 8.0, using the WITH clause placed before the main query.  
You can define multiple CTEs separated by commas, and each can be referenced by name later in the statement.  

Code example:  
-- Define a recursive CTE to list an employee hierarchy  
WITH RECURSIVE emp_hierarchy (emp_id, emp_name, manager_id, level) AS (  
    -- Anchor member: top‑level managers (no manager)  
    SELECT emp_id, emp_name, manager_id, 1  
    FROM employees  
    WHERE manager_id IS NULL  
    UNION ALL  
    -- Recursive member: find direct reports of the previous level  
    SELECT e.emp_id, e.emp_name, e.manager_id, eh.level + 1  
    FROM employees e  
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.emp_id  
)  
SELECT emp_id, emp_name, manager_id, level  
FROM emp_hierarchy  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing. The inner function retains a reference to the outer scope's variables, forming a persistent lexical environment. Closures enable data encapsulation, allowing private state that cannot be accessed directly from the outside. They are commonly used for factory functions, module patterns, and maintaining state in callbacks. Understanding closures is essential for mastering asynchronous code and functional programming techniques in JavaScript.  

Code Example:  
function makeCounter(initial) {                // outer function creates a private variable  
    let count = initial;                       // this variable is captured by the inner function  

    return function increment(step = 1) {      // inner function forms a closure over 'count'  
        count += step;                         // modifies the captured variable  
        console.log(`Current count: ${count}`); // side‑effect: logs the current value  
        return count;                          // returns the updated count  
    };                                          // the inner function is returned and keeps access to 'count'  
}                                               // end of outer function  

const counterA = makeCounter(0);                // counterA has its own independent 'count'  
counterA();          // Current count: 1  
counterA(5);         // Current count: 6  

const counterB = makeCounter(10);               // a separate closure with a different starting value  
counterB(2);         // Current count: 12  
counterB();          // Current count: 13  

// Each call to makeCounter creates a new lexical environment, so counterA and counterB maintain separate private states.  
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with Large Language Models  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs within the same request, guiding it to perform a new task without fine‑tuning.  
By carefully formatting the examples and clearly separating the new query, the model can infer the pattern and generate accurate responses.  
Key design choices include consistent indentation, using delimiters (e.g., "---") to separate examples, and explicitly stating the task.  
This technique works well for classification, transformation, or reasoning tasks where labeled data is scarce.  
In practice, you embed the few‑shot prompt into the API call and let the model complete the pattern for the unseen input.  

Code example (Python, OpenAI API):  

import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt for sentiment classification  
few_shot_prompt = """Classify the sentiment of the given sentence as Positive, Negative, or Neutral.  
Example 1:  
Sentence: I love the new design of the app!  
Sentiment: Positive  

Example 2:  
Sentence: The update crashed my phone repeatedly.  
Sentiment: Negative  

Example 3:  
Sentence: The tutorial was okay, nothing special.  
Sentiment: Neutral  

Now classify the following sentence:  
Sentence: The battery life could be better.  
Sentiment:"""  

# Call the ChatCompletion endpoint with the constructed prompt  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=[{"role": "user", "content": few_shot_prompt}],  
    temperature=0.0,  # deterministic output for classification  
    max_tokens=10  
)  

# Extract and print the model's answer  
answer = response.choices[0].message.content.strip()  
print("Predicted Sentiment:", answer)  
*/

