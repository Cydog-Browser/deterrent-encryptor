# Cydog Browser Trustless Authorization (TA)
This is a free-to-use authorization technique that replaces antiquated [JSON Web Token (JWT)](https://www.geeksforgeeks.org/web-tech/json-web-token-jwt/) architectures.

## How do JWT's work and why has deterrence encryption made them antiquated?
JWT's were created in 2015, nearly ten years ago. They dictate trusted interactions between a browser and a server, authorizing use. When a user authenticates via a login event, the server creates an authorization token in JSON format returning it to the browser. On future interactions between the browser and server, the server will only honor authenticated users with an authorization header attached to requests with the server. This is called a trustful request scenario. 

## How does Cydog Browser's TA work?
TA creates a trustless request scenario. Your browser has a unique fingerprint that does not change and cannot be duplicated. You add [fingerprint2.min.js](https://raw.githubusercontent.com/Cydog-Browser/deterrent-encryptor/refs/heads/main/trustless-authorization/fingerprint2.min.js) to all your pages, setting a cookie with the fingerprint created by fingerprint2.min.js. Using our deterrence encryptor program, you grab the cookie in PHP and encrypt page contents using the fingerprint as the encryption password. When the fingerprint changes, this will dynamically change the deterrence encryptor password, allowing the page to be completely authorized to that user in that browser without worrying about sessions or signatures.

## Why is TA lightyears better than JWT?
JWTs use the server to create signing systems. If a server is compromised, the signing system is ineffective at signing. If the browser is compromised, the signature can be stolen to request data from the server. Instead, TA offloads the signature to the user. If the browser is compromised, the fingerprint will change, rendering a stolen fingerprint signature worthless. A hallmark of browser fingerprinting is the tiniest detail will change a fingerprint so a browser compromise will inevitably change this fingerprint. This method requires no extra encryption, signing, and/or reauthorization, as the complexity for all three is handled locally on the browser in non-reproducible ways, saving resources. TA also works in tandem with deterrence encryption so that each subsequent page request is encrypted dynamically to the fingerprint cookie so users will not even notice the difference when a page has been secured from a potential tamper-related event, mitigating a browser compromise.

## Installation & Implementation
1. Download cy-page-encryptor.php
2. Add to backend of web project (e.g., `/some/backend/path/internet/cannot/access/here/cy-page-encryptor.php`)
3. Create a bridge that will serve users the encrypted web page content on the frontend from a path that can be accessed by anyone visiting your website. See advanced example of contents of /some/frontend/path/internet/can/access/your_page.php using our TA system below:
```php
<!--This will also block all web scraping, except headless browser scraping (which we show you how to solve with later techniques).-->
<!DOCTYPE html>
<html lang='en'>
    <head>
        <script src="fingerprint2.min.js"></script>
        <script src="cy-page-decryptor.js"></script>
    </head>
    <body>
    <?php
        if (isset($_COOKIE['ta'])) {
            $ta = $_COOKIE['ta'];
            include '/some/backend/path/internet/cannot/access/here/cy-page-encryptor.php';
            $webpage = encryptWebPage('/some/backend/path/internet/cannot/access/here.php', $ta, 'your_file_type');
            if($webpage){
                echo $webpage;
            }
        } else {
            <p>There was a backend error rendering this web page.</p>
        }
    ?>
    </body>
    <script>
        const encryptedHtml = document.body.innerHTML;
        var decryptedHtml = "";
        getHTML();
        async function getHTML(){
            decryptedHtml = await decryptHtml(encryptedHtml, getCookie('ta'));
            document.body.innerHTML = '';
            const iframe = document.createElement('iframe');
            iframe.width = '100vw';
            iframe.height = '100vh';
            iframe.id = 'ta-example';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);
            const taExample = document.getElementById('ta-example');
            const iframeDoc = taExample.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(decryptedHtml);
            iframeDoc.close();
        }
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
                }
                if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
                }
            }
            return null;
        }
    </script>
</html>
```

## Notes
Although you can decrypt via the web browser with automated scripts, like we have done in the example above, we suggest using our browser extension. **Support for automatic TA will be available in 1.2.0+.** Automatically decrypting in JS is secure. But, using our browser extension is infinitely more secure, as it will protect you from browser compromises that attempt to block scripts from running on the page you are visiting, decreasing errors that come from the kinds of compromises which tend to make users want to stop using security technologies altogether. That said, using our extension is only recommended for tightly controlled pages. It is not meant for daily and regularized interactions with a server, like login pages and/or backend authenticated pages. It might be helpful to use automated decryption scripts for public-facing pages to ensure only humans are using your web page, protecting resources. But, this will harm a page's Search Engine Optimization (SEO). Consider your use-case accordingly. 

## Contribute
Send me a pull request!

## See our terms & conditions
[Our terms & conditions](https://cydogbrowser.com/cyterms.html)

## Want to know more?
Visit [https://cydogbrowser.com](https://cydogbrowser.com/)