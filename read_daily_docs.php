<?php
// 2026-09-17 06:36:45

/* PHP
Topic: PHP Generators  

Explanation:  
- Generators provide a simple way to implement iterators without the overhead of building a full class that implements the Iterator interface.  
- They use the `yield` keyword to return values one at a time, preserving the function’s state between each call.  
- This makes them memory‑efficient for large data sets because only one value is held in memory at a time.  
- Generators can also receive input via `send()` and can return a final value with `return`.  
- They are particularly useful for streaming data, lazy loading, or handling large files line by line.  

Code example (with comments):  

function getNumbers(int $max): Generator  
{  
    for ($i = 1; $i <= $max; $i++) {  
        // Yield the current number and pause execution  
        yield $i;  
    }  
    // Optional final return value (available via $generator->getReturn())  
    return "Done generating $max numbers.";  
}  

$generator = getNumbers(5);  

foreach ($generator as $value) {  
    // Each iteration receives the next yielded value  
    echo "Number: $value\n";  
}  

// Access the return value after the generator is exhausted  
echo $generator->getReturn();  
*/

/* Laravel
Topic: Eloquent One‑to‑Many Relationships  

Explanation:  
In Laravel, a one‑to‑many relationship is used when a single model owns multiple instances of another model, such as a Post having many Comments. The relationship is defined in the parent model with a hasMany() method and in the child model with a belongsTo() method. Eloquent automatically uses the foreign key convention (model_id) unless you specify a custom key. Once defined, you can retrieve related records using dynamic properties or query builder methods, and you can easily create, update, or delete related records through the relationship. This pattern keeps database interactions expressive and concise while maintaining referential integrity.

Code Example (Post and Comment models):

// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // A post has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class); // default foreign key: post_id
    }
}

// app/Models/Comment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // A comment belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class); // default foreign key: post_id
    }
}

// Using the relationship in a controller or route closure
// Retrieve a post with its comments
$post = Post::with('comments')->find(1);

// Access comments via dynamic property
foreach ($post->comments as $comment) {
    echo $comment->content . PHP_EOL;
}

// Adding a new comment to the post
$post->comments()->create([
    'content' => 'Great article!',
    'user_id' => auth()->id(),
]);

// Deleting all comments belonging to a post
$post->comments()->delete();
*/

/* MySQL
Topic name: Common Table Expressions (CTEs) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks.  
When the WITH clause is followed by the keyword RECURSIVE, the CTE can refer to itself, enabling hierarchical or graph traversal queries.  
Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until no new rows are produced.  
They are especially useful for processing tree structures such as organizational charts, category trees, or bill‑of‑materials data.

Code example (generating an employee hierarchy):

-- Anchor member: select the top‑level manager(s)
WITH RECURSIVE emp_hierarchy AS (
    SELECT 
        employee_id,
        manager_id,
        first_name,
        last_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL          -- top‑level managers have no manager

    UNION ALL

    -- Recursive member: join each employee to their manager
    SELECT 
        e.employee_id,
        e.manager_id,
        e.first_name,
        e.last_name,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN emp_hierarchy eh
        ON e.manager_id = eh.employee_id
)
SELECT 
    employee_id,
    manager_id,
    CONCAT(first_name, ' ', last_name) AS full_name,
    level
FROM emp_hierarchy
ORDER BY level, manager_id;   -- results show the hierarchy from top to bottom.
*/

/* JavaScript
Topic: JavaScript Closures  

Explanation:  
A closure is created when an inner function retains access to variables from its outer (enclosing) function even after that outer function has finished executing. This allows the inner function to “remember” the environment in which it was created, enabling data encapsulation and private state. Closures are fundamental for patterns such as function factories, module patterns, and maintaining state across asynchronous callbacks. Because the captured variables live on the heap, they persist as long as any reference to the inner function exists. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state between loop iterations.

Code Example:
// Function that returns a new counter each time it is called
function makeCounter() {
    // ‘count’ is a private variable that lives in the closure
    let count = 0;

    // The returned function forms a closure over ‘count’
    return function () {
        // Increment and return the private count
        count++;
        return count;
    };
}

// Create two independent counters
const counterA = makeCounter();
const counterB = makeCounter();

console.log(counterA()); // 1
console.log(counterA()); // 2
console.log(counterB()); // 1  (separate closure, its own ‘count’) 
console.log(counterA()); // 3

// Even after makeCounter() has finished, the inner function still accesses ‘count’ via its closure.
*/

/* AI
Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  
This technique supplies the model with a few example input‑output pairs (the “shots”) before the actual user query, guiding it toward the desired behavior. By carefully crafting the prompt structure—defining role, instructions, and examples—you can achieve higher accuracy without fine‑tuning. Few‑shot prompts are especially useful for tasks like text classification, data extraction, or style transfer where labeled data is scarce. The approach works across GPT‑3.5, GPT‑4 and newer models, and can be adapted programmatically to generate dynamic prompts. Properly formatted examples help the model infer patterns and apply them to new inputs.

Python example using the OpenAI API (requires `openai` package and an API key set in the environment)

import os
import openai

# Load API key from environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Define a few‑shot prompt with role, instruction, and examples
    prompt = [
        {"role": "system", "content": "You are a helpful assistant that classifies sentiment of short sentences as Positive, Negative, or Neutral."},
        {"role": "user", "content": "I love the new phone I bought!"},
        {"role": "assistant", "content": "Positive"},
        {"role": "user", "content": "The weather today is okay."},
        {"role": "assistant", "content": "Neutral"},
        {"role": "user", "content": "I'm disappointed with the service."},
        {"role": "assistant", "content": "Negative"},
        # The actual query to classify
        {"role": "user", "content": text}
    ]

    # Call the chat completion endpoint
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # choose an appropriate model
        messages=prompt,
        temperature=0.0               # deterministic output for classification
    )

    # Extract the assistant's reply
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
sample_text = "The movie was surprisingly good."
print(f"Sentiment: {classify_sentiment(sample_text)}")   # Expected output: Positive  
*/

