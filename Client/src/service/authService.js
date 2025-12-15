import api from "./api";

/**
 * Authentication Service
 * Handles all authentication-related API calls
 */

/**
 * Register a new user
 * @param {Object} userData - User registration data
 * @param {string} userData.email - User email
 * @param {string} userData.password - User password
 * @param {string} userData.username - User full name
 * @returns {Promise<Object>} Response with JWT token
 */
export const register = async (userData) => {
  try {
    const response = await api.post("/register", userData);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Registration failed" };
  }
};

/**
 * Login user
 * @param {Object} credentials - User credentials
 * @param {string} credentials.username - User email
 * @param {string} credentials.password - User password
 * @returns {Promise<Object>} Response with JWT token
 */
export const login = async (credentials) => {
  try {
    const response = await api.post("/login", credentials);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Login failed" };
  }
};

/**
 * Logout user (if backend requires logout endpoint)
 * @returns {Promise<void>}
 */
export const logout = async () => {
  try {
    await api.post("/logout");
  } catch (error) {
    // Silent fail - token will be removed from localStorage anyway
    console.error("Logout error:", error);
  }
};

/**
 * Get current user profile
 * @returns {Promise<Object>} User data
 */
export const getCurrentUser = async () => {
  try {
    const response = await api.get("/user/profile");
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to fetch user profile" };
  }
};

/**
 * Update user profile
 * @param {Object} userData - Updated user data
 * @returns {Promise<Object>} Updated user data
 */
export const updateProfile = async (userData) => {
  try {
    const response = await api.put("/user/profile", userData);
    return response.data;
  } catch (error) {
    throw error.response?.data || { message: "Failed to update profile" };
  }
};
