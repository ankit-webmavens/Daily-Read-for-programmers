<?php
// 2026-09-08 06:29:36

/* PHP
PHP Topic: Generators (Yield)

Explanation:
Generators allow you to create iterators without building an entire array in memory.  
By using the `yield` keyword, a function can pause its execution and return a value, then resume later from the same point.  
This is especially useful for processing large data sets, streaming files, or handling database rows one at a time.  
Generators reduce memory consumption and can improve performance in I/O‑bound tasks.  
They behave like objects implementing the Traversable interface, so they work with foreach loops.

Code Example:
<?php
// A generator that yields each line of a large text file
function readLines(string $filename): Generator
{
    $handle = fopen($filename, 'r');
    if ($handle === false) {
        throw new RuntimeException("Cannot open file: $filename");
    }

    // Loop until end of file, yielding one line at a time
    while (($line = fgets($handle)) !== false) {
        // Trim the newline and yield the line to the caller
        yield rtrim($line, "\r\n");
    }

    fclose($handle);
}

// Using the generator
foreach (readLines('biglog.txt') as $lineNumber => $text) {
    // $lineNumber is zero‑based index automatically provided by foreach
    echo "Line " . ($lineNumber + 1) . ": $text\n";

    // Optionally break early to demonstrate lazy evaluation
    if ($lineNumber >= 9) {
        break; // stop after processing first 10 lines
    }
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks, such as sending emails or processing files, to a background worker instead of handling them during a web request. This improves response times and user experience. Queues are configured via a driver (database, Redis, SQS, etc.) and jobs are simple PHP classes that implement the ShouldQueue contract. Dispatching a job places a serialized version of the class onto the selected queue, where a worker process later executes its handle method. Laravel also provides helpers for delaying execution, retrying failed jobs, and monitoring queue health through the built‑in dashboard.

Code example (a simple email sending job using the database queue):

app/Jobs/SendWelcomeEmail.php
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

    protected $user;                 // The user instance to receive the email

    // The number of times the job may be attempted.
    public $tries = 3;

    // Constructor receives the user model.
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker.
    public function handle()
    {
        // Build and send the welcome email.
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }
}

Dispatching the job (e.g., from a controller after registration):

use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    // Validation and user creation logic...
    $user = User::create($request->all());

    // Dispatch the job to the default queue, delaying it by 2 minutes.
    SendWelcomeEmail::dispatch($user)->delay(now()->addMinutes(2));

    return response()->json(['message' => 'Registration successful, welcome email will be sent shortly.']);
}

Running the queue worker (in a terminal):

php artisan queue:work --tries=3 --timeout=60

This command starts a worker that listens to the default queue, processes jobs, respects the $tries property, and times out after 60 seconds if a job hangs. The worker should be supervised (e.g., via Supervisor or systemd) in production to keep it running continuously.
*/

/* MySQL
MySQL Topic: Triggers for Auditing Table Changes

Explanation:  
A trigger is a database object that automatically executes predefined SQL statements when a specified data‑modification event occurs (INSERT, UPDATE, DELETE).  
Auditing triggers capture before‑ and after‑state of rows, allowing you to record who changed what and when without modifying application code.  
You can define triggers at the row level (FOR EACH ROW) so that the trigger fires once for every affected row, which is ideal for detailed change logs.  
Triggers can write to an audit table, include the current user (SESSION_USER), and store timestamps, making it easy to trace data history.  
Because triggers run within the same transaction as the triggering statement, the audit record is rolled back if the original operation fails, guaranteeing consistency.

Code example (create an audit table and a trigger that logs every UPDATE on the `employees` table):

CREATE TABLE employees (
    emp_id   INT PRIMARY KEY,
    name     VARCHAR(100),
    salary   DECIMAL(10,2)
);

CREATE TABLE employees_audit (
    audit_id   BIGINT AUTO_INCREMENT PRIMARY KEY,
    emp_id     INT NOT NULL,
    old_name   VARCHAR(100),
    new_name   VARCHAR(100),
    old_salary DECIMAL(10,2),
    new_salary DECIMAL(10,2),
    changed_by VARCHAR(64) NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER $$

CREATE TRIGGER trg_employees_update
AFTER UPDATE ON employees
FOR EACH ROW
BEGIN
    -- Insert a row into the audit table capturing old and new values
    INSERT INTO employees_audit (
        emp_id,
        old_name, new_name,
        old_salary, new_salary,
        changed_by
    ) VALUES (
        OLD.emp_id,
        OLD.name, NEW.name,
        OLD.salary, NEW.salary,
        SESSION_USER
    );
END $$

DELIMITER ;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is created when an inner function retains access to variables from its outer (enclosing) function even after that outer function has finished executing. This allows the inner function to remember the environment in which it was created, enabling data encapsulation and private state. Closures are fundamental for patterns like function factories, memoization, and module design. Because the retained variables are not garbage‑collected while the closure exists, they can lead to memory leaks if not managed carefully. Understanding closures helps you write more modular and expressive code.

Code Example:
function makeCounter(start) {                     // outer function receives an initial value
    let count = start;                           // this variable is captured by the inner function
    return function() {                         // the inner function forms a closure over 'count'
        count += 1;                              // modify the captured variable
        return count;                            // return the updated value
    };
}

const counterA = makeCounter(0);                  // creates a new closure with its own 'count'
console.log(counterA()); // 1
console.log(counterA()); // 2

const counterB = makeCounter(10);                 // a separate closure, independent state
console.log(counterB()); // 11
console.log(counterA()); // 3   // counterA continues where it left off, proving isolation.
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting provides the model with several example interactions before the actual user query, guiding it toward the desired response style. By structuring these examples as a list of messages, you can demonstrate the pattern you want—such as a concise summary, a step‑by‑step solution, or a specific tone. The model uses the context of the examples to infer how to handle the new request, often producing more accurate and consistent outputs than a single instruction. This technique works well for tasks like code generation, data extraction, or instructional writing. Adjust the number and quality of examples to balance token usage with performance.

Code example (Python) with comments:  
import os  
import json  
from openai import OpenAI  

# Initialize the client using your API key from the environment variable  
client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))  

# Define a few‑shot prompt: two example Q&A pairs followed by the new user question  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that writes concise Python functions."},  
    {"role": "user", "content": "Write a function that returns the factorial of a number."},  
    {"role": "assistant", "content": "def factorial(n):\n    return 1 if n == 0 else n * factorial(n-1)"},  
    {"role": "user", "content": "Write a function that checks if a string is a palindrome."},  
    {"role": "assistant", "content": "def is_palindrome(s):\n    s = s.replace(' ', '').lower()\n    return s == s[::-1]"},  
    {"role": "user", "content": "Write a function that merges two sorted lists into one sorted list."}  
]  

# Call the chat completion endpoint with the constructed messages  
response = client.chat.completions.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.2,  # low temperature for deterministic code output  
)  

# Extract and display the generated code  
generated_code = response.choices[0].message.content  
print("Generated function:\n", generated_code)  
*/

