// This is just an example of a js function to decrypt the data
// Our browser extension will have a modified version of this function 
// It will render the HTMl with no password if no password is set and if that fails it will prompt user for a password
async function decryptHtml(encryptedData, password) {
    // Extract salt, iv, tag, and ciphertext
    const encryptedDataJSON = JSON.parse(encryptedData);
    const ciphertext = Uint8Array.from(atob(encryptedDataJSON.ciphertext), c => c.charCodeAt(0));
    const iv = Uint8Array.from(atob(encryptedDataJSON.iv), c => c.charCodeAt(0));
    const salt = Uint8Array.from(atob(encryptedDataJSON.salt), c => c.charCodeAt(0));
    const tag = Uint8Array.from(atob(encryptedDataJSON.tag), c => c.charCodeAt(0));
    // Derive key from password and salt using PBKDF2
    const passwordKey = await crypto.subtle.importKey(
        'raw',
        new TextEncoder().encode(password),
        { name: 'PBKDF2' },
        false,
        ['deriveKey']
    );
    const aesKey = await crypto.subtle.deriveKey(
        {
            name: 'PBKDF2',
            salt: salt,
            iterations: 10000,
            hash: 'SHA-256',
        },
        passwordKey,
        { name: 'AES-GCM', length: 256 },
        false,
        ['decrypt']
    );
    try {
        // Decrypt the ciphertext
        const decryptedContent = await crypto.subtle.decrypt(
            {
                name: 'AES-GCM',
                iv: iv,
                additionalData: new Uint8Array(), // No additional data used in PHP example
                tagLength: 128, // 16 bytes * 8 bits/byte = 128 bits
            },
            aesKey,
            new Uint8Array([...ciphertext, ...tag]) // Combine ciphertext and tag for decryption
        );
        return new TextDecoder().decode(decryptedContent);
    } catch (error) {
        console.error("Decryption failed:", error);
        return null;
    }
}