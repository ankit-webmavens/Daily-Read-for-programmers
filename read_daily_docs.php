<?php
// 2026-10-21 06:29:47

/* PHP
Topic: PHP Generators (Yield)

Explanation:
Generators allow you to create iterators without building an entire array in memory.  
By using the yield keyword, a function can pause its execution, return a value, and resume later.  
This is especially useful for processing large data sets, streaming files, or handling infinite sequences.  
Generators improve performance and reduce memory usage compared to returning full collections.  
They can also receive values back into the generator using send() and be controlled with throw() and close().

Code Example:
<?php
// A generator function that yields numbers from 1 to $limit
function numberSequence(int $limit): Generator
{
    for ($i = 1; $i <= $limit; $i++) {
        // Yield the current number and pause execution
        yield $i;
    }
}

// Use the generator
$limit = 5;
$gen = numberSequence($limit);

foreach ($gen as $value) {
    // Each iteration retrieves the next yielded value
    echo "Value: $value\n";
}

// Demonstrating sending a value back into the generator
function twoWayGenerator(): Generator
{
    $value = yield "Start";
    // $value receives whatever is sent from outside
    yield "Received: $value";
}

$tw = twoWayGenerator();
echo $tw->current() . "\n";   // Outputs "Start"
$tw->send("Hello");          // Sends "Hello" back, making $value = "Hello"
echo $tw->current() . "\n";   // Outputs "Received: Hello"
?>
*/

/* Laravel
Topic: Laravel Queues with Redis

Explanation:
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or calling external APIs. By configuring the queue driver to use Redis, you get a fast, in‑memory data store that can handle high‑throughput job processing. Jobs are pushed onto a Redis list and workers pull them off, executing the job logic in the background. This improves the responsiveness of your web requests and helps scale your application horizontally. You can monitor and retry failed jobs using Laravel’s built‑in queue commands and dashboard.

Code example with comments:

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

    protected $user; // The user instance that will receive the email

    // Constructor receives the user and stores it for later use
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Build and send the welcome email
        Mail::to($this->user->email)->send(new \App\Mail\WelcomeMail($this->user));
    }
}

// Dispatching the job (e.g., from a controller)
$user = \App\Models\User::find(1);
// The job will be pushed onto the Redis queue named "default"
SendWelcomeEmail::dispatch($user)->onQueue('emails');

// To start a worker that processes the "emails" queue using the Redis driver:
// php artisan queue:work redis --queue=emails --sleep=3 --tries=3

?>
*/

/* MySQL
Topic: Window Functions – Running Totals in MySQL

Explanation:
- Window functions operate on a set of rows related to the current row without collapsing the result set.
- They are useful for calculations that need context, such as cumulative sums, moving averages, or ranking.
- MySQL introduced full support for window functions in version 8.0, allowing efficient analytics directly in SQL.
- A running total (cumulative sum) can be produced with the SUM() function combined with an OVER() clause that defines the ordering.
- This technique avoids the need for self‑joins or user‑defined variables, making the query easier to read and maintain.

Code example (with comments):

-- Create a sample sales table
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL
);

-- Insert sample data
INSERT INTO sales (sale_date, amount) VALUES
('2024-01-01', 150.00),
('2024-01-03', 200.00),
('2024-01-04', 120.00),
('2024-01-07', 300.00),
('2024-01-08', 250.00);

-- Query to compute a running total of sales ordered by date
SELECT
    id,
    sale_date,
    amount,
    -- SUM() calculates the cumulative total up to the current row
    SUM(amount) OVER (ORDER BY sale_date ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS running_total
FROM sales
ORDER BY sale_date;

-- The result shows each sale record with its corresponding cumulative sum in the running_total column.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even when executed outside that scope.  
It allows inner functions to "remember" variables from the outer function that have already finished executing.  
Closures are created automatically every time a function is defined inside another function.  
They are essential for data privacy, partial application, and creating function factories.  
Understanding closures helps avoid common pitfalls with variable mutability and asynchronous code.  

Code Example:  
function makeCounter(initial) {                     // outer function creates a private variable  
    let count = initial;                           // this variable is captured by the inner function  

    return function step(increment) {              // inner function forms a closure over count  
        count += increment;                        // modifies the captured variable  
        return count;                              // returns the updated value  
    };                                             // the closure is returned as a callable function  
}                                                  // end of makeCounter  

const counter = makeCounter(10);                    // counter now has its own private count starting at 10  
console.log(counter(2));   // 12   // each call updates the private count  
console.log(counter(5));   // 17   // the state is preserved across calls  
console.log(counter(-3));  // 14   // closures keep the variable alive even after makeCounter finishes  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
- Few‑shot prompting supplies a handful of example input‑output pairs to steer the model toward the desired behavior.  
- By placing clear, concise demonstrations before the new query, the model learns the pattern without any fine‑tuning.  
- This technique works well for classification, transformation, or reasoning tasks where a full dataset is unavailable.  
- The prompt is constructed as a sequence of messages, alternating user and assistant roles, matching the chat API format.  
- Keeping temperature low (e.g., 0.0) makes the output deterministic, which is important for reproducible classifications.  

Code example (Python, using the OpenAI API):  

import os  
import openai  

# Load API key from environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Few‑shot examples: user input followed by expected assistant label  
    examples = [  
        {"role": "user", "content": "I love this product! It works perfectly."},  
        {"role": "assistant", "content": "Positive"},  
        {"role": "user", "content": "The service was terrible and slow."},  
        {"role": "assistant", "content": "Negative"},  
    ]  
    # Append the new query after the examples  
    messages = examples + [{"role": "user", "content": text}]  
    # Request completion from the chat model  
    response = openai.ChatCompletion.create(  
        model="gpt-3.5-turbo",  
        messages=messages,  
        temperature=0.0,   # deterministic output  
    )  
    # Return the model’s label (strip whitespace)  
    return response.choices[0].message.content.strip()  

# Example usage  
print(classify_sentiment("The movie had stunning visuals but a weak plot."))   # Expected: Neutral or Mixed, based on examples  
*/

