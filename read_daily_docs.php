<?php
// 2026-11-24 11:27:11

/* PHP
PHP PDO (PHP Data Objects) for Secure Database Access  
The PDO extension provides a consistent interface for working with many different databases (MySQL, PostgreSQL, SQLite, etc.).  
It uses prepared statements, which separate SQL code from data, preventing SQL‑injection attacks.  
PDO returns results as objects or associative arrays, making it easy to manipulate query data.  
Error handling can be configured to throw exceptions, allowing clean try/catch logic.  
Connection parameters are supplied as a DSN string, keeping credentials separate from the query logic.  

<?php
// Database connection parameters
$host = 'localhost';
$db   = 'testdb';
$user = 'dbuser';
$pass = 'dbpass';
$charset = 'utf8mb4';

// Data Source Name (DSN) string tells PDO which driver to use and how to connect
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    // Create a new PDO instance (establishes the connection)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle connection errors
    die('Connection failed: ' . $e->getMessage());
}

// Example prepared statement to fetch a user by email
$sql = 'SELECT id, name, email FROM users WHERE email = :email';
$stmt = $pdo->prepare($sql);            // Prepare the query with a named placeholder
$stmt->execute(['email' => 'alice@example.com']); // Bind the value and execute

// Fetch the single result row
$user = $stmt->fetch();

if ($user) {
    echo "User ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}";
} else {
    echo 'No user found with that email.';
}
?>
*/

/* Laravel
Topic: Eloquent One-to-Many Relationships

Explanation:  
In Laravel, a one-to-many relationship links a parent model that can have multiple related child records, while each child belongs to a single parent. This is common for scenarios like a blog where a Post can have many Comments. The relationship is defined using the hasMany method on the parent model and the belongsTo method on the child model. Laravel automatically infers foreign key names (e.g., post_id) but they can be customized. Once defined, you can retrieve related records using dynamic properties or query builder methods, and you can also create, update, or delete related models through the relationship.

Code example with comments:

// app/Models/Post.php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Define the one-to-many relationship to comments
    public function comments()
    {
        // hasMany(RelatedModel::class, foreignKey = 'post_id', localKey = 'id')
        return $this->hasMany(Comment::class);
    }
}

// app/Models/Comment.php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Define the inverse relationship back to the post
    public function post()
    {
        // belongsTo(RelatedModel::class, foreignKey = 'post_id', ownerKey = 'id')
        return $this->belongsTo(Post::class);
    }
}

// database/migrations/2024_01_01_000001_create_posts_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

// database/migrations/2024_01_01_000002_create_comments_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

// Using the relationship in a controller or route
<?php
use App\Models\Post;
use App\Models\Comment;

// Retrieve a post with its comments
$post = Post::with('comments')->find(1);
foreach ($post->comments as $comment) {
    echo $comment->content . PHP_EOL;
}

// Create a new comment for a post
$comment = new Comment(['content' => 'Great article!']);
$post->comments()->save($comment);

// Delete all comments belonging to a post
$post->comments()->delete();
*/

/* MySQL
Topic: Using Stored Procedures with IN, OUT, and INOUT Parameters

Explanation:
Stored procedures allow you to encapsulate a set of SQL statements that can be executed repeatedly with different inputs.  
By defining IN, OUT, and INOUT parameters, you can pass values into the procedure, retrieve results, and even modify the input values.  
IN parameters are read‑only inside the procedure, OUT parameters return data to the caller, and INOUT parameters act as both input and output.  
Procedures improve code reusability, reduce network traffic, and can enforce business logic at the database level.  
They are especially useful for complex operations that involve multiple queries, transactions, or conditional logic.

Code example (MySQL):

-- Create a database and use it
CREATE DATABASE IF NOT EXISTS demo_db;
USE demo_db;

-- Create a sample table to store employee salaries
CREATE TABLE IF NOT EXISTS employees (
    emp_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    salary DECIMAL(10,2) NOT NULL
);

-- Insert some test data
INSERT INTO employees (name, salary) VALUES 
('Alice', 70000.00),
('Bob', 85000.00),
('Carol', 92000.00);

-- Drop the procedure if it already exists
DROP PROCEDURE IF EXISTS GetSalaryInfo;

-- Define a stored procedure that takes an employee ID (IN),
-- returns the employee name (OUT), and updates the salary by a bonus amount (INOUT)
CREATE PROCEDURE GetSalaryInfo (
    IN p_emp_id INT,
    OUT p_name VARCHAR(50),
    INOUT p_bonus DECIMAL(10,2)
)
BEGIN
    -- Retrieve the employee name into the OUT parameter
    SELECT name INTO p_name
    FROM employees
    WHERE emp_id = p_emp_id;

    -- Add the bonus to the current salary
    UPDATE employees
    SET salary = salary + p_bonus
    WHERE emp_id = p_emp_id;

    -- After the update, fetch the new salary and store it back into the INOUT parameter
    SELECT salary INTO p_bonus
    FROM employees
    WHERE emp_id = p_emp_id;
END;

-- Declare variables to hold the OUT and INOUT values
SET @empName = '';
SET @bonusAmount = 5000.00;   -- initial bonus to add

-- Call the procedure for employee with ID 2 (Bob)
CALL GetSalaryInfo(2, @empName, @bonusAmount);

-- Display the results
SELECT @empName AS EmployeeName, @bonusAmount AS NewSalaryAfterBonus;

-- Verify the table reflects the updated salary
SELECT * FROM employees WHERE emp_id = 2;
*/

/* JavaScript
Topic: Debouncing Functions in JavaScript  

Explanation:  
Debouncing is a technique used to limit how often a function can be invoked. It is especially useful for performance‑critical events like window resizing, scrolling, or keystroke handling where the raw event may fire many times per second. The debounce wrapper creates a timer that delays the execution of the original function until a specified period has elapsed without further calls. If the event occurs again before the timer expires, the timer is reset, effectively postponing the function call. This reduces unnecessary processing and improves UI responsiveness.

Code example with comments:  

function debounce(func, wait) {                     // Accept the function to debounce and the delay in ms
    let timeoutId = null;                         // Holds the reference to the pending timer
    return function(...args) {                    // Return a new wrapper function that captures arguments
        if (timeoutId !== null) {                 // If a timer is already running, clear it
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {            // Start a new timer
            func.apply(this, args);               // When the timer finishes, invoke the original function
        }, wait);
    };
}

// Example usage: log the window width only after the user stops resizing for 300 ms
const logWidth = () => console.log('Window width:', window.innerWidth);
window.addEventListener('resize', debounce(logWidth, 300));   // Attach debounced handler to resize event  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
1. Few‑shot prompting supplies a small number of example input‑output pairs to guide the model’s behavior without fine‑tuning.  
2. By structuring the prompt as a conversation, you can demonstrate the desired format, tone, and constraints.  
3. The model uses the examples as a pattern, generalizing to new user queries that follow the same schema.  
4. This technique works well for classification, transformation, or generation tasks where a full dataset is unavailable.  
5. The OpenAI Chat Completion endpoint accepts a list of messages, making it easy to embed examples directly in the API call.  

Code example (Python, using openai library):  

import os  
import openai  

# Load your API key from an environment variable or secret manager  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt as a list of messages  
messages = [  
    {"role": "system", "content": "You are an assistant that converts informal English sentences into polite, formal business language."},  
    {"role": "user", "content": "Hey, can you send me the report?"},  
    {"role": "assistant", "content": "Could you please forward the report to me at your earliest convenience?"},  
    {"role": "user", "content": "I need those numbers ASAP."},  
    {"role": "assistant", "content": "I would appreciate receiving the requested numbers as soon as possible."},  
    # New query that should follow the demonstrated pattern  
    {"role": "user", "content": "Give me an update on the project."}  
]  

# Call the Chat Completion API  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",        # Choose a suitable model  
    messages=messages,          # Pass the few‑shot prompt  
    temperature=0.2,            # Low temperature for deterministic output  
    max_tokens=100              # Limit the length of the reply  
)  

# Extract and print the assistant’s answer  
formal_reply = response["choices"][0]["message"]["content"]  
print(formal_reply)   # Expected: a polite, formal version of the last user query.  
*/

