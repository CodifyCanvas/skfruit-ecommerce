function sanitizeFormData(input) {
    if (input instanceof FormData) {
        // Sanitize FormData
        const sanitizedFormData = new FormData();

        // Iterate over FormData entries
        input.forEach((value, key) => {
            if (typeof value === 'string') {
                // Trim and convert to lowercase for string fields
                sanitizedFormData.set(key, value.trim().toLowerCase());
            } else {
                // For non-string fields (like files), keep them unchanged
                sanitizedFormData.set(key, value);
            }
        });

        return sanitizedFormData;
    } else if (Array.isArray(input)) {
        // Sanitize Array (use the same logic as with objects)
        return input.map(item => sanitizeFormData(item));
    } else if (typeof input === 'object' && input !== null) {
        // Sanitize Plain Object
        const sanitizedObject = {};
        
        Object.keys(input).forEach(key => {
            let value = input[key];
            if (typeof value === 'string') {
                // Trim and convert to lowercase for string fields
                sanitizedObject[key] = value.trim().toLowerCase();
            } else {
                // Keep non-string fields unchanged
                sanitizedObject[key] = value;
            }
        });

        return sanitizedObject;
    }

    // If input is not a valid type, return it unchanged
    return input;
}
