<?php
// 2026-11-09 08:37:34

/* PHP
PHP PDO (PHP Data Objects) for Secure Database Access
The PDO extension provides a consistent interface for accessing different databases (MySQL, PostgreSQL, SQLite, etc.) using the same functions.
It supports prepared statements, which protect against SQL injection by separating query structure from data.
PDO allows you to fetch results as associative arrays, objects, or both, giving flexibility in handling result sets.
Error handling can be set to throw exceptions, making debugging and error management more straightforward.
Using PDO encourages clean, reusable code and makes it easier to switch database drivers without rewriting queries.

<?php
// Database connection parameters
$host = 'localhost';
$db   = 'example_db';
$user = 'db_user';
$pass = 'db_password';
$charset = 'utf8mb4';

// Data Source Name (DSN) tells PDO which driver and database to use
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options improve error handling and performance
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return rows as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    // Create a new PDO instance (establishes the connection)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle connection errors gracefully
    die('Connection failed: ' . $e->getMessage());
}

// Example of a prepared SELECT statement
$sql = 'SELECT id, name, email FROM users WHERE status = :status';
$stmt = $pdo->prepare($sql);               // Prepare the query template
$stmt->execute(['status' => 'active']);    // Bind value and execute

// Fetch all matching rows
$users = $stmt->fetchAll();

foreach ($users as $user) {
    // Output each user's information
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Laravel Topic: Queues and Background Jobs  

Explanation:  
Laravel queues allow time‑consuming tasks such as sending emails, processing uploads, or generating reports to be performed asynchronously, keeping the HTTP response fast.  
A job class encapsulates the logic that should run in the background, while a queue worker processes pending jobs from the chosen driver (database, Redis, SQS, etc.).  
Jobs can be dispatched immediately, delayed, or chained together for complex workflows.  
Laravel provides helper methods to retry failed jobs, monitor queue health, and handle failures gracefully.  
Using queues also helps scale applications horizontally by adding more workers without changing the core code.  

Code Example (Job class and dispatch):  

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; // The user instance to receive the email

    // Job receives data via constructor
    public function __construct($user)
    {
        $this->user = $user;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Send the welcome email using a Mailable class
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));
    }

    // Optional: Define how many times the job may be retried
    public function retryUntil()
    {
        return now()->addMinutes(5);
    }
}

// Dispatching the job from a controller or any other place
use App\Jobs\SendWelcomeEmail;

public function register(Request $request)
{
    // Validate and create the user...
    $user = User::create($request->all());

    // Dispatch the email job to the default queue
    SendWelcomeEmail::dispatch($user);

    // Or dispatch with a delay of 2 minutes
    // SendWelcomeEmail::dispatch($user)->delay(now()->addMinutes(2));

    return response()->json(['message' => 'User registered, welcome email queued.']);
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It improves query readability by allowing you to define subqueries with a name and refer to them multiple times. CTEs are defined using the WITH clause and can be either non‑recursive or recursive. Recursive CTEs are useful for traversing hierarchical data such as organization charts or folder structures. The recursion stops when the CTE’s anchor query no longer produces rows, preventing infinite loops.

Code Example with Comments:  
WITH RECURSIVE org_chart AS (  
    -- Anchor member: select the top‑level manager(s)  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  
  
    UNION ALL  
  
    -- Recursive member: find employees reporting to the current level  
    SELECT e.employee_id, e.manager_id, e.employee_name, oc.level + 1 AS level  
    FROM employees e  
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id  
)  
SELECT employee_id, manager_id, employee_name, level  
FROM org_chart  
ORDER BY level, manager_id;  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is created when an inner function retains access to the variables of its outer (enclosing) function after that outer function has finished executing. This allows the inner function to preserve state between calls without using global variables. Closures are fundamental for data encapsulation, partial application, and implementing private members in objects. They are formed automatically whenever a function is defined inside another function and the inner function references variables from the outer scope. Understanding closures helps avoid common pitfalls such as unintended memory leaks and stale references.

Code example with comments:
function makeCounter() {
    // Private variable that will be captured by the inner function
    var count = 0;

    // The inner function forms a closure over 'count'
    return function() {
        // Increment the private count each time the returned function is called
        count++;
        console.log('Current count:', count);
    };
}

// Create a new counter instance
var counterA = makeCounter();

counterA(); // Current count: 1
counterA(); // Current count: 2

// Create a second, independent counter
var counterB = makeCounter();

counterB(); // Current count: 1
counterA(); // Current count: 3   (counterA maintains its own separate state)
*/

/* AI
Topic: Neural Style Transfer with PyTorch  

Explanation:  
Neural style transfer (NST) blends the artistic style of one image with the content of another by optimizing a generated image to match the high‑level features of the content image and the Gram‑matrix statistics of the style image. The approach uses a pretrained convolutional network (e.g., VGG‑19) to extract feature maps at multiple layers. Content loss measures the difference between the generated and content feature activations, while style loss measures the difference between their Gram matrices. By iteratively updating the generated image with gradient descent, the result gradually adopts the visual texture of the style image while preserving the original scene layout. PyTorch’s autograd makes the optimization loop concise and GPU‑accelerated.

Code example (Python, PyTorch):  

import torch  
import torch.nn as nn  
import torchvision.transforms as transforms  
import torchvision.models as models  
from PIL import Image  

# Helper to load and preprocess images  
def load_image(path, max_size=512):  
    image = Image.open(path).convert('RGB')  
    size = max(image.size)  
    if size > max_size:  
        scale = max_size / size  
        new_size = (int(image.size[0] * scale), int(image.size[1] * scale))  
        image = image.resize(new_size, Image.ANTIALIAS)  
    loader = transforms.Compose([transforms.ToTensor()])  
    tensor = loader(image).unsqueeze(0)  # add batch dimension  
    return tensor.to(device)  

# Compute Gram matrix for a feature map  
def gram_matrix(tensor):  
    b, c, h, w = tensor.size()  
    features = tensor.view(b * c, h * w)  
    G = torch.mm(features, features.t())  
    return G.div(b * c * h * w)  

# Define a model that returns selected layer activations  
class VGGFeatures(nn.Module):  
    def __init__(self, layers):  
        super(VGGFeatures, self).__init__()  
        self.vgg = models.vgg19(pretrained=True).features.eval()  
        self.layers = layers  
        for param in self.vgg.parameters():  
            param.requires_grad = False  

    def forward(self, x):  
        outputs = []  
        for name, layer in self.vgg._modules.items():  
            x = layer(x)  
            if name in self.layers:  
                outputs.append(x)  
        return outputs  

device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')  

content_img = load_image('content.jpg').to(device)  
style_img   = load_image('style.jpg').to(device)  

# Choose layers for content and style representations  
content_layers = ['21']          # conv4_2 in VGG19  
style_layers   = ['0', '5', '10', '19', '28']  # conv1_1, conv2_1, conv3_1, conv4_1, conv5_1  

vgg = VGGFeatures(content_layers + style_layers).to(device)  

# Extract target features  
with torch.no_grad():  
    content_target = vgg(content_img)[0]  
    style_targets  = [gram_matrix(f) for f in vgg(style_img)[len(content_layers):]]  

# Initialize generated image as a copy of the content image (requires gradient)  
generated = content_img.clone().requires_grad_(True)  

optimizer = torch.optim.Adam([generated], lr=0.02)  
content_weight = 1e5  
style_weight   = 1e10  
num_steps = 300  

for step in range(num_steps):  
    optimizer.zero_grad()  
    feats = vgg(generated)  
    gen_content = feats[0]  
    gen_style_feats = feats[len(content_layers):]  

    # Content loss (MSE)  
    content_loss = nn.functional.mse_loss(gen_content, content_target)  

    # Style loss (MSE of Gram matrices)  
    style_loss = 0  
    for gen_feat, style_gram in zip(gen_style_feats, style_targets):  
        gen_gram = gram_matrix(gen_feat)  
        style_loss += nn.functional.mse_loss(gen_gram, style_gram)  

    total_loss = content_weight * content_loss + style_weight * style_loss  
    total_loss.backward()  
    optimizer.step()  

    # Clamp pixel values to valid range  
    with torch.no_grad():  
        generated.clamp_(0, 1)  

    if (step + 1) % 50 == 0:  
        print(f'Step {step+1}/{num_steps}, Loss: {total_loss.item():.4f}')  

# Save result  
output = generated.cpu().squeeze(0)  
output = transforms.ToPILImage()(output)  
output.save('output.jpg')  
*/

