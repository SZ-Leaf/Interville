/**
 * Decodes a JWT token to extract the payload.
 * Note: This does not verify the signature, only decodes the base64 payload.
 *
 * @param {string} token - The JWT token string.
 * @returns {Object|null} The decoded payload or null if parsing fails.
 */
export const parseJwt = (token) => {
  try {
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const jsonPayload = decodeURIComponent(
      window
        .atob(base64)
        .split("")
        .map(function (c) {
          return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
        })
        .join("")
    );

    return JSON.parse(jsonPayload);
  } catch (e) {
    console.error("An error occured during parsing: ", e);

    return null;
  }
};

/**
 * Checks if a JWT token is valid based on its expiration time.
 *
 * @param {string} token - The JWT token string.
 * @returns {boolean} True if valid and not expired.
 */
export const isTokenValid = (token) => {
  if (!token) return false;
  const decoded = parseJwt(token);
  if (!decoded) return false;

  const currentTime = Date.now() / 1000;
  return decoded.exp > currentTime;
};
