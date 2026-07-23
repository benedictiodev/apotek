const format_rupiah = (nominal) => {
    const result = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(nominal);

    return result.split(",")[0];
};

function update_to_format_rupiah(nominal) {
    nominal = nominal.toString();
    var number_string = nominal.replace(/[^,\d]/g, "").toString();
    var split = number_string.split(",");
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah;
}
function keyup_rupiah(element) {
    element.value = update_to_format_rupiah(element.value);
}

/**
 * Converts a snake_case string to camelCase.
 * e.g., 'hello_world' becomes 'helloWorld'
 *
 * @param {string} str The input string in snake_case.
 * @returns {string} The converted string in camelCase.
 */
const snakeToCamelCase = (str) => {
    // Check if the input is a string
    if (typeof str !== "string") {
        console.error("Input must be a string for snakeToCamelCase.");
        return "";
    }
    // Use replace with a regular expression to find underscores followed by a letter.
    // The matched letter (char) is converted to uppercase.
    return str.replace(/_([a-z])/g, (match, char) => char.toUpperCase());
};

/**
 * Converts a snake_case string to kebab-case.
 * e.g., 'hello_world' becomes 'hello-world'
 *
 * @param {string} str The input string in snake_case.
 * @returns {string} The converted string in kebab-case.
 */
const snakeToKebabCase = (str) => {
    // Check if the input is a string
    if (typeof str !== "string") {
        console.error("Input must be a string for snakeToKebabCase.");
        return "";
    }
    // Simply replace all underscores with hyphens.
    return str.replace(/_/g, "-");
};

/**
 * Converts a kebab-case string to snake_case.
 * e.g., 'hello-world' becomes 'hello_world'
 *
 * @param {string} str The input string in kebab-case.
 * @returns {string} The converted string in snake_case.
 */
const kebabToSnakeCase = (str) => {
    // Check if the input is a string
    if (typeof str !== "string") {
        console.error("Input must be a string for kebabToSnakeCase.");
        return "";
    }
    // Simply replace all hyphens with underscores.
    return str.replace(/-/g, "_");
};

/**
 * Converts a kebab-case string to camelCase.
 * e.g., 'hello-world' becomes 'helloWorld'
 *
 * @param {string} str The input string in kebab-case.
 * @returns {string} The converted string in camelCase.
 */
const kebabToCamelCase = (str) => {
    // Check if the input is a string
    if (typeof str !== "string") {
        console.error("Input must be a string for kebabToCamelCase.");
        return "";
    }
    // Use replace with a regular expression to find hyphens followed by a letter.
    // The matched letter (char) is converted to uppercase.
    return str.replace(/-([a-z])/g, (match, char) => char.toUpperCase());
};
