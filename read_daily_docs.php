<?php
// 2026-11-19 09:11:30

/* PHP
Topic: PDO Prepared Statements for Secure Database Queries

Explanation:
PDO (PHP Data Objects) provides a uniform interface for accessing many different databases. Using prepared statements with PDO helps prevent SQL injection by separating the query structure from the data. Placeholders are used in the SQL string, and values are bound later, allowing the database driver to handle proper escaping. PDO also supports named and positional placeholders, giving flexibility in how parameters are referenced. Errors can be managed with exceptions, making debugging easier and improving code reliability.

Code example:
<?php
// Create a new PDO instance with error mode set to exceptions
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$usernameValue = 'jane_doe';
$emailValue = 'jane@example.com';
$stmt->bindParam(':username', $usernameValue, PDO::PARAM_STR);
$stmt->bindParam(':email', $emailValue, PDO::PARAM_STR);

// Execute the statement safely
$stmt->execute();

// Retrieve the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: " . $newUserId;
?>
*/

/* Laravel
Laravel Queues with Redis  
Explanation:  
Laravel queues allow time‑consuming tasks to be processed in the background, keeping web requests fast. By configuring the queue driver to use Redis, you get a fast, in‑memory data store that handles high‑throughput jobs efficiently. Jobs are defined as plain PHP classes that implement the ShouldQueue contract. Dispatching a job simply pushes a payload onto a Redis list, which a worker process later pulls and executes. This setup is ideal for sending emails, processing uploads, or generating reports without blocking the user experience.

Example – creating and dispatching a job

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail; // facade for sending emails

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // the user instance to email

    // Constructor receives the data needed by the job
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the worker when the job is processed
    public function handle()
    {
        // Send the welcome email using a Mailable class
        Mail::to($this->user->email)->send(new \App\Mail\WelcomeMail($this->user));
    }
}

// Somewhere in a controller or service, dispatch the job
use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    $user = User::create($request->all());

    // Push the job onto the Redis queue named "default"
    SendWelcomeEmail::dispatch($user); // job is now queued, not executed immediately

    return response()->json(['message' => 'User registered, email queued.']);
}

// config/queue.php – ensure Redis driver is set
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
];

// Start a worker to process the queued jobs
// Run this command in the terminal:
// php artisan queue:work redis --sleep=3 --tries=3

// The worker will continuously poll the Redis list, pop jobs, and execute their handle() method.
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTE) for Hierarchical Queries

Explanation:  
A recursive CTE lets you query hierarchical or tree‑structured data such as organization charts, category trees, or bill‑of‑materials without writing procedural loops. The CTE consists of two parts: an anchor query that returns the root rows, and a recursive query that repeatedly joins the previous result set to fetch child rows. MySQL evaluates the recursion until the recursive part returns an empty set or a MAX_RECURSIVE_ITERATIONS limit is reached. This technique is efficient because the entire operation is performed in a single SQL statement, allowing you to retrieve all levels of a hierarchy with a single result set. Use the WITH RECURSIVE clause (available since MySQL 8.0) to define the CTE, and you can add a depth column to track the level of each row.

Code example (comments start with --):

WITH RECURSIVE org_chart AS (
    -- Anchor member: select the top‑level manager(s)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS depth
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join each manager to their direct reports
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        oc.depth + 1 AS depth
    FROM employees e
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id
)
SELECT 
    employee_id,
    manager_id,
    employee_name,
    depth
FROM org_chart
ORDER BY depth, manager_id;

-- The result shows every employee with the level (depth) in the hierarchy, starting from the top manager.
*/

/* JavaScript
Topic: Closures

Explanation:  
A closure is a function that retains access to variables from its outer (enclosing) scope even after that outer function has finished executing.  
It enables the inner function to read and modify those captured variables, effectively creating private state.  
Closures are formed automatically whenever a function is defined, but they become powerful when functions are returned from other functions.  
They underpin patterns such as module pattern, function factories, and data encapsulation.  
Mastering closures helps prevent bugs caused by unintended sharing of mutable state across different parts of code.  

Code example with comments:  
function makeCounter() {  
    let count = 0;                     // count is private to this closure  
    return function() {               // the inner function forms a closure over count  
        count += 1;                    // update the captured variable  
        return count;                  // return the current count value  
    };                                // end of inner function  
}                                    // end of makeCounter  

const counterA = makeCounter();      // each call creates a new closure with its own count  
console.log(counterA()); // 1  
console.log(counterA()); // 2  

const counterB = makeCounter();      // a separate closure, independent from counterA  
console.log(counterB()); // 1   (starts at its own initial count)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a few example input‑output pairs inside the prompt, teaching it the desired format and style without any fine‑tuning. This technique works well for tasks like data extraction, text transformation, or generating code snippets. By carefully designing the examples and the final user query, you can guide the model to produce consistent, high‑quality results. The approach is lightweight, requires only API calls, and can be adapted on the fly for different tasks. It is especially useful for programmers who need quick, custom behavior from a large language model.

Code example (Python, using the openai library):
import os
import openai

# Set your OpenAI API key; you can also set the OPENAI_API_KEY env var
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt with two examples of converting plain English to a Python list comprehension
system_message = {"role": "system", "content": "You are a helpful assistant that converts English descriptions of list transformations into Python list comprehensions."}
example1 = {"role": "user", "content": "Create a list of squares of numbers from 1 to 5."}
assistant1 = {"role": "assistant", "content": "[x**2 for x in range(1, 6)]"}
example2 = {"role": "user", "content": "Make a list of even numbers between 10 and 20 inclusive."}
assistant2 = {"role": "assistant", "content": "[x for x in range(10, 21) if x % 2 == 0]"}

# The actual user request we want the model to answer
user_query = {"role": "user", "content": "Generate a list of the first 10 Fibonacci numbers."}

messages = [system_message, example1, assistant1, example2, assistant2, user_query]

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",
    messages=messages,
    temperature=0.0  # deterministic output for code generation
)

# Print the generated Python code
print(response.choices[0].message.content)
*/

