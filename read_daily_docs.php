<?php
// 2026-10-01 07:41:16

/* PHP
Topic: Using PDO Prepared Statements for Secure Database Access

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
The PDO (PHP Data Objects) extension provides a uniform interface for various databases.  
You first create a PDO instance with the appropriate DSN, username, and password.  
Then you prepare an SQL statement with placeholders, bind values, and execute it.  
After execution you can fetch results as associative arrays or objects, and the connection is automatically closed when the script ends.  

Code example (with comments):  
<?php  
// Create a new PDO instance to connect to a MySQL database  
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';  
$username = 'dbuser';  
$password = 'dbpass';  
$options = [  
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors  
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch rows as associative arrays  
];  
$pdo = new PDO($dsn, $username, $password, $options);  

// Prepare an INSERT statement with named placeholders  
$sql = 'INSERT INTO users (email, password_hash) VALUES (:email, :hash)';  
$stmt = $pdo->prepare($sql);  

// Bind values to the placeholders and execute the statement  
$email = 'alice@example.com';  
$hash = password_hash('secret123', PASSWORD_BCRYPT);  
$stmt->bindParam(':email', $email);  
$stmt->bindParam(':hash', $hash);  
$stmt->execute();  

// Prepare a SELECT statement to fetch the inserted row  
$selectSql = 'SELECT id, email FROM users WHERE email = :email';  
$selectStmt = $pdo->prepare($selectSql);  
$selectStmt->execute([':email' => $email]);  

// Fetch the result as an associative array  
$user = $selectStmt->fetch();  
if ($user) {  
    echo 'User ID: ' . $user['id'] . ', Email: ' . $user['email'];  
} else {  
    echo 'User not found.';  
}  

// No need to explicitly close the connection; it closes when the script ends  
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing files, or making API calls, so they run in the background instead of blocking the request cycle. By pushing a job onto a queue, Laravel stores the job payload in a driver (database, Redis, SQS, etc.) and a worker process later retrieves and executes it. This improves application responsiveness and enables horizontal scaling by adding more workers as demand grows. Queues also support retry attempts, job timeouts, and failure handling out of the box. You define jobs as plain PHP classes that implement the ShouldQueue contract and contain a handle method where the actual work is performed.

Code example (app/Jobs/SendWelcomeEmail.php):

<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;          // Marks the job for queue processing
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;                                            // Facade for sending email

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;                                 // Store the user instance

    /**
     * Create a new job instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;                         // Inject the user when dispatching
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Build the email data
        $data = ['user' => $this->user];

        // Send the email using a mailable or a simple closure
        Mail::send('emails.welcome', $data, function ($message) {
            $message->to($this->user->email)
                    ->subject('Welcome to Our Platform');
        });
    }
}

Dispatching the job (e.g., in a controller after registration):

use App\Jobs\SendWelcomeEmail;

// After creating the user...
$user = User::create($validatedData);

// Push the job onto the default queue
SendWelcomeEmail::dispatch($user);   // Returns immediately, job will be processed by a worker

Running the worker (from the command line):

php artisan queue:work --tries=3   // Processes jobs, retries up to 3 times on failure.
*/

/* MySQL
Topic: Stored Procedures in MySQL  

Explanation:  
A stored procedure is a named set of SQL statements that is stored in the database server and can be executed repeatedly.  
Procedures help encapsulate business logic, reduce client‑side code, and improve performance by minimizing round‑trips.  
They can accept input parameters, return output parameters, and contain control‑flow statements such as IF and LOOP.  
MySQL supports declaring variables, handling errors with DECLARE ... HANDLER, and committing or rolling back transactions inside a procedure.  
Using procedures also enhances security because you can grant users permission to execute the procedure without giving direct access to underlying tables.  

Example:  

DELIMITER $$  
CREATE PROCEDURE TransferFunds(  
    IN p_from_account INT,  
    IN p_to_account   INT,  
    IN p_amount       DECIMAL(10,2)  
)  
BEGIN  
    DECLARE insufficient_funds CONDITION FOR SQLSTATE '45000';  
  
    -- Check that the source account has enough balance  
    IF (SELECT balance FROM accounts WHERE account_id = p_from_account) < p_amount THEN  
        SIGNAL insufficient_funds SET MESSAGE_TEXT = 'Insufficient funds';  
    END IF;  
  
    -- Debit the source account  
    UPDATE accounts  
    SET balance = balance - p_amount  
    WHERE account_id = p_from_account;  
  
    -- Credit the destination account  
    UPDATE accounts  
    SET balance = balance + p_amount  
    WHERE account_id = p_to_account;  
  
    COMMIT;  
END$$  
DELIMITER ;  

-- To call the procedure:  
CALL TransferFunds(101, 202, 250.00);   (this line is just an example call)
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:  
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that captures the surrounding scope. Closures are useful for creating private data, implementing function factories, and preserving state across multiple calls. They enable patterns like memoization and module encapsulation without relying on global variables. Understanding closures is essential for mastering asynchronous code and callbacks in JavaScript.

Code example (with comments):
function createCounter(initialValue) {            // outer function, creates a private counter
  let count = initialValue;                       // variable to be captured by the inner function

  return function increment(step = 1) {           // inner function forms a closure over 'count'
    count += step;                                // modifies the captured variable
    return count;                                 // returns the updated count
  };
}

// Using the closure
const counterA = createCounter(0);                // counterA has its own private 'count'
console.log(counterA());       // 1
console.log(counterA(5));      // 6
console.log(counterA());       // 7

const counterB = createCounter(10);               // separate closure, independent 'count'
console.log(counterB());       // 11
console.log(counterB(2));      // 13

// The 'count' variable is not accessible from the outside, demonstrating encapsulation.
*/

/* AI
Topic: Few-Shot Prompt Engineering for GPT‑3.5

Explanation:  
Few‑shot prompting lets you guide a large language model by providing a few example input‑output pairs within the same request. This technique reduces the need for extensive fine‑tuning while still achieving task‑specific behavior. By carefully selecting diverse examples, the model can infer the desired pattern and apply it to new inputs. It works well for classification, transformation, and generation tasks where labeled data is scarce. The approach is simple to implement using the OpenAI API and works with both chat and completion endpoints.

Code example (Python, using the openai library):
import openai

# Set your OpenAI API key (replace with your actual key or use environment variable)
openai.api_key = "sk-YOUR_API_KEY"

def classify_sentiment(text):
    """
    Classify the sentiment of the given text as Positive, Negative, or Neutral
    using a few‑shot prompt.
    """
    # Define a prompt that includes two labeled examples and the new query
    prompt = (
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"
        "Sentence: I love the new design of the app.\n"
        "Sentiment: Positive\n\n"
        "Sentence: The update caused many crashes and is frustrating.\n"
        "Sentiment: Negative\n\n"
        f"Sentence: {text}\n"
        "Sentiment:"
    )

    # Call the completion endpoint with temperature set low for deterministic output
    response = openai.Completion.create(
        model="text-davinci-003",
        prompt=prompt,
        max_tokens=10,
        temperature=0.0,
        stop=["\n"]
    )
    # Extract and return the sentiment label from the response
    sentiment = response.choices[0].text.strip()
    return sentiment

# Example usage
sample = "The documentation was clear and helpful."
print(f"Input: {sample}")
print("Predicted Sentiment:", classify_sentiment(sample))
*/

