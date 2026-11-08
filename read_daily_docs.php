<?php
// 2026-11-08 07:53:24

/* PHP
PHP Topic: PDO Prepared Statements  

Explanation:  
Prepared statements in PDO allow you to execute the same SQL query multiple times with different parameters while keeping the query structure separate from the data. This separation helps protect against SQL injection, because user‑supplied values are sent to the database engine separately from the SQL code. PDO also provides a consistent API for different database drivers, making your code portable. You can bind parameters by position or by name, and you can fetch results in various formats. Using prepared statements also improves performance when the same query is run many times, because the database can reuse the execution plan.

Code example (with comments):
<?php
// Create a new PDO instance (replace DSN, username, password with your own)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute
$stmt->execute([
    ':username' => 'alice',
    ':email'    => 'alice@example.com'
]);

// Prepare a SELECT statement with a positional placeholder
$selectSql = 'SELECT id, username, email FROM users WHERE id > ?';
$selectStmt = $pdo->prepare($selectSql);

// Execute the statement with a parameter (e.g., fetch users with id > 10)
$selectStmt->execute([10]);

// Fetch all matching rows
$users = $selectStmt->fetchAll();

foreach ($users as $user) {
    // Output each user's information
    echo "ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation: 
Laravel queues allow you to defer time‑consuming tasks, such as sending emails or processing files, to a background process. By pushing jobs onto a queue, the main request can respond quickly while the work is handled asynchronously. Laravel supports multiple queue drivers (database, Redis, SQS, etc.) and provides a unified API to dispatch and process jobs. Jobs are simple PHP classes that implement the ShouldQueue contract and contain a handle method where the task logic resides. Workers listen to the queue and execute jobs, optionally retrying on failure according to your configuration.

Code example:

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WelcomeMail;
use Mail;

/**
 * This job sends a welcome email to a newly registered user.
 */
class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userEmail;

    /**
     * Create a new job instance.
     *
     * @param string $userEmail
     * @return void
     */
    public function __construct(string $userEmail)
    {
        // Store the recipient's email address for later use
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->userEmail)->send(new WelcomeMail());
    }
}

// Dispatching the job from a controller or service
use App\Jobs\SendWelcomeEmail;

// Example: after a user registers successfully
SendWelcomeEmail::dispatch($newUser->email);

// To start processing jobs, run the queue worker (e.g., using Redis driver):
// php artisan queue:work redis --tries=3

?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can be recursive, allowing you to perform hierarchical queries such as organizational charts or bill‑of‑materials.  
They improve readability by separating complex subqueries from the main query logic.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  
CTEs exist only for the duration of the statement that defines them; they are not stored as permanent objects.

Code example (non‑recursive CTE that calculates total sales per product and then filters high‑value items):
  
WITH product_sales AS (  
    SELECT p.id AS product_id, p.name AS product_name, SUM(o.quantity * o.unit_price) AS total_sales  
    FROM products p  
    JOIN orders o ON p.id = o.product_id  
    GROUP BY p.id, p.name  
)  
SELECT product_id, product_name, total_sales  
FROM product_sales  
WHERE total_sales > 10000  
ORDER BY total_sales DESC;   (The WITH clause defines the CTE, which is then queried in the final SELECT)
*/

/* JavaScript
Topic: Closures and the Module Pattern

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. By leveraging closures you can create private state that is not directly reachable from the global scope. The module pattern packages related functionality together, exposing only the methods you want public while keeping internal variables hidden. This approach improves encapsulation, prevents naming collisions, and makes code easier to maintain. It is especially useful in large codebases where you want to avoid polluting the global namespace.

Code example with comments:  
function createCounter() {  
    let count = 0; // private variable, not accessible from outside the module  
    return {  
        increment: function() {  
            count++;          // modifies the private count  
            return count;     // returns the updated value  
        },  
        decrement: function() {  
            count--;          // modifies the private count  
            return count;     // returns the updated value  
        },  
        getValue: function() {  
            return count;     // provides read‑only access to the current count  
        }  
    }; // the returned object forms the public API of the module  
}  

const counter = createCounter(); // creates a new independent counter instance  

console.log(counter.increment()); // 1  
console.log(counter.increment()); // 2  
console.log(counter.decrement()); // 1  
console.log(counter.getValue());  // 1  

// Trying to access 'count' directly would result in undefined:
// console.log(counter.count); // undefined (private)  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s ChatCompletion API

Explanation:  
Few‑shot prompting injects a small set of example input‑output pairs directly into the prompt, guiding the model toward the desired format and style. By carefully curating examples that cover edge cases, you can reduce the need for post‑processing and improve consistency. This technique works well for tasks such as data extraction, code generation, or natural‑language transformations. The prompt is constructed as a single string, mixing system instructions, examples, and the user query. Adjusting the number and diversity of examples lets you trade off token usage against output quality.

Code example (Python, using the openai library):
import os
import openai

# Load your API key from an environment variable for safety
openai.api_key = os.getenv("OPENAI_API_KEY")

def extract_dates(text):
    """
    Use a few‑shot prompt to ask the model to list all dates found in the input text.
    """
    # Define the system message that sets the overall behavior
    system_msg = {
        "role": "system",
        "content": "You are a helpful assistant that extracts dates from a sentence and returns them as a JSON array."
    }

    # Provide two illustrative examples (few‑shot)
    example_user_1 = {
        "role": "user",
        "content": "The conference will be held on March 12, 2025 and the deadline is 01/15/2025."
    }
    example_assistant_1 = {
        "role": "assistant",
        "content": '["2025-03-12", "2025-01-15"]'
    }
    example_user_2 = {
        "role": "user",
        "content": "Our launch dates are 7th July 2023, 08/08/2023, and next year."
    }
    example_assistant_2 = {
        "role": "assistant",
        "content": '["2023-07-07", "2023-08-08"]'
    }

    # The actual user query we want to process
    query_msg = {
        "role": "user",
        "content": f"Extract all dates from the following text:\n{text}"
    }

    # Assemble the message list in order
    messages = [
        system_msg,
        example_user_1,
        example_assistant_1,
        example_user_2,
        example_assistant_2,
        query_msg
    ]

    # Call the ChatCompletion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",      # lightweight model suitable for this task
        messages=messages,
        temperature=0.0,          # deterministic output for extraction
        max_tokens=100
    )

    # The model's answer is in the first choice's message content
    return response.choices[0].message.content.strip()

# Example usage
sample_text = "Our meetings are scheduled for 12/01/2024, 15th Feb 2025, and sometime in 2026."
extracted = extract_dates(sample_text)
print("Extracted dates:", extracted)
*/

