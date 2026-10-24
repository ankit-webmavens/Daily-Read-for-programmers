<?php
// 2026-10-24 06:22:59

/* PHP
PHP PDO Prepared Statements

Explanation:  
Prepared statements separate the SQL query structure from the data values, which improves security by preventing SQL injection attacks. The query is sent to the database server and compiled once, then executed multiple times with different parameters. PDO (PHP Data Objects) provides a consistent interface for prepared statements across many database systems. Binding parameters can be done by position or by name, offering flexibility in how data is supplied. Using prepared statements also often yields better performance for repeated queries.

Code example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Prepare an INSERT statement with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())';
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders and execute
$users = [
    ['username' => 'alice', 'email' => 'alice@example.com'],
    ['username' => 'bob',   'email' => 'bob@example.com']
];
foreach ($users as $user) {
    $stmt->bindParam(':username', $user['username']);
    $stmt->bindParam(':email',    $user['email']);
    $stmt->execute(); // Executes the prepared statement with the current bound values
}

// Retrieve the inserted rows using a prepared SELECT statement
$selectSql = 'SELECT id, username, email FROM users WHERE email = :email';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':email' => 'alice@example.com']);
$alice = $selectStmt->fetch(); // Fetches the row for Alice

// Output the result
echo 'User ID: ' . $alice['id'] . PHP_EOL;
echo 'Username: ' . $alice['username'] . PHP_EOL;
echo 'Email: ' . $alice['email'] . PHP_EOL;
*/

/* Laravel
Topic: Route Model Binding in Laravel  

Explanation:  
Laravel’s route model binding automatically injects model instances into your routes based on the route parameters.  
When a route contains a placeholder that matches a model’s primary key, Laravel resolves the corresponding record.  
If the record is not found, a 404 response is generated without additional code.  
Both implicit and explicit binding are supported; implicit works by type‑hinting the model in the controller method.  
Explicit binding allows you to customize the key used for lookup or bind a closure for complex resolution.  

Code Example:  

<?php  

use App\Http\Controllers\PostController;  
use App\Models\Post;  
use Illuminate\Support\Facades\Route;  

// Implicit binding – Laravel will resolve {post} to a Post model instance by ID  
Route::get('posts/{post}', [PostController::class, 'show']);  

// Explicit binding – use a custom key such as slug instead of the default ID  
Route::bind('post_slug', function ($value) {  
    // Return the Post model where the slug column matches the route value  
    return Post::where('slug', $value)->firstOrFail();  
});  

// Route using the explicit binding key  
Route::get('posts/slug/{post_slug}', [PostController::class, 'showBySlug']);  

// PostController.php  
namespace App\Http\Controllers;  

use App\Models\Post;  

class PostController extends Controller  
{  
    // Method for implicit binding – $post is already a Post instance  
    public function show(Post $post)  
    {  
        // Return or render the post data  
        return response()->json($post);  
    }  

    // Method for explicit binding – parameter name matches the binding key  
    public function showBySlug(Post $post_slug)  
    {  
        // $post_slug is actually a Post model resolved by slug  
        return response()->json($post_slug);  
    }  
}  

// App\Models\Post.php (simplified)  
namespace App\Models;  

use Illuminate\Database\Eloquent\Model;  

class Post extends Model  
{  
    // If you want to use slug as the route key globally, override getRouteKeyName  
    public function getRouteKeyName()  
    {  
        return 'slug'; // Now implicit binding will use slug instead of ID  
    }  
}  
*/

/* MySQL
Topic: MySQL Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve readability by breaking complex queries into logical parts. MySQL 8.0+ supports both non‑recursive and recursive CTEs, the latter allowing you to traverse hierarchical data such as organizational charts or tree structures. Recursive CTEs consist of an anchor member that provides the initial rows and a recursive member that repeatedly references the CTE itself until no new rows are produced. They are particularly useful for queries that would otherwise require procedural loops or multiple self‑joins.  

Code example with comments:

-- Create a sample table to store an employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50) NOT NULL,
    manager_id INT NULL,          -- NULL for top‑level manager (CEO)
    FOREIGN KEY (manager_id) REFERENCES employees(emp_id)
);

-- Insert sample data
INSERT INTO employees (emp_id, emp_name, manager_id) VALUES
(1, 'Alice', NULL),      -- CEO
(2, 'Bob', 1),           -- reports to Alice
(3, 'Carol', 1),         -- reports to Alice
(4, 'David', 2),         -- reports to Bob
(5, 'Eve', 2),           -- reports to Bob
(6, 'Frank', 3);         -- reports to Carol

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)
WITH RECURSIVE subordinates AS (
    -- Anchor member: start with the direct reports of the manager
    SELECT emp_id, emp_name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id = 1
    UNION ALL
    -- Recursive member: find employees whose manager is in the previous level
    SELECT e.emp_id, e.emp_name, e.manager_id, s.level + 1
    FROM employees e
    INNER JOIN subordinates s ON e.manager_id = s.emp_id
)
SELECT emp_id, emp_name, manager_id, level
FROM subordinates
ORDER BY level, emp_id;

-- The query returns:
-- emp_id | emp_name | manager_id | level
--   2    |   Bob    |     1      |  1
--   3    |  Carol   |     1      |  1
--   4    |  David   |     2      |  2
--   5    |   Eve    |     2      |  2
--   6    |  Frank   |     3      |  2

-- This CTE walks the hierarchy starting from the CEO (manager_id = 1) and lists each subordinate with their depth level.
*/

/* JavaScript
Topic: Asynchronous Iterators and the for‑await‑of Loop

Explanation:  
An asynchronous iterator produces values that may become available at different times, such as data from a network request or a file stream. It implements a `next()` method that returns a promise resolving to an object with `value` and `done` properties. By defining `[Symbol.asyncIterator]` on an object, you enable it to be consumed with the `for‑await‑of` syntax, which pauses the loop until each promise resolves. This pattern is useful for processing streams, paginated APIs, or any source where data arrives incrementally. Unlike regular iterators, asynchronous iterators keep the code non‑blocking while preserving a simple sequential style.

Code example:
```javascript
// Simulated async data source that yields numbers with a delay
function createAsyncNumberGenerator(limit) {
  let count = 1;
  return {
    // Define the async iterator protocol
    [Symbol.asyncIterator]() {
      return this;
    },
    // Each call returns a promise that resolves after 500 ms
    async next() {
      if (count > limit) {
        return { done: true };                     // Signal completion
      }
      // Wait half a second before delivering the next value
      await new Promise(res => setTimeout(res, 500));
      return { value: count++, done: false };      // Provide next number
    }
  };
}

// Consume the async iterator using for‑await‑of
(async () => {
  for await (const num of createAsyncNumberGenerator(5)) {
    console.log('Received:', num);                // Logs 1 … 5 at 0.5 s intervals
  }
  console.log('All numbers processed');
})();
```
*/

/* AI
Topic Name: Fine‑Tuning a Code Generation Model with Hugging Face Transformers  

Explanation:  
Fine‑tuning a pre‑trained transformer (e.g., CodeGen, GPT‑Neo) on a domain‑specific code corpus can improve its ability to generate correct, idiomatic code for that domain. The process involves loading the base model, preparing paired prompt‑completion examples, and training with a small learning rate to preserve the model’s general knowledge. Using the 🤗 Accelerate library simplifies multi‑GPU or mixed‑precision training. After training, the model can be saved and served via the Hugging Face Inference API for real‑time code suggestions. This workflow is lightweight enough to run on a single GPU workstation.  

Code Example:  
import os  
from datasets import load_dataset  
from transformers import AutoTokenizer, AutoModelForCausalLM, Trainer, TrainingArguments  

# Load a small public code dataset (e.g., python‑code‑alpaca)  
dataset = load_dataset("tatsu-lab/alpaca", split="train")  
# Keep only examples that contain Python code in the instruction field  
def filter_python(example):  
    return "python" in example["instruction"].lower()  
dataset = dataset.filter(filter_python)  

# Initialize tokenizer and model from a code‑focused checkpoint  
model_name = "bigcode/starcoderbase-1b"  
tokenizer = AutoTokenizer.from_pretrained(model_name)  
model = AutoModelForCausalLM.from_pretrained(model_name, torch_dtype="auto")  

# Tokenize the prompt‑completion pairs  
def tokenize_fn(example):  
    # Concatenate instruction and response with a separator token  
    prompt = f"{example['instruction']}\n\n{example['input']}" if example['input'] else example['instruction']  
    full_text = f"{prompt}\n\n{example['output']}"  
    tokenized = tokenizer(full_text, truncation=True, max_length=1024)  
    tokenized["labels"] = tokenized["input_ids"].copy()  # supervised loss uses same IDs as targets  
    return tokenized  

tokenized_dataset = dataset.map(tokenize_fn, remove_columns=dataset.column_names)  

# Set up training arguments – modest settings for a single GPU  
training_args = TrainingArguments(  
    output_dir="./fine_tuned_codegen",  
    per_device_train_batch_size=2,  
    gradient_accumulation_steps=4,  
    num_train_epochs=3,  
    learning_rate=5e-5,  
    fp16=True,               # mixed precision for speed  
    logging_steps=50,  
    save_steps=500,  
    evaluation_strategy="no",  
)  

# Create Trainer instance  
trainer = Trainer(  
    model=model,  
    args=training_args,  
    train_dataset=tokenized_dataset,  
)  

# Start fine‑tuning  
trainer.train()  

# Save the fine‑tuned model for later inference  
trainer.save_model("./fine_tuned_codegen")  

# Example inference – generate code from a new prompt  
def generate_code(prompt_text):  
    input_ids = tokenizer(prompt_text, return_tensors="pt").input_ids.to(model.device)  
    output_ids = model.generate(  
        input_ids,  
        max_new_tokens=150,  
        temperature=0.7,  
        do_sample=True,  
        pad_token_id=tokenizer.eos_token_id,  
    )  
    return tokenizer.decode(output_ids[0], skip_special_tokens=True)  

print(generate_code("Write a Python function that computes the nth Fibonacci number using memoization."))
*/

