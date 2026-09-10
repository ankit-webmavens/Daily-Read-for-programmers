<?php
// 2026-09-10 06:36:39

/* PHP
PHP Generators – Memory‑Efficient Iteration  
Generators allow a function to yield values one at a time instead of building an entire array in memory.  
Each call to the generator returns the next value and pauses execution, resuming where it left off on the next iteration.  
They are especially useful for processing large data sets, reading big files, or streaming database rows.  
Using a generator can reduce memory consumption dramatically and improve performance for sequential data processing.  
Syntax is similar to regular functions, but you use the “yield” keyword to produce values.

<?php
// Example: reading a large CSV file line by line with a generator

function readCsvRows(string $filePath): Generator
{
    // Open the file for reading
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException("Unable to open file: $filePath");
    }

    // Loop until end‑of‑file
    while (($data = fgetcsv($handle)) !== false) {
        // Yield the current row as an associative array
        // Assuming the first row contains column headers
        static $headers = null;
        if ($headers === null) {
            $headers = $data;               // store header row
            continue;                       // skip to next iteration
        }
        $row = array_combine($headers, $data);
        yield $row;                         // return one row, pause here
    }

    fclose($handle); // clean up when generator is exhausted
}

// Consuming the generator
foreach (readCsvRows('large_data.csv') as $row) {
    // Process each row without loading the entire file into memory
    echo $row['id'] . ': ' . $row['name'] . PHP_EOL;
}
?>
*/

/* Laravel
Laravel Topic: Queues with Redis Driver

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or calling external APIs. By configuring the queue driver to use Redis, you gain a fast, in‑memory data store that can handle high throughput and low latency. Jobs are pushed onto a Redis list and workers pull them off, processing each job in isolation. This approach keeps your HTTP responses quick and improves overall application scalability. Laravel provides artisan commands to start workers that listen continuously for new jobs.

Code Example (Job Class and Dispatch)  

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

    protected $userEmail;

    // Constructor receives data needed for the job
    public function __construct(string $email)
    {
        $this->userEmail = $email;
    }

    // This method is executed by the queue worker
    public function handle()
    {
        // Send the email – this runs in the background
        Mail::raw('Welcome to our platform!', function ($message) {
            $message->to($this->userEmail)
                    ->subject('Welcome!');
        });
    }
}

// Dispatching the job from a controller or service
// The job will be pushed onto the Redis queue named "default"
SendWelcomeEmail::dispatch('newuser@example.com')
    ->onQueue('emails')
    ->delay(now()->addMinutes(1)); // optional delay before processing

// To start a worker that processes the "emails" queue:
// php artisan queue:work redis --queue=emails --sleep=3 --tries=3

// Ensure your .env has the following settings:
// QUEUE_CONNECTION=redis
// REDIS_HOST=127.0.0.1
// REDIS_PASSWORD=null
// REDIS_PORT=6379
*/

/* MySQL
Topic: Common Table Expressions (CTE) in MySQL  

Explanation:  
- A CTE is a temporary named result set that you can reference within a single SELECT, INSERT, UPDATE, or DELETE statement.  
- It improves query readability by allowing you to break complex logic into logical building blocks.  
- CTEs can be recursive, enabling hierarchical queries such as organization charts or bill‑of‑materials.  
- They are defined using the WITH clause and exist only for the duration of the statement.  
- MySQL 8.0+ supports both non‑recursive and recursive CTEs, making it a powerful tool for advanced data manipulation.  

Code example (finding employees in a hierarchy and calculating total salary per level):  

WITH RECURSIVE emp_hierarchy AS (  
    SELECT employee_id, manager_id, salary, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL               -- top‑level manager (root)  
    UNION ALL  
    SELECT e.employee_id, e.manager_id, e.salary, eh.level + 1  
    FROM employees e  
    INNER JOIN emp_hierarchy eh ON e.manager_id = eh.employee_id  
)  
SELECT level, COUNT(*) AS employee_count, SUM(salary) AS total_salary  
FROM emp_hierarchy  
GROUP BY level  
ORDER BY level;   -- result shows each hierarchy level with employee count and total salary  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing.  
Closures enable data privacy by allowing inner functions to manipulate private state without exposing it to the global scope.  
They are created automatically whenever a function is defined inside another function, and the inner function references variables from the outer scope.  
Closures are fundamental for patterns such as module creation, function factories, and maintaining state in asynchronous callbacks.  
Understanding closures helps avoid common pitfalls like unintentionally sharing mutable variables across multiple invocations.  

Code Example:  
function makeCounter() {  
    let count = 0;                 // private variable, not accessible from outside  
    return function() {            // this inner function forms a closure over 'count'  
        count++;                   // modifies the private state  
        return count;              // returns the updated count  
    };  
}  

const counterA = makeCounter();      // each call creates a separate closure  
console.log(counterA()); // 1  
console.log(counterA()); // 2  

const counterB = makeCounter();      // independent counter with its own private 'count'  
console.log(counterB()); // 1  
console.log(counterA()); // 3   // counterA continues where it left off  
*/

/* AI
Topic: Few‑Shot Prompt Engineering for GPT‑4

Explanation:
This technique feeds the model a small number of example input‑output pairs (the “shots”) before the actual query, guiding it toward the desired response style and content. By carefully selecting diverse and representative examples, you can improve consistency, reduce hallucinations, and adapt the model to domain‑specific tasks without fine‑tuning. The prompt is built as a single string where each example is separated by clear delimiters, and the final user question follows the examples. This approach works well for classification, transformation, and generation tasks where labeled data is scarce. Adjust the number of shots and the phrasing of examples to balance performance and token cost.

Code example (Python, using OpenAI’s API):
import os
import openai

# Load your OpenAI API key from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def build_few_shot_prompt(examples, user_question):
    """
    Assemble a prompt with a series of example Q&A pairs followed by the new question.
    Each example is formatted as:
    Q: <question>
    A: <answer>
    """
    prompt = ""
    for q, a in examples:
        prompt += f"Q: {q}\nA: {a}\n\n"
    # Append the actual user question without an answer
    prompt += f"Q: {user_question}\nA:"
    return prompt

# Define two illustrative examples (2‑shot)
example_pairs = [
    ("What is the capital of France?", "Paris."),
    ("Translate 'good morning' to Spanish.", "Buenos días.")
]

# New user query we want the model to answer
new_question = "Translate 'thank you' to Japanese."

# Build the complete prompt
few_shot_prompt = build_few_shot_prompt(example_pairs, new_question)

# Call the GPT‑4 model with the constructed prompt
response = openai.ChatCompletion.create(
    model="gpt-4",
    messages=[{"role": "user", "content": few_shot_prompt}],
    temperature=0.2,          # Low temperature for deterministic answers
    max_tokens=50
)

# Extract and print the model’s answer
answer = response["choices"][0]["message"]["content"].strip()
print("Answer:", answer)
*/

