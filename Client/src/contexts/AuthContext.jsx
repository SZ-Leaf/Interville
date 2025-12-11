import { useState } from "react";
import { parseJwt, isTokenValid } from "@/utils/auth";
import { AuthContext } from "@/context";

/**
 * AuthProvider Component
 *
 * Manages the global authentication state of the application.
 * It initializes the user state from localStorage to persist sessions.
 */
export const AuthProvider = ({ children }) => {
  // Initialize user state lazily to avoid synchronous state update warnings
  // and to ensure we only read from localStorage once on mount.
  const [user, setUser] = useState(() => {
    const token = localStorage.getItem("token");
    if (token) {
      // Validate token expiration before restoring the session
      if (isTokenValid(token)) {
        const userData = parseJwt(token);
        return { ...userData, token };
      } else {
        // Clear invalid/expired token
        localStorage.removeItem("token");
      }
    }
    return null;
  });

  /**
   * Logs the user in by storing the token and updating state.
   * @param {string} token - The JWT token received from the backend.
   */
  const login = (token) => {
    localStorage.setItem("token", token);
    const userData = parseJwt(token);
    setUser({ ...userData, token });
  };

  /**
   * Logs the user out by clearing the token and state.
   */
  const logout = () => {
    localStorage.removeItem("token");
    setUser(null);
  };

  /**
   * Checks if the current user has admin privileges.
   * @returns {boolean}
   */
  const isAdmin = () => {
    return user && user.roles && user.roles.includes("ROLE_ADMIN");
  };

  return (
    <AuthContext.Provider value={{ user, login, logout, isAdmin }}>
      {children}
    </AuthContext.Provider>
  );
};
