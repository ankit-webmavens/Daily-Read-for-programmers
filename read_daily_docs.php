<?php
// 2026-09-19 06:31:00

/* PHP
PHP Topic: PDO Prepared Statements  

Explanation:  
- PDO (PHP Data Objects) provides a consistent interface for accessing many different databases.  
- Prepared statements separate the SQL code from the data, which helps prevent SQL injection attacks.  
- They allow the database server to parse and compile the query once, then execute it multiple times with different parameters, improving performance.  
- Using named or positional placeholders makes the code more readable and maintainable.  
- Errors can be handled gracefully with exceptions, giving you full control over error reporting.  

Code example (with comments):  

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO connection (replace DSN, user, and password with your own)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'secret';
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) 
        VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute
$data = [
    ':username' => 'alice',
    ':email'    => 'alice@example.com',
];
$stmt->execute($data);

// Prepare a SELECT statement with a positional placeholder
$selectSql = "SELECT id, username, email FROM users WHERE username = ?";
$selectStmt = $pdo->prepare($selectSql);

// Execute the SELECT with the value for the placeholder
$selectStmt->execute(['alice']);

// Fetch and display the result
$user = $selectStmt->fetch();
if ($user) {
    echo "User ID: " . $user['id'] . PHP_EOL;
    echo "Username: " . $user['username'] . PHP_EOL;
    echo "Email: " . $user['email'] . PHP_EOL;
} else {
    echo "No user found." . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Queues with Redis

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or calling external APIs. By using Redis as the queue driver you get fast, in‑memory storage that can handle high throughput. Jobs are defined as simple PHP classes that implement the ShouldQueue interface and contain a handle method. Dispatching a job places it onto the Redis list, where a worker process will pick it up and execute the handle method. This decouples the request cycle from heavy processing, improving response times and user experience.

Code Example (Job Class and Dispatch):
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

    protected $user; // The user instance to email

    // Constructor receives data that will be serialized onto the queue payload
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Example: Use a mailable to send the email
        \Mail::to($this->user->email)->send(new \App\Mail\WelcomeMail($this->user));
    }
}

// Somewhere in a controller or service after a new user registers:
public function register(Request $request)
{
    $user = User::create($request->only(['name', 'email', 'password']));

    // Dispatch the job to the default queue (Redis by default in config/queue.php)
    SendWelcomeEmail::dispatch($user);

    return response()->json(['message' => 'User registered, welcome email queued.']);
}

// Queue worker command (run from terminal):
// php artisan queue:work redis --sleep=3 --tries=3

// config/queue.php excerpt to ensure Redis driver is set:
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
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs improve readability by allowing you to break complex queries into logical building blocks. They are defined using the WITH clause and exist only for the duration of the statement. Recursive CTEs enable hierarchical data processing, such as traversing parent‑child relationships. MySQL supports both non‑recursive and recursive CTEs starting with version 8.0.

Code example with comments:
WITH RECURSIVE org_chart AS (  
    -- Anchor member: select the top‑level manager (no parent)  
    SELECT employee_id, name, manager_id, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  
  
    UNION ALL  
  
    -- Recursive member: join each employee with their manager  
    SELECT e.employee_id, e.name, e.manager_id, oc.level + 1  
    FROM employees e  
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, name, manager_id, level  
FROM org_chart  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: Event Delegation in the DOM

Explanation:  
Event delegation leverages the bubbling phase of events to handle actions on multiple child elements with a single parent listener.  
It reduces memory usage because fewer event listeners are attached, which is especially beneficial for large or dynamic lists.  
By checking the event.target, you can determine which specific child triggered the event and respond accordingly.  
This pattern also simplifies adding or removing child elements without needing to reassign listeners.  
Overall, event delegation leads to cleaner, more maintainable code for interactive interfaces.

Code Example:
// Parent container that holds many list items
const listContainer = document.getElementById('item-list');

// Attach a single click listener to the parent
listContainer.addEventListener('click', function(event) {
  // Use event.target to identify the actual clicked element
  const clickedItem = event.target;
  
  // Ensure the click originated from a list item (not the container itself)
  if (clickedItem && clickedItem.matches('li.item')) {
    // Perform the desired action, e.g., toggle a selected class
    clickedItem.classList.toggle('selected');
    console.log('Item clicked:', clickedItem.textContent);
  }
});

// Example HTML structure (for context):
// <ul id="item-list">
//   <li class="item">Item 1</li>
//   <li class="item">Item 2</li>
//   <li class="item">Item 3</li>
// </ul)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of input–output examples inside the prompt, teaching it the desired pattern without any parameter updates. By carefully formatting the examples and using clear instruction text, the model can generalize to new inputs with high accuracy. This technique works especially well with Chat‑style models that understand system and user roles. You can dynamically construct the prompt in code, allowing you to adapt the examples based on the task. Proper token budgeting is crucial; keep the total length within the model’s context window to avoid truncation.

Code example (Python, using the openai library):  

import os  
import openai  

# Load your API key from the environment  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt: system message + two examples + new query  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that converts English sentences into Pig Latin."},  
    {"role": "user", "content": "Translate: I love programming."},  
    {"role": "assistant", "content": "Iway ovelay ogrammingpray."},  
    {"role": "user", "content": "Translate: The quick brown fox jumps over the lazy dog."},  
    {"role": "assistant", "content": "Ethay uickqay rownbay oxfay umpsjay overway ethay azylay ogday."},  
    {"role": "user", "content": "Translate: Machine learning is fascinating."}  
]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",            # choose a model that supports chat  
    messages=messages,              # send the constructed prompt  
    temperature=0.2,                # low temperature for deterministic output  
)  

# Extract and print the assistant's answer  
answer = response["choices"][0]["message"]["content"]  
print("Pig Latin:", answer)  
*/

