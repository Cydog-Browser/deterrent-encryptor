# Cydog Browser Deterrent Encryptor
This is a free-to-use PHP drop-in to secure your website. It dynamically generates encryption for web pages and only users with our most updated Cydog Toolkit Extension will be able to view the content of pages served with our Deterrent Encryptor. 

**Pages served with Deterrent Encryptor will only work for users on Cydog Toolkit Browser Extension version 1.1.7+.**

## Installation & Implementation
1. Download cy-page-encryptor.php
2. Add to backend of web project (e.g., `/some/backend/path/internet/cannot/access/here/cy-page-encryptor.php`)
3. Create a bridge that will serve users the encrypted web page content on the frontend from a path that can be accessed by anyone visiting your website. See example of contents of /some/frontend/path/internet/can/access/your_page.php below:
```php
include "/some/backend/path/internet/cannot/access/here/cy-page-encryptor.php";
$webpage = encryptWebPage("/some/backend/path/internet/cannot/access/here.php", "your_chosen_password", "your_file_type");
if($webpage){
    echo $webpage;
}
```

## Core Protections
| Feature | Protection Level | Impact |
|---------|------------------|--------|
| **Dynamic Encryption** | Critical | Prevents manipulation in transit |
| **Secure Authentication** | Critical | Enhances authentication strategies |

## Implementation Notes
1. **Supported file types**: 
  - html
    - Serves static HTML/Output produced, which secures page against scripting injections.
  - php
    - Dynamically run PHP script/page and serves only the HTML/Output produced, which secures server against PHP related attacks.
  - other (under development)
    - Serves any output produced, which allows for encrypted APIs of any JSON output.

## Performance Impact
- Unknown
    - Testing is ongoing

## Security Considerations
1. **Backend Security**: Place all pages in website backend and serve only display pages:
    - Add user and password protection to backend with no user and password access (i.e. htaccess)
    - Examples:
        - display-test-example.php would be in the frontend
        - test.php would be in the backend
        - cy-page-encryptor.php would be in the backend
        - display-test-example.php and cy-page-encryptor.php would create a bridge, allowing test.php to be rendered as an encrypted page so our extension can do its work

2. **Use Authentication**: Never remove webpage authentication and instead:
   - Enhance authentication for important pages and/or
   - Block bad actors who scrape your web page on non-authentication pages

## Server Compatibility
This solution works in:
- Apache
- Nginx

> **Critical Note**: This script supplements but doesn't replace server-side security. Always implement backend mechanisms like PHP Sessions with usernames and password and multi-factor authentication.

## Contribute
Send me a pull request!

## See our terms & conditions
[Our terms & conditions](https://cydogbrowser.com/cyterms.html)

## Want to know more?
Visit [https://cydogbrowser.com](https://cydogbrowser.com/)
